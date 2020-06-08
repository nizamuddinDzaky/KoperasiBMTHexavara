<?php

namespace App\Http\Controllers;

use App\BMT;
use App\Deposito;
use App\Pembiayaan;
use App\Pengajuan;
use App\PenyimpananDeposito;
use App\PenyimpananBMT;
use App\PenyimpananJaminan;
use App\Repositories\InformationRepository;
use App\Tabungan;
use Illuminate\Http\Response;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use Illuminate\Support\Facades\Hash;
use App\Repositories\TabunganReporsitories;
use App\Repositories\DepositoReporsitories;
use App\Repositories\DonasiReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\SimpananReporsitory;
use App\Repositories\PembiayaanReporsitory;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\AccountReporsitories;

class TellerController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    protected $id_role;

    public function __construct(Rekening $rekening,
                                User $user,
                                Tabungan $tabungan,
                                Pembiayaan $pembiayaan,
                                Deposito $deposito,
                                Pengajuan $pengajuan,
                                InformationRepository $informationRepository,
                                TabunganReporsitories $tabunganReporsitory,
                                DepositoReporsitories $depositoReporsitory,
                                DonasiReporsitories $donasiReporsitory,
                                RekeningReporsitories $rekeningReporsitory,
                                SimpananReporsitory $simpananReporsitory,
                                PembiayaanReporsitory $pembiayaanReporsitory,
                                PengajuanReporsitories $pengajuanReporsitory,
                                AccountReporsitories $accountReporsitory
                                )
    {
        $this->middleware(function ($request, $next) {
            $this->id_role = Auth::user()->tipe;
            if (!$this->id_role == "teller")
                return redirect('login')->with('status', [
                    'enabled' => true,
                    'type' => 'danger',
                    'content' => 'Tidak boleh mengakses'
                ]);
            return $next($request);
        });
        $this->rekening = $rekening;
        $this->user = $user;
        $this->tabungan = $tabungan;
        $this->deposito = $deposito;
        $this->pembiayaan = $pembiayaan;
        $this->pengajuan = $pengajuan;
        $this->informationRepository = $informationRepository;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->depositoReporsitory = $depositoReporsitory;
        $this->donasiReporsitory = $donasiReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->simpananReporsitory = $simpananReporsitory;
        $this->pembiayaanReporsitory = $pembiayaanReporsitory;
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->accountReporsitory = $accountReporsitory;
    }

    public function index(){
        $home = new HomeController;
        $date_now = $home->MonthShifter(+1)->format(('Y-m'));
        $date_prev = $home->MonthShifter(-1)->format(('Y-m-t'));

        $bmtTeller = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->select(['saldo'])->first();
        
        $rekeningTabungan = Rekening::where([ ['tipe_rekening', 'detail'], ['katagori_rekening', 'TABUNGAN'] ])->get();
        $idRekeningTabungan = array();
        foreach($rekeningTabungan as $tabungan)
        {
            array_push($idRekeningTabungan, $tabungan->id);
        }
        $bmtTabungan = BMT::whereIn('id_rekening', $idRekeningTabungan)->select(['saldo', 'nama'])->get();
        $saldoTabungan = 0;
        foreach($bmtTabungan as $bmttabungan)
        {
            $saldoTabungan = $saldoTabungan + $bmttabungan->saldo;
        }

        $rekeningDeposito = Rekening::where([ ['tipe_rekening', 'detail'], ['katagori_rekening', 'DEPOSITO'] ])->get();
        $idRekeningDeposito = array();
        foreach($rekeningDeposito as $deposito)
        {
            array_push($idRekeningDeposito, $deposito->id);
        }
        $bmtDeposito = BMT::whereIn('id_rekening', $idRekeningDeposito)->select(['saldo', 'nama'])->get();
        $saldoDeposito = 0;
        foreach($bmtDeposito as $bmtdeposito)
        {
            $saldoDeposito = $saldoDeposito + $bmtdeposito->saldo;
        }

        $rekeningPembiayaan = Rekening::where([ ['tipe_rekening', 'detail'], ['katagori_rekening', 'PEMBIAYAAN'] ])->get();
        $idRekeningPembiayaan = array();
        foreach($rekeningPembiayaan as $pembiayaan)
        {
            array_push($idRekeningPembiayaan, $pembiayaan->id);
        }
        $bmtPembiayaan = BMT::whereIn('id_rekening', $idRekeningPembiayaan)->select(['saldo', 'nama'])->get();
        $saldoPembiayaan = 0;
        foreach($bmtPembiayaan as $bmtpembiayaan)
        {
            $saldoPembiayaan = $saldoPembiayaan + $bmtpembiayaan->saldo;
        }

        $pengajuan =$this->pengajuan->select('status')
            ->where('pengajuan.created_at', ">" , $date_prev." 23:59:59")
            ->where('pengajuan.created_at', "<" , $date_now."-01")
            ->get();
        $set=$tol=$pen=0;
        foreach ($pengajuan as $p){
            if($p->status=="Menunggu Konfirmasi")$pen +=1;
            elseif(str_before($p->status," ")=="Ditolak")$tol +=1;
            elseif($p->status=="Disetujui" || str_before($p->status," ")=="Disetujui"  || str_before($p->status," ")=="Sudah" ||str_before($p->status," ")=="[Disetujui" )$set +=1;
        }

        $bmt = $this->informationRepository->getRekeningBMT(json_decode(Auth::user()->detail,true)['id_rekening']);
        
        return view('teller.dashboard',[
            'teller' =>$bmt,
            'setuju' =>$set,
            'tolak' =>$tol,
            'pending' =>$pen,
            'saldo_kas' => $bmtTeller->saldo,
            'saldo_tabungan' => $saldoTabungan,
            'saldo_deposito' => $saldoDeposito,
            'saldo_pembiayaan' => $saldoPembiayaan
        ]);
    }

    public function datadiri(){
        return view('teller.datadiri',[
            'dropdown7' => $this->informationRepository->getDdTeller()
        ]);
    }

    //    TRANSAKSI
    public function pengajuan(){
        $home = new HomeController;
        $date = $home->date_query(0);
        return view('admin.transaksi.pengajuan',[
            'kegiatan' => $this->informationRepository->getAllMaal(),
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'datasaldoDep' =>  $this->informationRepository->getAllDepUsr(),
            'datasaldo' =>  $this->informationRepository->getTabUsr(),
            'data' =>  $this->informationRepository->getAllpengajuanTell($date),
            'data2' => $this->informationRepository->getAllpengajuanUsrTab(),
            'tab' =>  $this->informationRepository->getAllTab(),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'dropdown' => $this->informationRepository->getDdTab(),
            'dropdown2' => $this->informationRepository->getDdDep(),
            'dropdown3' => $this->informationRepository->getDdPem(),
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()
        ]);
    }
    public function periode_pengajuan(Request $request){
        $home = new HomeController;
        $date = $home->date_query($request->periode);
        return view('admin.transaksi.pengajuan',[
            'kegiatan' => $this->informationRepository->getAllMaal(),
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'datasaldoDep' =>  $this->informationRepository->getAllDepUsr(),
            'datasaldo' =>  $this->informationRepository->getTabUsr(),
            'data' =>  $this->informationRepository->getAllpengajuanTell($date),
            'data2' => $this->informationRepository->getAllpengajuanUsrTab(),
            'tab' =>  $this->informationRepository->getAllTab(),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'dropdown' => $this->informationRepository->getDdTab(),
            'dropdown2' => $this->informationRepository->getDdDep(),
            'dropdown3' => $this->informationRepository->getDdPem(),
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()
        ]);
    }

    public function status_pengajuan(Request $request){
        $detail = [
            'id'  => $request->id_,
            'id_user'  => $request->id_user,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ];
        if($this->informationRepository->pengajuanStatus($detail)){
            return redirect()
                ->back()
                ->withSuccess(sprintf('Status Pengajuan berhasil diubah!.'));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Status Pengajuan gagal diubah!.');
        }
    }

    //    UNTUK PEMBUKAAN REKENING BARU
    public function daftar_pengajuan_baru($request,$usr){
        // $user = User::where('no_ktp',$request->nama_nasabah)->first();

        if ($request->atasnama == 1) {
            $atasnama = "Pribadi";
            $nama = $usr['nama'];
        } else {
            $atasnama = "Lembaga";
            $nama = $request->nama;
//            $id_user = $request->id_user;
        }
        $id_user = $usr['id'];
        $data =[
            'atasnama' => $atasnama,
            'nama' => $nama,
            'id' => $id_user,
            'keterangan' => $request->keterangan,
        ];
        $ket =[
            'jenis' =>"Buka ",
            'status' => "Disetujui",
        ];

        if($request->tabungan!=null) {
            $data['akad'] = $request->tabungan;
            $data['tabungan']=$request->tabungan;
            $ket['jenis']=$ket['jenis']."Tabungan";
            return $this->informationRepository->pengajuanTab($data,$ket);
        }
        elseif($request->deposito!=null) {
            $data['jumlah']=$request->jumlah;
            $data['deposito']=$request->deposito_;
            $data['id_pencairan']=$request->rek_tabungan;
            $ket['jenis']=$ket['jenis']."Deposito";


            return $this->informationRepository->pengajuanDep($data,$ket);
        }
        elseif($request->pembiayaan!=null) {
            $data['pembiayaan']=$request->pembiayaan;
            $data['jumlah']=$request->jumlah;
            $data['jenis_Usaha'] = $request->jenisUsaha;
            $data['usaha'] = $request->usaha;
            $data['keterangan'] = $request->waktu . " " . $request->ketWaktu;
            $data['jaminan'] = $request->jaminan;
            $ket['jenis']=$ket['jenis']."Pembiayaan";

            return $this->informationRepository->pengajuanPem($data,$ket,$request);
        }
    }
    public function active_pengajuan(Request $request){
        $detail = [
            'id'  => $request->id_,
            'syarat' => $request->syarat,
            'identitas'  => $request->identitas,
        ];
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);

//        VIA TELLER atau ADMIN
        if($request->nama_nasabah !=null){

            $usr =$this->informationRepository->getUsrByKtp($request->nama_nasabah);
            if($request->tabungan !=null){
                $rekening =$this->informationRepository->getRekeningByid($request->tabungan);
                $detail_ptabungan=[
                    'teller'         => Auth::user()->id,
                    'dari_rekening'  => "",
                    'untuk_rekening' => json_decode(Auth::user()->detail,true)['id_rekening'],
                    'jumlah'         => json_decode($rekening->detail,true)['setoran_awal'],
                    'saldo_awal'     => 0,
                    'saldo_akhir'    => json_decode($rekening->detail,true)['setoran_awal']
                ];
                $tab = $this->tabungan->where('id_user', $usr->id)->orderBy('id','DESC')->get();
                if(count($tab)==0)$id=1;
                else $id=str_after($tab[0]['id_tabungan'], '.')+1;
                $id_pengajuan =$this->daftar_pengajuan_baru($request,$usr);
                $data=[
                    'tipe'      =>  "Tabungan",
                    'id_UsrTDP' => $usr->id.".".$id,
                    'id_TDP'=>  $request->tabungan,
                    'id_pengajuan' =>$id_pengajuan,
                    'jenis_TDP' => $rekening->nama_rekening,
                    'id_user' => $usr->id,
                    'status' =>"Setoran Awal",
                ];
                if($this->informationRepository->setoranAwal($detail_ptabungan,$data)){
//                    teller
                    $dt['id']=$detail_ptabungan['untuk_rekening'];
                    $dt['jumlah']=$detail_ptabungan['saldo_akhir'];
//                    $this->informationRepository->updateSaldoRekening($dt);
//                    tabungan
                    $dt['id']=$rekening['id'];
                    $dt['jumlah']=$detail_ptabungan['saldo_akhir'];
//                    $this->informationRepository->updateSaldoRekening($dt);
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf('Pembukaan Rekening Tabungan berhasil dilakukan!.'));
                }
                else{
                    if($this->informationRepository->delPengajuan($id_pengajuan))
                        return redirect()
                            ->back()
                            ->withInput()->with('message', 'Pembukaan Rekening Tabungan gagal dilakukan!.');
                }
            }
            elseif($request->deposito !=null){
                $rekening =$this->informationRepository->getRekeningByid($request->deposito);
                $detail_pdeposito=[
                    'teller'         => Auth::user()->id,
                    'dari_rekening'  => "",
                    'untuk_rekening' => json_decode(Auth::user()->detail,true)['id_rekening'],
                    'jumlah'         =>$request->jumlah,
                    'saldo_awal'     => 0,
                    'saldo_akhir'    => $request->jumlah
                ];

                $tab = $this->deposito->where('id_user', $usr->id)->orderBy('id','DESC')->get();
                if(count($tab)==0)$id=1;
                else $id=str_after($tab[0]['id_deposito'], '.')+1;
                $id_pengajuan =$this->daftar_pengajuan_baru($request,$usr);
                $data=[
                    'tipe'      =>  "Deposito",
                    'tempo' =>json_decode($rekening->detail,true)['jangka_waktu'],
                    'id_UsrTDP' => $usr->id.".".$id,
                    'id_TDP'=>  $request->deposito_,
                    'id_pengajuan' =>$id_pengajuan,
                    'id_pencairan' => $request->rek_tabungan,
                    'jenis_TDP' => $rekening->nama_rekening,
                    'id_user' => $usr->id,
                    'status' =>"Setoran Awal",
                ];
                if($this->informationRepository->setoranAwal($detail_pdeposito,$data)){
//                    teller
                    $dt['id']=$detail_pdeposito['untuk_rekening'];
                    $dt['jumlah']=$detail_pdeposito['jumlah'];
//                    $this->informationRepository->updateSaldoRekening($dt);
//                    deposito
                    $dt['id']=$rekening['id'];
                    $dt['jumlah']=$detail_pdeposito['jumlah'];
//                    $this->informationRepository->updateSaldoRekening($dt);
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf('Pembukaan Rekening Deposito berhasil dilakukan!.'));
                }
                else{
                    if($this->informationRepository->delPengajuan($id_pengajuan))
                        return redirect()
                            ->back()
                            ->withInput()->with('message', 'Pembukaan Rekening Deposito gagal dilakukan!.');
                }
            }
            elseif($request->pembiayaan !=null){
                $bmt = $this->informationRepository->getRekeningBMT($request->bank);
                if( (floatval($bmt['saldo']) < ( floatval( $request->jumlah)  )) )
                    return redirect()
                        ->back()
                        ->withInput()->with('message', 'Saldo '.$this->informationRepository->getRekeningBMT($request->bank)->nama." tidak cukup!");
                $id_pengajuan =$this->daftar_pengajuan_baru($request,$usr);
                $pengajuan =$this->informationRepository->getPengajuan($id_pengajuan);
                $tab = $this->pembiayaan->where('id_user', $usr->id)->orderBy('id','DESC')->get();

                $rekening =$this->informationRepository->getRekeningByid($request->pembiayaan);
                $ditangguhkan =$this->informationRepository->getRekening(json_decode($rekening['detail'],true)['m_ditangguhkan']);
                if(count($tab)==0)$id=1;
                else $id=str_after($tab[0]['id_pembiayaan'], '.')+1;
                $lama_angsuran =1;
                if( str_after( json_decode($pengajuan->detail,true)['keterangan']," ") == "Tahun"){
                    $lama_angsuran = str_before( json_decode($pengajuan->detail,true)['keterangan']," ") *12;
                }
                elseif( str_after( json_decode($pengajuan->detail,true)['keterangan']," ") == "Bulan"){
                    $lama_angsuran = str_before( json_decode($pengajuan->detail,true)['keterangan']," ");
                }
                $margin_total = (floatval(json_decode($pengajuan->detail,true)['jumlah'] )* floatval($request->nisbah)/100)*floatval($lama_angsuran);
                $pinjaman_total = floatval(json_decode($pengajuan->detail,true)['jumlah'] )+ $margin_total;
                $angsuran_pokok = floatval($pinjaman_total/$lama_angsuran );
                $detail_ppembiayaan=[
                    'teller'         => Auth::user()->id,
                    'dari_rekening'  => $request->bank,
                    'untuk_rekening' => "Tunai",
                    'angsuran_pokok' => $angsuran_pokok ,
                    'angsuran_ke'    => 0,
                    'nisbah'         => floatval($request->nisbah)/100,
                    'margin'         => $margin_total,
                    'jumlah'         => floatval(json_decode($pengajuan->detail,true)['jumlah']),
                    'tagihan'        => $angsuran_pokok ,
                    'sisa_angsuran'    => floatval(json_decode($pengajuan->detail,true)['jumlah']),
                    'sisa_margin'    => $margin_total,
                    'sisa_pinjaman'  => $pinjaman_total,
                ];
                $data=[
                    'detail_rekening'   =>  $rekening->detail,
                    'lama_angsuran'     =>  $lama_angsuran,
                    'tipe'              =>  $pengajuan->kategori,
                    'id_UsrTDP'         =>  $usr->id.".".$id,
                    'id_TDP'            =>  $pengajuan->id_rekening,
                    'id_pengajuan'      =>  $pengajuan->id,
                    'tempo'             =>  date(now()),
                    'margin'             =>  $margin_total,
                    'jumlah'             =>  floatval(json_decode($pengajuan->detail,true)['jumlah']),
                    'nisbah'         => floatval($request->nisbah)/100,
                    'jenis_TDP'         =>  json_decode($pengajuan['detail'],true)['nama_rekening'],
                    'id_user'           =>  $usr->id,
                    'status'            =>  "Pencairan Pembiayaan",
                    'saksi1'            =>  $request['saksi1'],
                    'saksi2'            =>  $request['saksi2'],
                    'alamat2'           =>  $request['alamat2'],
                    'ktp2'              =>  $request['ktp2']
                ];
                if($this->informationRepository->setoranAwal($detail_ppembiayaan,$data)){
                    //                    teller /kas
                    $dt['id']=$detail_ppembiayaan['dari_rekening'];
                    $dt['jumlah']=-$detail_ppembiayaan['jumlah'];
//                    $this->informationRepository->updateSaldoRekening($dt);
//                    ditangguhkan (untuk tipe jual beli nisbah belom fix)
                    if($ditangguhkan['id']!=null){
                        $dt['id']=$ditangguhkan['id'];
                        $dt['jumlah']=-floatval($detail_ppembiayaan['margin']);
//                        $this->informationRepository->updateSaldoRekening($dt);
                    }
//                    pembiayaan (untuk tipe jual beli nisbah belom fix)
                    $dt['id']=$rekening['id'];
                    $dt['jumlah']=floatval($detail_ppembiayaan['jumlah'])+floatval($detail_ppembiayaan['margin']);
//                    $this->informationRepository->updateSaldoRekening($dt);
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf('Pembukaan Rekening Pembiayaan berhasil dilakukan!.'));
                }
                else{
                    if($this->informationRepository->delPengajuan($id_pengajuan))
                        return redirect()
                            ->back()
                            ->withInput()->with('message', 'Pembukaan Rekening Pembiayaan gagal dilakukan!.');
                }
            }

        }

    //   VIA TRANSAKSI PENGAJUAN
        if($request->pembiayaan){
            $detail['nisbah']=$request->nisbah;
            $detail['bank']=$request->bank;
            $detail['saksi1']=$request->saksi1;
            $detail['saksi2']=$request->saksi2;
            $detail['alamat2']=$request->alamat2;
            $detail['ktp2']=$request->ktp2;
            $bmt = $this->informationRepository->getRekeningBMT($request->bank);
            $pengajuan = $this->informationRepository->getPengajuan($request->id_);
            if( (floatval($bmt->saldo) < ( floatval( json_decode($pengajuan->detail,true)['jumlah'])  )) )
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Saldo '.$this->informationRepository->getRekeningBMT($request->bank)->nama." tidak cukup!");
        }
        if(isset($request->pokok)){
            if(preg_match("/^[0-9,]+$/", $request->pokok)) $request->pokok = str_replace(',',"",$request->pokok);
            if(preg_match("/^[0-9,]+$/", $request->wajib)) $request->wajib = str_replace(',',"",$request->wajib);
            $detail['pokok']=$request->pokok;
            $detail['wajib']=$request->wajib;
        }

        if($request->syarat=="ya" && $request->identitas=="ya")
            if($this->informationRepository->pengajuanActive($detail))
                return redirect()
                    ->back()
                    ->withSuccess(sprintf('Aktivasi Pengajuan berhasil dilakukan!.'));
            else
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Aktivasi Pengajuan gagal dilakukan!.');

        else
            return redirect()
                ->back()
                ->withInput()->with('message', 'Aktivasi Pengajuan gagal dilakukan!.');

    }
    public function daftar_debit_kredit($request){
        if($request->idcKre=="CK") $idtab = $this->informationRepository->getTabById($request->id_);
        else $idtab = $request->idRek;
        $usr_tab = $this->tabungan->where('id', $idtab)->first();
        $usr = $this->user->where('id', $usr_tab['id_user'])->first();
        $request->id_user = $usr['id'];
        $request->idtab = $idtab;
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);

        if ($this->informationRepository->cekRekStatusTab($idtab)) ;
        else {
            $rek = "Mohon maaf Rekening Tabungan Anda tidak AKTIF!.";
            return redirect()
                ->back()
                ->withInput()->with('message', $rek);
        }
        if ($request->debit == 1 || $request->kredit == 1) {
            $jenis = "Transfer";
            $bank = $request->bank;
        }
        else {
            $jenis = "Tunai";
            $bank = null;
        }
        $detail = [
            'id' => $usr['id'],
            'id_tabungan' => $idtab,
            'nama' => $usr['nama'],
            'bank' => $bank,
            'no_bank' => $request->nobank,
            'daribank' => $request->daribank,
            'atasnama' => $request->atasnama,
            'jumlah' => $request->jumlah,
        ];
        if($request->idcKre=="CK"){
            $detail['id_tabungan'] = $request->id_;
            $detail['debit'] = $jenis;
            $status = "Debit";
        }
        else{
            $detail['kredit'] = $jenis;
            $status = "Kredit";
        }
        $keterangan = [
            'jenis' => $status." Tabungan [" . $jenis . "]",
            'status' => "Menunggu Konfirmasi",
        ];
        $data = [
            "detail" => $detail,
            "keterangan" => $keterangan,
        ];

        if($request->idcKre=="CK")
            $request->id= $this->informationRepository->TransaksiTabUsrKre($data, $request);
        else  $request->id= $this->informationRepository->TransaksiTabUsrDeb($data, $request);
        if($request->teller=="teller"){
            $detail['id_tabungan'] = $request->id_;
        }
        return $request;
    }

    public function daftar_angsuran($request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        if(preg_match("/^[0-9,]+$/", $request->jumlah_)) $request->jumlah_ = str_replace(',',"",$request->jumlah_);
        if(preg_match("/^[0-9,]+$/", $request->bayar_mar)) $request->bayar_mar = str_replace(',',"",$request->bayar_mar);
        if(preg_match("/^[0-9,]+$/", $request->bayar_ang)) $request->bayar_ang = str_replace(',',"",$request->bayar_ang);
        if(preg_match("/^[0-9,]+$/", $request->nisbah)) $request->nisbah = str_replace(',',"",$request->nisbah);
        if($request->jumlah == null){
            if($request->jenis_==2){
                $jumlah = $request->jumlah;
                $wajib = $request->jumlah;
            }
            else{
                $jumlah = $request->jumlah_;
                $wajib = $request->jumlah_;
            }
        }
        else {
            $wajib =$request->pokok_;
            $jumlah = $request->jumlah;
        }

        $usr_pem = $this->pembiayaan->where('id_pembiayaan',$request->id_)->first();
        $usr = $this->user->where('id',$usr_pem['id_user'])->first();
        if ($request->debit == 1) {
            $kredit = "Transfer";
            $atasnama = $request->atasnama;
        } else {
            $kredit = "Tunai";
            $atasnama = $usr['nama'];
        }

        $detail = [
            'angsuran' => $kredit,
            'id_pembiayaan' => $request->id_,
            'id' => $usr['id'],
            'nama' => $usr['nama'],
            'bank_user' => $request->daribank,
            'no_bank' => $request->nobank,
            'atasnama' => $atasnama,
            'bank' => $request->bank,
            'pokok' => $request->pokok_,
            'tipe_pembayaran' => $request->tipe_,
            'sisa_ang' => floatval($request->sisa_ang),
            'sisa_mar' => floatval($request->sisa_mar),
            'bayar_ang' => floatval($request->bayar_ang),
            'bayar_mar' => floatval($request->bayar_mar),
            'jumlah' => floatval($request->bayar_mar) + floatval($request->bayar_ang),
            'nisbah' => $request->nisbah,
            'jenis' => $request->jenis_,
        ];
        $keterangan = [
            'jenis' => "Angsuran Pembiayaan [" . $kredit . "]",
            'status' => "Menunggu Konfirmasi",
        ];
        $data = [
            "detail" => $detail,
            "keterangan" => $keterangan,
        ];
        return $this->informationRepository->TransaksiPemUsrAng($data, $request);
    }
    public function konfirmasi_angsur(Request $request){
        if($request->teller=="teller"){
            $this->validate($request, [
                'file' => 'file|max:2000', // max 2MB
            ]);
//            $detail_usr=json_decode($this->informationRepository->getPemUsrId($request->id_)['detail'],true);
//            $detail_usr=($this->informationRepository->getPemUsrId($request->id_));
//            if($detail_usr['tagihan_bulanan'] < $detail_usr['angsuran_pokok']){
//                $msg="";
//                if(number_format($detail_usr['tagihan_bulanan'])==number_format($detail_usr['angsuran_pokok']-($detail_usr['margin']/$detail_usr['lama_angsuran'])) )
//                    if($request->tipe_==1) goto end;
//                    else $msg ="Angsuran";
//                elseif (number_format($detail_usr['tagihan_bulanan'])==number_format($detail_usr['margin']/$detail_usr['lama_angsuran']))
//                    if($request->tipe_==0) goto end;
//                    else $msg="Margin";
//                else goto end;
//                return redirect()
//                    ->back()
//                    ->withInput()->with('message', 'Silahkan melunasi tagihan ' .$msg.' terlebih dahulu!.');
//            }
//            end:
            $id_pengajuan = $this->daftar_angsuran($request);
            $request->idtab =$request->id_;
            $request->id_ = $id_pengajuan;
        }
        if($this->informationRepository->penyimpananAngsuran($request)){
            return redirect()
                ->back()
                ->withSuccess(sprintf('Konfirmasi Pembayaran berhasil dilakukan!.'));
        }
        else{
            if($request->teller=="teller"){
                $this->informationRepository->delPengajuan($id_pengajuan);
            }
            return redirect()
                ->back()
                ->withInput()->with('message', 'Konfirmasi Pembayaran gagal dilakukan!.');
        }
    }
    public function konfirmasi_pencairan(Request $request){
        $konfirmasi = $this->depositoReporsitory->pencairanDeposito($request);
        // return response()->json($request);
        
        $bmt = $this->informationRepository->getRekeningBMT($request->dari);
        if( floatval($bmt['saldo']) <  floatval(str_replace(',', '', $request->saldo)) )
            return redirect()
                ->back()
                ->withInput()->with('message', 'Saldo '.$this->informationRepository->getRekeningBMT($request->dari)->nama." tidak cukup!");
        if($request->teller=="teller"){
            $status = $this->deposito->where('id_deposito',$request->id_)->first();
            if($status['status']!="active"){
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Pengajuan gagal dilakukan Rekening Deposito '.$request->id_." ".$status['jenis_deposito'].' Tidak Aktif!.');
            }
            $id_pengajuan = $this->informationRepository->withdrawDeposito($request);
            $request->id = $id_pengajuan;
            $request->id_user = $status['id_user'];
        }

        if($this->informationRepository->pencairanDeposito($request)){
            return redirect()
                ->back()
                ->withSuccess(sprintf('Transaksi Pencairan Deposito berhasil dilakukan!.'));
        }
        else{
            if($request->teller=="teller"){
                $this->informationRepository->delPengajuan($id_pengajuan);
            }
            return redirect()
                ->back()
                ->withInput()->with('message', 'Transaksi Pencairan Deposito gagal dilakukan!.');
        }

        if($konfirmasi['status'] == 'sukses'){
            return redirect()
                ->back()
                ->withSuccess(sprintf($konfirmasi['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Transaksi Pencairan Mudharabah Berjangka gagal dilakukan!.');
        }
    }

    public function un_block_rekening(Request $request){
        if($this->informationRepository->un_blockRekening($request)){
            if($request->status=="blocked")
                $msg ="Rekening User berhasil diblokir!";
            else
                $msg ="Rekening User berhasil diaktivasi!";
            return redirect()
                ->back()
                ->withSuccess(sprintf($msg));
        }
        else{
            if($request->status=="blocked")
                $msg ="Rekening User gagal diblokir!";
            else
                $msg ="Rekening User gagal diaktivasi!";
            return redirect()
                ->back()
                ->withInput()->with('message', $msg);
        }
    }

    public function konfirmasi_tutup(Request $request){

        if($request->jenis==1){
            $bmt = $this->informationRepository->getRekeningBMT($request->bank);
            if( (floatval($bmt['saldo']) < ( floatval( $request->jumlah)  )) )
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Saldo '.$this->informationRepository->getRekeningBMT($request->bank)->nama." tidak cukup!");

        }

        if($this->informationRepository->tutupTabungan($request)){
            return redirect()
                ->back()
                ->withSuccess(sprintf('Penutupan Tabungan berhasil dilakukan!.'));
        }
        else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Konfirmasi Pembayaran gagal dilakukan!.');
        }
    }
//end of TRANSAKSI

    public function transaksi_tab(){
        return view('admin.transaksi.tabungan',[
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $this->informationRepository->getAllTransaksiTab(),
            'data2' => $this->informationRepository->getAllpengajuanUsrTab(),
            'tab' =>  $this->informationRepository->getAllTab(),
            'dropdown' => $this->informationRepository->getDdTab(),
            'dropdown2' => $this->informationRepository->getDdDep(),
            'dropdown3' => $this->informationRepository->getDdPem(),
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
        ]);
    }
    public function detail_pengajuan(Request $request){
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuan();
        return view('admin.transaksi.pengajuan',[
            'data' => $data,
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown2,
            'dropdown3' => $dropdown3,
        ]);
    }


    //NAVBAR DAFTAR NASABAH TABUNGAN
    //    TABUNGAN [UNACTIVE]
    public function pengajuan_tabungan(){
        $home = new HomeController;
        $date = $home->date_query(0);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanTabTell($date);

        return view('teller.transaksi.tabungan.pengajuan',[
            'bank_bmt' => $this->tabunganReporsitory->getRekening('BANK'),
            'datasaldo' =>  $this->informationRepository->getAllTab(),
            'data' => $data,
            'kegiatan' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown2,
            'dropdown3' => $dropdown3,
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()

        ]);
    }
    public function periode_tab(Request $request){
        $home = new HomeController;
        $date = $home->date_query($request->periode);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanTabTell($date);
        return view('admin.tabungan.pengajuan',[
            'datasaldo' =>  $this->informationRepository->getTabUsr(),
            'kegiatan' => $dropdown,
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown2,
            'dropdown3' => $dropdown3,
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()
        ]);
    }
    public function nasabah_tabungan(){
        $dropdown = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllTab();
        if(Auth::user()->tipe=="admin")
            return view('admin.deposito.nasabah',[
                'kegiatan' => $dropdown,
                'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
                'tab' =>  $this->informationRepository->getAllTab(),
                'tabactive' =>  $this->informationRepository->getAllTabActive(),
                'data' => $data,
                'dropdown' => $dropdown,
                'dropdown2' => $dropdown,
                'dropdown3' => $dropdown,
                'dropdown6' => $this->informationRepository->getDdBank(),
                'dropdown7' => $this->informationRepository->getDdTeller(),
                'dropdown8' => $this->informationRepository->getAllNasabah(),
                'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            ]);
        elseif(Auth::user()->tipe=="teller")
            return view('teller.nasabah.nasabah_tabungan',[
                'kegiatan' => $dropdown,
                'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
                'tab' =>  $this->informationRepository->getAllTab(),
                'tabactive' =>  $this->informationRepository->getAllTabActive(),
                'data' => $data,
                'dropdown' => $dropdown,
                'dropdown2' => $dropdown,
                'dropdown3' => $dropdown,
                'dropdown6' => $this->informationRepository->getDdBank(),
                'dropdown7' => $this->informationRepository->getDdTeller(),
                'dropdown8' => $this->informationRepository->getAllNasabah(),
                'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            ]);

    }
    public function simpanan_wajib(Request $request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        if ($request->jenis == 1 ) {
            $jenis = "Transfer";
            $bank = $request->bank;
        }
        else {
            $jenis = "Tunai";
            $bank = null;
        }
        $detail = [
            'id' => $request->id_,
            'jenis' => $request->jenis,
            'nama' => $request->nama,
            'bank' => $bank,
            'no_bank' => $request->nobank,
            'daribank' => $request->daribank,
            'atasnama' => $request->atasnama,
            'jumlah' => $request->jumlah,
        ];

        $data['status'] ="Disetujui";
        $data['detail'] =$detail;
        $data['jenis'] =$jenis;

        if($request->teller=="teller"){
            $this->validate($request, [
                'file' => 'file|max:2000', // max 2MB
            ]);
            $id_pengajuan = $this->informationRepository->pengajuanWajib($data,$request);
        }

        if($this->informationRepository->penyimpananWajib($request,$id_pengajuan)){
            return redirect()
                ->back()
                ->withSuccess(sprintf('Konfirmasi Pembayaran berhasil dilakukan!.'));
        }
        else{
            if($request->teller=="teller") {
                if($this->informationRepository->delPengajuan($id_pengajuan));
            }
            return redirect()
                ->back()
                ->withInput()->with('message', 'Konfirmasi Pembayaran gagal dilakukan!.');
        }
    }
//end of NAVBAR TABUNGAN

    //NAVBAR DAFTAR NASABAH DEPOSITO
    public function pengajuan_deposito(){
        $home = new HomeController;
        $date = $home->date_query(0);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanDepTell($date);
        $dataInDate = $this->informationRepository->getAllDepUsrActiveInDate();

        $depositoExpiredNotAutoExtended = array();
        foreach ($dataInDate as $value) {
            if(json_decode($value->detail)->perpanjangan_otomatis == true)
            {
                array_push($depositoExpiredNotAutoExtended, $value);
            }
        }

        return view('teller.transaksi.deposito.pengajuan',[
            // 'datasaldoDep' =>  $this->informationRepository->getAllDep(),
            'datasaldoDepInDate' => $depositoExpiredNotAutoExtended,
            'bank_bmt'  => $this->tabunganReporsitory->getRekening('BANK'),
            'datasaldoDep' =>  $this->depositoReporsitory->getDeposito($status='active'),
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown2,
            'dropdown3' => $dropdown3,
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()
        ]);
    }
    public function periode_dep(Request $request){
        $home = new HomeController;
        $date = $home->date_query($request->periode);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanDepTell($date);
        return view('admin.deposito.pengajuan',[
            'datasaldoDep' =>  $this->informationRepository->getAllDepUsr(),
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown2,
            'dropdown3' => $dropdown3,
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()
        ]);
    }
    public function nasabah_deposito(){
        $dropdown = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllDep();
        if(Auth::user()->tipe=="admin")
            return view('admin.deposito.nasabah',[
                'kegiatan' => $dropdown,
                'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
                'tab' =>  $this->informationRepository->getAllTab(),
                'tabactive' =>  $this->informationRepository->getAllTabActive(),
                'data' => $data,
                'dropdown' => $dropdown,
                'dropdown2' => $dropdown,
                'dropdown3' => $dropdown,
                'dropdown6' => $this->informationRepository->getDdBank(),
                'dropdown7' => $this->informationRepository->getDdTeller(),
                'dropdown8' => $this->informationRepository->getAllNasabah(),
                'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            ]);
        elseif(Auth::user()->tipe=="teller")
            return view('teller.nasabah.nasabah_deposito',[
                'kegiatan' => $dropdown,
                'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
                'tab' =>  $this->informationRepository->getAllTab(),
                'tabactive' =>  $this->informationRepository->getAllTabActive(),
                'data' => $data,
                'dropdown' => $dropdown,
                'dropdown2' => $dropdown,
                'dropdown3' => $dropdown,
                'dropdown6' => $this->informationRepository->getDdBank(),
                'dropdown7' => $this->informationRepository->getDdTeller(),
                'dropdown8' => $this->informationRepository->getAllNasabah(),
                'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            ]);
    }
//end of NAVBAR DEPOSITO

    //NAVBAR DAFTAR NASABAH PEMBIAYAAN
    public function pengajuan_pembiayaan(){
        $home = new HomeController;
        $date = $home->date_query(0);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanPemTell($date);
        return view('teller.transaksi.pembiayaan.pengajuan',[
            'bank_bmt' => $this->tabunganReporsitory->getRekening('BANK'),
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown2,
            'dropdown3' => $dropdown3,
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller($id_rekening=json_decode(Auth::user()->detail)->id_rekening),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode(),
            'tabungan'  => $this->informationRepository->getAllTab()
        ]);
    }
    public function periode_pem(Request $request){
        $home = new HomeController;
        $date = $home->date_query($request->periode);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanPemTell($date);
        return view('admin.pembiayaan.pengajuan',[
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown2,
            'dropdown3' => $dropdown3,
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()
        ]);
    }
    public function nasabah_pembiayaan(){
        $dropdown = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllPemNasabah();
        if(Auth::user()->tipe=="teller")
            return view('teller.nasabah.nasabah_pembiayaan',[
                'kegiatan' => $dropdown,
                'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
                'tab' =>  $this->informationRepository->getAllTab(),
                'tabactive' =>  $this->informationRepository->getAllTabActive(),
                'data' => $data,
                'dropdown' => $dropdown,
                'dropdown2' => $dropdown,
                'dropdown3' => $dropdown,
                'dropdown6' => $this->informationRepository->getDdBank(),
                'dropdown7' => $this->informationRepository->getDdTeller(),
                'dropdown8' => $this->informationRepository->getAllNasabah(),
                'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            ]);
        elseif(Auth::user()->tipe=="admin")
            return view('admin.pembiayaan.nasabah',[
                'kegiatan' => $dropdown,
                'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
                'tab' =>  $this->informationRepository->getAllTab(),
                'tabactive' =>  $this->informationRepository->getAllTabActive(),
                'data' => $data,
                'dropdown' => $dropdown,
                'dropdown2' => $dropdown,
                'dropdown3' => $dropdown,
                'dropdown6' => $this->informationRepository->getDdBank(),
                'dropdown7' => $this->informationRepository->getDdTeller(),
                'dropdown8' => $this->informationRepository->getAllNasabah(),
                'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            ]);
    }
//end of NAVBAR PEMBIAYAAN
    public function akad_pembiayaan($id)
    {
        $pengajuan =Pengajuan::where('id',$id)->first();
        $pihak2 =User::select('nama','alamat','no_ktp','detail')->where('id',$pengajuan['id_user'])->first();
        $jaminan =PenyimpananJaminan::where('id_pengajuan',$id)
            ->join('jaminan','jaminan.id','penyimpanan_jaminan.id_jaminan')->first();
        $pembiayaan =Pembiayaan::where('id_pengajuan',$jaminan['id_pengajuan'])->first();
        $a =  json_decode($jaminan['detail'],true);
        $b =  json_decode($jaminan['transaksi'],true)['field'];
        $c = (substr_count($jaminan['detail'],","));
        $detail_jaminan ="";
        $v=0;
        for ($i=0;$i<=$c;$i++) {
            $spasi="";
            $j= 25 - strlen($a[$i])-$v;
            for ($k=1;$k<$j;$k++) $spasi=$spasi." ";
            $detail_jaminan = $detail_jaminan."<w:br />".$a[$i].$spasi.": ".$b[$a[$i]];
            $v=$v+1;
        }
        $tgl_realisasi = $pembiayaan['created_at'];
        $bulan = array (1 =>   'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $hari = array (0 =>   'Minggu','Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum at', 'Sabtu');
        $tgl_lunas=(date_format($tgl_realisasi,"Y-m-d"));
        $hari2=(date_format($tgl_realisasi,"w"));
        $hari2=($hari[ (int)$hari2 ]);

        $tgl_realisasi=(date_format($tgl_realisasi,"d m Y"));
        $pecahkan = explode(' ', $tgl_realisasi);
        $bln=$bulan[ (int)$pecahkan[1] ];
        $tgl_realisasi=$pecahkan[0]." ".$bln." ".$pecahkan[2];

        $lama = json_decode($pembiayaan['detail'],true)['lama_angsuran']." months";
        $tgl_lunas = date('Y-m-d', strtotime($tgl_lunas. $lama));
        $pecahkan = explode('-', $tgl_lunas);
        $bln=$bulan[ (int)$pecahkan[1] ];
        $tgl_lunas=$pecahkan[2]." ".$bln." ".$pecahkan[0];

        $tgl=date('Y-m-d');
        $pecahkan = explode('-', $tgl);
        $bln=$bulan[ (int)$pecahkan[1] ];
        $tgl=$pecahkan[2]." ".$bln." ".$pecahkan[0];

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        // Edit variabel in word document
        $template = new \PhpOffice\PhpWord\TemplateProcessor('storage/formakad/template1.docx');
        $template->setValue('pihak1','M. SYAMSUL ARIFIN SHOLEH');
        $template->setValue('pihak2',$pihak2['nama']);
        $template->setValue('pihak2_',strtoupper($pihak2['nama']));
        $template->setValue('alamatpihak2',$pihak2['alamat']);
        $template->setValue('ktppihak2',$pihak2['no_ktp']);
        $template->setValue('pekerjaanpihak2',json_decode($pihak2['detail'],true)['pekerjaan']);
//        $template->setValue('saksi1',json_decode($jaminan['transaksi'],true)['jaminan']['saksi1']);
        $template->setValue('saksi1_',strtoupper(json_decode($jaminan['transaksi'],true)['jaminan']['saksi1']));
        $template->setValue('saksi2',json_decode($jaminan['transaksi'],true)['jaminan']['saksi2']);
        $template->setValue('saksi2_',strtoupper(json_decode($jaminan['transaksi'],true)['jaminan']['saksi2']));
        $template->setValue('alamatsaksi2',json_decode($jaminan['transaksi'],true)['jaminan']['alamat2']);
        $template->setValue('ktpsaksi2',json_decode($jaminan['transaksi'],true)['jaminan']['ktp2']);

        $template->setValue('jaminan',json_decode($pengajuan['detail'],true)['jaminan']);
        $template->setValue('total_pinjaman',number_format(json_decode($pembiayaan['detail'],true)['total_pinjaman']));
        $template->setValue('pinjaman',number_format(json_decode($pembiayaan['detail'],true)['pinjaman']));
        $template->setValue('margin',number_format(json_decode($pembiayaan['detail'],true)['margin']));

        $template->setValue('lama_pinjaman',json_decode($pembiayaan['detail'],true)['lama_angsuran']);
        $template->setValue('jatuh_tempo',json_decode($pembiayaan['detail'],true)['margin']);
        $template->setValue('pinjaman_bln',number_format(floatval(json_decode($pembiayaan['detail'],true)['pinjaman']) / floatval(json_decode($pembiayaan['detail'],true)['lama_angsuran'])));
        $template->setValue('margin_bln',number_format(floatval(json_decode($pembiayaan['detail'],true)['margin']) / floatval(json_decode($pembiayaan['detail'],true)['lama_angsuran'])));
        $template->setValue('coba', 'John  123 fake street');
        $template->setValue('detail_jaminan',$detail_jaminan);

        $template->setValue('kota',"Surabaya");
        $template->setValue('tgl_realisasi',$tgl_realisasi);
        $template->setValue('tgl_lunas',$tgl_lunas);
        $template->setValue('tgl',$tgl  );
        $template->setValue('hari',$hari2  );

        $filename ='storage/formakad/result_template1.docx';
        $template->saveAs($filename);
        header('Content-disposition: inline');
        header('Content-type: application/msword'); // not sure if this is the correct MIME type
        readfile($filename);
        $headers = array(
            'Content-Type: application/docx',
        );

        return response()->download($filename, "akad_pembiayaan - " .$pihak2['nama'].".docx", $headers);

    }
    public function akad_deposito($id)
    {
        $pengajuan =Pengajuan::where('id',$id)->first();
        $pihak2 =User::select('nama','alamat','no_ktp','detail')->where('id',$pengajuan['id_user'])->first();
        $deposito =Deposito::where('id_pengajuan',$id)->first();
        $tabungan =Tabungan::where('id',json_decode($deposito['detail'],true)['id_pencairan'])->first();
        $tempo = json_decode(Rekening::where('id',$deposito['id_rekening'])->first()['detail'],true)['jangka_waktu'];
        $id_teller =json_decode(PenyimpananDeposito::where('id_deposito',$deposito['id'])->first()['transaksi'],true)['teller'];
        $teller = User::where('id',$id_teller)->first()['nama'];
        $tgl_realisasi = $deposito['created_at'];
        $bulan = array (1 =>   'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $hari = array (0 =>   'Minggu','Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum at', 'Sabtu');
        $tgl_tempo=(date_format($tgl_realisasi,"Y-m-d"));
        $hari2=(date_format($tgl_realisasi,"w"));
        $hari2=($hari[ (int)$hari2 ]);

        $tgl_realisasi=(date_format($tgl_realisasi,"d m Y"));
        $pecahkan = explode(' ', $tgl_realisasi);
        $bln=$bulan[ (int)$pecahkan[1] ];
        $tgl_realisasi=$pecahkan[0]." ".$bln." ".$pecahkan[2];

        $lama = $tempo." months";
        $tgl_tempo = date('Y-m-d', strtotime($tgl_tempo. $lama));
        $pecahkan = explode('-', $tgl_tempo);
        $bln=$bulan[ (int)$pecahkan[1] ];
        $tgl_tempo=$pecahkan[2]." ".$bln." ".$pecahkan[0];
        $tgl=date('Y-m-d');
        $pecahkan = explode('-', $tgl);
        $bln=$bulan[ (int)$pecahkan[1] ];
        $tgl=$pecahkan[2]." ".$bln." ".$pecahkan[0];

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
//        $textlines = explode("\n", $detail_jaminan);
//        $section = $phpWord->addSection();
//        $textrun = $section->addTextRun();
//        $textrun->addText(array_shift($textlines));
//        foreach($textlines as $line) {
//            $textrun->addTextBreak();
        // maybe twice if you want to seperate the text
        // $textrun->addTextBreak(2);
//            $textrun->addText($line);
//        }
//        $section = $phpWord->addSection();
//        $textlines = explode("\n", $detail_jaminan);
//        foreach($textlines as $line) {
//            $section->addText(htmlspecialchars($line));
//        }

        // Edit variabel in word document
        $template = new \PhpOffice\PhpWord\TemplateProcessor('storage/formakad/template_dep.docx');
        $template->setValue('nama',strtoupper($pihak2['nama']));
        $template->setValue('teller',$teller);
        $template->setValue('alamat',$pihak2['alamat']);
        $template->setValue('ktp',$pihak2['no_ktp']);
        $template->setValue('rek_tab',$tabungan['id_tabungan']);
        $template->setValue('rek_dep',$deposito['id_deposito']);
        $template->setValue('manager','M. SYAMSUL ARIFIN SHOLEH');
        $template->setValue('kota',"Surabaya");
        $template->setValue('tgl_realisasi',$tgl_realisasi);
        $template->setValue('tempo',$tgl_tempo);
        $template->setValue('tgl',$tgl_realisasi  );
        $template->setValue('bln',$tempo);
        $template->setValue('jumlah',number_format(json_decode($deposito['detail'],true)['saldo']));
        $template->setValue('spell',number_format(json_decode($deposito['detail'],true)['saldo']));

        $filename ='storage/formakad/result_template_dep.docx';
        $template->saveAs($filename);
        header('Content-disposition: inline');
        header('Content-type: application/msword'); // not sure if this is the correct MIME type
        readfile($filename);
        $headers = array(
            'Content-Type: application/docx',
        );

        return response()->download($filename, "akad_deposito - " .$pihak2['nama'].".docx", $headers);
    }

    
    /**
     * Pengajuan maal controller
     * @return View 
    */
    public function pengajuan_maal(){
        $home = new HomeController;
        $date = $home->date_query(0);

        $dataInDate = $this->informationRepository->getAllDepUsrActiveInDate();
        $data = $this->informationRepository->getAllDepUsrActive();
        $depositoExpiredNotAutoExtended = array();
        foreach ($dataInDate as $value) {
            if(json_decode($value->detail)->perpanjangan_otomatis == true)
            {
                array_push($depositoExpiredNotAutoExtended, $value);
            }
        }
        // return response()->json();
        return view('teller.transaksi.maal.pengajuan',[
            'datasaldoDepInDate' => $depositoExpiredNotAutoExtended,
            'bank_bmt' => $this->tabunganReporsitory->getRekening('BANK'),
            'anggota'  => $this->accountReporsitory->getAccount('anggota'),
            'tabungan' => $this->tabunganReporsitory->getTabungan(),
            'pengajuanKegiatan' => $this->donasiReporsitory->getPengajuanDonasi($type="donasi kegiatan"),
            'pengajuanZIS' => $this->donasiReporsitory->getPengajuanDonasi($type="zis"),
            'pengajuanWakaf' => $this->donasiReporsitory->getPengajuanDonasi($type="wakaf"),
            'kegiatan' => $this->informationRepository->getAllMaal(),
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'datasaldoDep' =>  $this->informationRepository->getAllDepUsr(),
            'datasaldo' =>  $this->informationRepository->getTabUsr(),
            'data' =>  $this->informationRepository->getAllpengajuanMaal($date),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'data2' => $this->informationRepository->getAllpengajuanUsrTab(),
            'tab' =>  $this->informationRepository->getAllTab(),
            'dropdown' => $this->informationRepository->getDdTab(),
            'dropdown2' => $this->informationRepository->getDdDep(),
            'dropdown3' => $this->informationRepository->getDdPem(),
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()
        ]);
    }

    /** 
     * Pengajuan simpanan controller
     * @return View
    */
    public function pengajuan_simpanan() {
        $home = new HomeController;
        $date = $home->date_query(0);

        $dataInDate = $this->informationRepository->getAllDepUsrActiveInDate();
        $data = $this->informationRepository->getAllDepUsrActive();
        $depositoExpiredNotAutoExtended = array();
        foreach ($dataInDate as $value) {
            if(json_decode($value->detail)->perpanjangan_otomatis == true)
            {
                array_push($depositoExpiredNotAutoExtended, $value);
            }
        }
        // return response()->json($this->tabunganReporsitory->getTabungan());
        return view('teller.transaksi.simpanan.pengajuan',[
            'datasaldoDepInDate' => $depositoExpiredNotAutoExtended,
            'users'    => User::where('tipe', 'anggota')->get(),
            'tabungan' => $this->tabunganReporsitory->getTabungan(),
            'simpanan' => $this->simpananReporsitory->getUserPengajuanSimpanan(),
            'bank_bmt' => $this->tabunganReporsitory->getRekening('BANK'),
            'kegiatan' => $this->informationRepository->getAllMaal(),
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'datasaldoDep' =>  $this->informationRepository->getAllDepUsr(),
            'datasaldo' =>  $this->informationRepository->getTabUsr(),
            'data' =>  $this->informationRepository->getAllpengajuanMaal($date),
            'tabactive' =>  $this->informationRepository->getAllTabActive(),
            'data2' => $this->informationRepository->getAllpengajuanUsrTab(),
            'tab' =>  $this->informationRepository->getAllTab(),
            'dropdown' => $this->informationRepository->getDdTab(),
            'dropdown2' => $this->informationRepository->getDdDep(),
            'dropdown3' => $this->informationRepository->getDdPem(),
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode()
        ]);
    }

    /** 
     * Transfer Controller
     * @return View
    */
    public function transfer(){
        return view('teller.transaksi.transfer.index',[
            'nasabah' => count($this->informationRepository->getAllNasabah()),
            'data' => $this->informationRepository->getAllPengajuanBMT(),
            // 'dropdown' => $this->informationRepository->getDdBMT(),
            'dropdown' => $this->rekeningReporsitory->getRekeningExcludedCategory($excluded=array('kas', 'bank', 'shu berjalan'), $type="detail", $sort="id_rekening")
        ]);
    }

    /** 
     * Teller kolektibilitas controller
     * @return View
    */

    public function daftar_kolektibilitas(){
        $dropdown = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllPemNasabahKolek();
        return view('teller.laporan.daftar_kolektibilitas',[
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'tab' =>  $this->informationRepository->getAllTab(),
            'data' => $data,
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown,
            'dropdown3' => $dropdown,
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
        ]);
    }



    /** ----------------------------------------------------------------------
     * -----------------------------------------------------------------------
     * -----------------------------------------------------------------------
     * ----------------------- Teller Tabungan Menu---------------------------
     * -----------------------------------------------------------------------
     * -----------------------------------------------------------------------
    */

    /** 
     * Confirm user pengajuan tabungan
     * @return Response
    */
    public function confirm_tabungan(Request $request)
    {
        if($request->idcKre != null) {
            $confirmTabungan = $this->tabunganReporsitory->confirmCreditTabungan($request);
        } else {
            $confirmTabungan = $this->tabunganReporsitory->confirmDebitTabungan($request);
        }
        if($confirmTabungan['type'] == 'success'){
            return redirect()
                ->back()
                ->withSuccess(sprintf($confirmTabungan['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $confirmTabungan['message']);
        }
    }

    /** 
     * Kredit tabungan user
     * @return Response
    */
    public function kredit_tabungan(Request $request)
    {
        $kreditTabungan = $this->tabunganReporsitory->creditTabungan($request);
        if($kreditTabungan['type'] == 'success'){
            return redirect()
                ->back()
                ->withSuccess(sprintf($kreditTabungan['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $kreditTabungan['message']);
        }
    }

    /** 
     * Debit tabungan user
     * @return Response
    */
    public function debit_tabungan(Request $request)
    {
        $debitTabungan = $this->tabunganReporsitory->debitTabungan($request);
        if($debitTabungan['type'] == 'success'){
            return redirect()
                ->back()
                ->withSuccess(sprintf($debitTabungan['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $debitTabungan['message']);
        }
    }

    /** 
     * Confirm user pengajuan pembukaan deposito
     * @return Response
    */
    public function confirm_deposito(Request $request)
    {
        $confirmDeposito = $this->depositoReporsitory->confirmPengajuan($request);
        if($confirmDeposito['type'] == 'success'){
            return redirect()
                ->back()
                ->withSuccess(sprintf($confirmDeposito['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $confirmDeposito['message']);
        }
    }

    /** 
     * Confirm user pengajuan pencairan deposito
     * @return Response
    */
    public function confirm_pencairan_deposito(Request $request)
    {
        $pencairanDeposito = $this->depositoReporsitory->pencairanDeposito($request);
        if($pencairanDeposito['type'] == 'success'){
            return redirect()
                ->back()
                ->withSuccess(sprintf($pencairanDeposito['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $pencairanDeposito['message']);
        }
    }

    /** 
     * Open deposito from teller page
     * @return Response
    */
    public function open_deposito(Request $request)
    {
        $openDeposito = $this->depositoReporsitory->openDeposito($request);
        if($openDeposito['type'] == 'success'){
            return redirect()
                ->back()
                ->withSuccess(sprintf($openDeposito['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $openDeposito['message']);
        }
    }

    /** 
     * Withrawal deposito from teller page
     * @return Response
    */
    public function withraw_deposito(Request $request)
    {
        $pencairanDeposito = $this->depositoReporsitory->pencairanDeposito($request);
        if($pencairanDeposito['type'] == 'success'){
            return redirect()
                ->back()
                ->withSuccess(sprintf($pencairanDeposito['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $pencairanDeposito['message']);
        }
    }

    /** 
     * Pay donasi via teller page controller
     * @return Response
    */
    public function pay_donasi(Request $request)
    {
        // return response()->json($request);
        $donasi = $this->donasiReporsitory->payDonasi($request);
        if($donasi['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($donasi['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $donasi['message']);

        }
    }

    /** 
     * Konfirmasi pengajuan simpanan anggota
     * @return Response
    */
    public function confirm_simpanan(Request $request)
    {
        $pengajuan = $this->simpananReporsitory->confirmPengajuanSimpanan($request);
        // return response()->json($pengajuan);
        if($pengajuan['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($pengajuan['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $pengajuan['message']);

        }
    }

    /** 
     * Bayar simpanan anggota
     * @return Response
    */
    public function bayar_simpanan(Request $request)
    {
        $simpanan = $this->simpananReporsitory->paySimpanan($request);
        if($simpanan['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($simpanan['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $simpanan['message']);

        }
    }

    /** 
     * Konfirmasi pembiayaan pengguna
     * @return Response
    */
    public function konfirmasi_pembiayaan(Request $request)
    {
        $pengajuan = $this->pengajuanReporsitory->findPengajuan($request->id_);
        if($pengajuan->id_rekening == 100)
        {
            $pembiayaan = $this->pembiayaanReporsitory->confirmPembiayaanMRB($request);
        }
        if($pengajuan->id_rekening != 100)
        {
            $pembiayaan = $this->pembiayaanReporsitory->confirmPembiayaanLain($request);
        }
        
        if($pembiayaan['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($pembiayaan['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $pembiayaan['message']);

        }
    }

    /** 
     * Open pembiayaan pengguna
     * @return Response
    */
    public function open_pembiayaan(Request $request)
    {
        if($request->pembiayaan == 100)
        {
            $pembiayaan = $this->pembiayaanReporsitory->openPembiayaanMRB($request);
        }
        if($request->pembiayaan != 100)
        {
            $pembiayaan = $this->pembiayaanReporsitory->openPembiayaanLain($request);
        }
        if($pembiayaan['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($pembiayaan['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $pembiayaan['message']);

        }
    }

    /**
     * Konfirmasi pengajuan angsuran pembiayaan anggota
     * @return Response
     */
    public function konfirmasi_angsuran(Request $request)
    {
        $pengajuan = $this->pengajuanReporsitory->findPengajuan($request->id_);
        
        if($pengajuan->id_rekening == 100)
        {
            $angsuran = $this->pembiayaanReporsitory->confirmAngsuranMRB($request);
        }
        else
        {
            $angsuran = $this->pembiayaanReporsitory->confirmAngsuranLain($request);
        }
        if($angsuran['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($angsuran['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $angsuran['message']);

        }
    }

    /**
     * Konfirmasi angsuran pembiayaan via teller
     * @return Response
     */
    public function angsuran_pembiayaan(Request $request)
    {
        $id_rekening = explode(" ", $request->idRek)[7];
        if($id_rekening == 100)
        {
            $angsuran = $this->pembiayaanReporsitory->angsuranMRB($request);
        }
        else
        {
            $angsuran = $this->pembiayaanReporsitory->angsuranLain($request);
        }
        if($angsuran['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($angsuran['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $angsuran['message']);

        }
    }

    /** 
     * Konfirmasi pengajuan perpanjangan deposito
     * @return Response
    */
    public function konfirmasi_perpanjangan_deposito(Request $request)
    {
        $deposito = $this->depositoReporsitory->confirmPerpanjangan($request);
        if($deposito['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($deposito['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $deposito['message']);

        }
    }

    /** 
     * Perpanjangan deposito via dashboard teller
     * @return Response
    */
    public function perpanjangan_deposito(Request $request)
    {
        $deposito = $this->depositoReporsitory->perpanjanganDeposito($request);
        if($deposito['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($deposito['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $deposito['message']);
        }
    }

    /** 
     * Data tabungan anggota
     * @return Response
    */
    public function daftar_tabungan()
    {
        $tabungan = $this->tabunganReporsitory->getTabungan();

        return view('teller.nasabah.nasabah_tabungan', [
            'data'  => $tabungan
        ]);
    }

    /** 
     * Data deposito anggota
     * @return Response
    */
    public function daftar_deposito()
    {
        $deposito = $this->depositoReporsitory->getDeposito();
        return view('teller.nasabah.nasabah_deposito', [
            'data'  => $deposito
        ]);
    }

    /** 
     * Data pembiayaan anggota
     * @return Response
    */
    public function daftar_pembiayaan()
    {
        $pembiayaan = $this->pembiayaanReporsitory->getPembiayaan();
        return view('teller.nasabah.nasabah_pembiayaan', [
            'data'  => $pembiayaan
        ]);
    }

    /** 
     * Detail kas teller controller
     * @return Response
    */
    public function kas_teller()
    {
        $id_rekening_user_loged = json_decode(Auth::user()->detail)->id_rekening;
        $id_bmt_user_loged = BMT::where('id_rekening', $id_rekening_user_loged)->first();
        $data = PenyimpananBMT::where([ ['penyimpanan_bmt.id_bmt', $id_bmt_user_loged->id], ['penyimpanan_bmt.status', '!=', 'Setoran Awal'] ])
                                ->join('users', 'users.id', 'penyimpanan_bmt.id_user')
                                ->select('penyimpanan_bmt.*', 'users.nama')
                                ->get();
        // return response()->json($data);
        return view('teller.nasabah.nasabah_kas_teller', [
            'data'  => $data
        ]);
    }

    /**
     * Konfirmasi pendaftaran baru anggota
     * @return Response 
    */
    public function konfirmasi_pendaftaran_baru(Request $request)
    {
        if($request->syarat == "ya" && $request->identitas == "ya")
        {
            if($request->pokok > 0 && $request->wajib > 0)
            {
                $aktivasi_akun = $this->accountReporsitory->activasiAccount($request);
                if($aktivasi_akun['type'] == 'success') {
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf($aktivasi_akun['message']));
                }
                else{
                    return redirect()
                        ->back()
                        ->withInput()->with('message', $aktivasi_akun['message']);
                }
                    
            }
            else 
            {
                return redirect()
                        ->back()
                        ->withInput()->with('message', "Pengajuan tidak dapat dikonfirmasi, simpanan pokok & simpanan wajib tidak boleh 0.");
            }
        }
        else {
            return redirect()
                        ->back()
                        ->withInput()->with('message', "Pengajuan tidak dapat dikonfirmasi. Anda belum melengkapi syarat yang ditentukan.");
        }
    }

    /** 
     * Daftar pengajuan penutupan rekening
     * @return View
    */
    public function daftar_pengajuan_penutupan_rekening()
    {
        $pengajuan = $this->pengajuanReporsitory->getPengajuanSpecificCategory('Penutupan Rekening');

        return view("teller.transaksi.penutupan_rekening.pengajuan", [
            "data" => $pengajuan
        ]);
    }

    /** 
     * Pencairan rekening anggota
     * @return Response
    */
    public function pencairan_rekening(Request $request)
    {
        $pencairan = $this->accountReporsitory->pencairanSaldoRekening($request);
        if($pencairan['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($pencairan['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $pencairan['message']);
        }
    }

    /**
     * Konfirmasi pengajuan pelunasan pembiayaan anggota
     * @return Response
     */
    public function konfirmasi_pelunasan(Request $request)
    {
        $pengajuan = $this->pengajuanReporsitory->findPengajuan($request->id_);
        
        $angsuran = $this->pembiayaanReporsitory->confirmPelunasan($request);
        if($angsuran['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($angsuran['message']));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $angsuran['message']);

        }
    }
}
