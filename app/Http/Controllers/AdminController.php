<?php

namespace App\Http\Controllers;

use App\BMT;
use App\Deposito;
use App\Pembiayaan;
use App\Pengajuan;
use App\Repositories\InformationRepository;
use App\Tabungan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use Illuminate\Support\Facades\Hash;

use App\Repositories\RekeningReporsitories;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $id_role;

    public function __construct(Rekening $rekening,
                                User $user,
                                Tabungan $tabungan,
                                Pembiayaan $pembiayaan,
                                Deposito $deposito,
                                Pengajuan $pengajuan,
                                BMT $bmt,
                                InformationRepository $informationRepository,
                                RekeningReporsitories $rekeningReporsitory
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
        $this->bmt = $bmt;
        $this->informationRepository = $informationRepository;
        $this->rekeningReporsitory = $rekeningReporsitory;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        $nas = $this->informationRepository->getAllPemJur();
        $total =0;
        foreach ($nas as $n){
            $total += json_decode($n->detail,true)['saldo'] + json_decode($n->detail_pem,true)['tagihan_bulanan'] + 100000;

        }

//          UNTUK LIMIT HARIAN
        $home = new HomeController;
        $date = $home->date_query(0);
        $pengajuan =$this->pengajuan->select('status')
            ->where('pengajuan.created_at', ">" , $date['prev'])
            ->where('pengajuan.created_at', "<" , $date['now'])
            ->get();
        $set=$tol=$pen=0;
        foreach ($pengajuan as $p){
            if($p->status=="Menunggu Konfirmasi")$pen +=1;
            elseif(str_before($p->status," ")=="Ditolak")$tol +=1;
            elseif($p->status=="Disetujui" || str_before($p->status," ")=="Disetujui"  || str_before($p->status," ")=="Sudah" ||str_before($p->status," ")=="[Disetujui" )$set +=1;
        }
        
        return view('admin.dashboard',[
            'users'     => $this->informationRepository->getAllTeller(),
            'nas'       => count($nas),
            'tot'       => $total,
            'setuju'    => $set,
            'tolak'     => $tol,
            'pending'   => $pen,
        ]);
    }
    public function profile(){
        $data = $this->informationRepository->getAnggota(Auth::user()->no_ktp);
        return view('admin.profile',[
            'data' => $data,
        ]);
    }

    //    DATAMASTER
    public function edit_pass(Request $request) {

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $old = $request->password_old;
        $new = $request->password;
        if (Hash::check($old, Auth::user()->password)) {
            $pass = Hash::make($new);
            User::where('no_ktp', Auth::user()->no_ktp)->update(['password'=> $pass]);
            $status = ["success" ,"Password Berhasil Diubah"];
        }
        else {
            $status = ["danger", "Password Salah"];
        }
        return back();
    }
    public function data_anggota(){
        $data = $this->informationRepository->getAllAnggota();
        return view('admin.datamaster.anggota',[
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'data' => $data,
        ]);
    }
    public function showDetailAnggota(Request $request){
        return view('admin.datamaster.user_datadiri',[
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'tab' =>  $this->informationRepository->getAllTabungan(),
            'status' =>  $this->informationRepository->getUsrByKtp($request->noktp),
        ]);
    }
    public function data_rekening(){

        $dropdown_data = $this->informationRepository->getDropdown();
        $data = $this->informationRepository->getAllRekening();
        return view('admin.datamaster.rekening',[
            'data' => $data,
            'dropdown_rekening' => $dropdown_data,
        ]);
    }
    public function data_tabungan(){

        $dropdown_data = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllTabungan();
        return view('admin.datamaster.tabungan',[
            'data' => $data,
            'dropdown_tabungan' => $dropdown_data,
        ]);
    }
    public function data_deposito(){

        $dropdown_data = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllDeposito();
        return view('admin.datamaster.deposito',[
            'data' => $data,
            'dropdown_deposito' => $dropdown_data,
        ]);
    }
    public function data_pembiayaan(){

        $dropdown_data = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllPembiayaan();
        return view('admin.datamaster.pembiayaan',[
            'data' => $data,
            'dropdown_pembiayaan' => $dropdown_data,
        ]);
    }
    public function data_shu(){

        $data = $this->informationRepository->getAllSHU();
        return view('admin.datamaster.shu',[
            'data' => $data,
            'dropdown' => $this->informationRepository->getAllRekeningKM(),
        ]);
    }
    public function data_jaminan(){

        $dropdown_data = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllJaminan();
        return view('admin.datamaster.jaminan',[
            'data' => $data,
            'dropdown' => $dropdown_data,
        ]);
    }
    public function add_bmt(Request $request){
        if($this->informationRepository->addBMT())
            return redirect()
                ->back()
                ->withSuccess(sprintf('Penyimpanan BMT berhasil ditambah!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Data Penyimpanan BMT sudah diupdate!.');
        }
    }

//end of DATAMASTER

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
            'data' =>  $this->informationRepository->getAllpengajuan($date),
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

    public function pengajuan_maal(){
        $home = new HomeController;
        $date = $home->date_query(0);

        $idRekeningTeller = json_decode(Auth::user()->detail,true)['id_rekening'];
        $selfRekening = $this->informationRepository->getDetailTeller($idRekeningTeller);
        
        return view('admin.maal',[
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
            'periode'  => $this->informationRepository->periode(),
            'selfRekening' => $selfRekening,
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
            'data' =>  $this->informationRepository->getAllpengajuan($date),
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
    public function transfer(){
        return view('admin.transaksi.transfer',[
            'nasabah' => count($this->informationRepository->getAllNasabah()),
            'data' => $this->informationRepository->getAllPengajuanBMT(),
            // 'dropdown' => $this->informationRepository->getDdBMT(),
            'dropdown' => $this->rekeningReporsitory->getRekening($type="detail", $sort="id_rekening")
        ]);
    }
    public function transfer_rekening(Request $request){
        // $jurnal_lain = $this->rekeningReporsitory->transferRekening($request, "Transfer antar Rekening");
        // return response()->json($jurnal_lain);
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $dari = $this->bmt->where('id_rekening',$request->dari)->first();
        if($dari['saldo']<$request->jumlah){
            return redirect()
                ->back()
                ->withInput()->with('message', 'Saldo Rekening '. $dari['nama'].' tidak cukup!.');
        }

        if($this->informationRepository->transferRekening($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Transfer Rekening berhasil dilakukan!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Transfer Rekening gagal dilakukan!.');
        }
    }
    public function jurnal_lain(Request $request){
        $jurnal_lain = $this->rekeningReporsitory->transferRekening($request, "Jurnal Lain");
        if($jurnal_lain['type'] == "success")
            return redirect()
                ->back()
                ->withSuccess(sprintf($jurnal_lain['message']));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $jurnal_lain['message']);
        }
    }
    public function edit_saldo(Request $request){
        if($this->informationRepository->edit_saldo($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Saldo Rekening berhasil diubah!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Saldo Rekening gagal diubah!.');
        }
    }

    public function upgrade_simpanan(Request $request){
        if($this->informationRepository->upgradeSimpanan($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Simpanan berhasil di-upgrade!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Simpanan gagal di-upgrade!.');
        }
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
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        if ($request->atasnama == 1) {
            $atasnama = "Pribadi";
            $nama = $usr['nama'];
            $id_user = $usr->id;
        } else {
            $atasnama = "Lembaga";
            $nama = $request->nama;
            $id_user = $request->id_user;
        }
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
            $data['deposito']=$request->deposito;
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
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $detail = [
            'id'  => $request->id_,
            'syarat' => $request->syarat,
            'identitas'  => $request->identitas,
        ];

        $pengajuan = $this->informationRepository->getPengajuan($request->id_)['detail'];
        if(json_decode($pengajuan,true)['keterangan'] == "Tabungan Awal"){
            if(preg_match("/^[0-9,]+$/", $request->pokok)) $request->pokok = str_replace(',',"",$request->pokok);
            if(preg_match("/^[0-9,]+$/", $request->wajib)) $request->wajib = str_replace(',',"",$request->wajib);
            $detail['pokok'] = $request->pokok;
            $detail['wajib'] = $request->wajib;
        }
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
                if(count($tab)<0)$id=1;
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
                if(count($tab)<0)$id=1;
                else $id=str_after($tab[0]['id_deposito'], '.')+1;
                $id_pengajuan =$this->daftar_pengajuan_baru($request,$usr);
                $data=[
                    'tipe'      =>  "Deposito",
                    'tempo' =>json_decode($rekening->detail,true)['jangka_waktu'],
                    'id_UsrTDP' => $usr->id.".".$id,
                    'id_TDP'=>  $request->deposito_,
                    'id_pengajuan' =>$id_pengajuan,
                    'jenis_TDP' => $rekening->nama_rekening,
                    'id_user' => $usr->id,
                    'id_pencairan' => $request->rek_tabungan,
                    'status' =>"Setoran Awal",
                ];
                if($this->informationRepository->setoranAwal($detail_pdeposito,$data)){
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf('Pembukaan Rekening Mudharabah Berjangka berhasil dilakukan!.'));
                }
                else{
                    if($this->informationRepository->delPengajuan($id_pengajuan))
                        return redirect()
                            ->back()
                            ->withInput()->with('message', 'Pembukaan Rekening Mudharabah Berjangka gagal dilakukan!.');
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
                if(count($tab)<0)$id=1;
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
                    'saksi1'=>$request->saksi1,
                    'saksi2'=>$request->saksi2,
                    'alamat2'=>$request->alamat2,
                    'ktp2'=>$request->ktp2,
                ];
                if($this->informationRepository->setoranAwal($detail_ppembiayaan,$data)){
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

//       VIA TRANSAKSI PENGAJUAN
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
        if($request->syarat=="ya" && $request->identitas=="ya")
            if($this->informationRepository->pengajuanActive($detail))
                return redirect()
                    ->back()
                    ->withSuccess(sprintf('Aktivasi Pengajuan berhasil dilakukan!.'));
            else{
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Aktivasi Pengajuan gagal dilakukan!.');
            }
        else
            return redirect()
                ->back()
                ->withInput()->with('message', 'Aktivasi Pengajuan gagal dilakukan!.');

    }
    public function daftar_debit_kredit($request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        if($request->idcKre=="CK") $idtab = $this->informationRepository->getTabById($request->id_);
        else $idtab = $request->idRek;
        $usr_tab = $this->tabungan->where('id', $idtab)->first();
        $usr = $this->user->where('id', $usr_tab['id_user'])->first();
        $request->id_user = $usr['id'];
        $request->idtab = $idtab;

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
    public function konfirmasi(Request $request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        if(isset($request->idcKre))
            if($request->idRek<$request->jumlah){
                $rek = "Mohon maaf Saldo Rekening Tabungan Anda tidak CUKUP!.";
                return redirect()
                    ->back()
                    ->withInput()->with('message', $rek);
            }
        $this->validate($request, [
            'file' => 'file|max:2000', // max 2MB
        ]);

        if($request->idcKre=="CK"){
            $saldobank="-";
            if($request->daribank)
                $saldobank=(BMT::where('id_rekening',$request->daribank)->first());
            elseif($request->dariteller)
                $saldobank=(BMT::where('id_rekening',$request->dariteller)->first());
            else
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Saldo '.$saldobank->nama." Tidak Cukup!");

            if(str_replace(",","",$request->jumlahCK)){
                if(floatval(str_replace(",","",$request->jumlahCK)) > floatval($saldobank->saldo)){
                    return redirect()
                        ->back()
                        ->withInput()->with('message', 'Saldo '.$saldobank->nama." Tidak Cukup!");
                };
            }
            else{
                if(floatval($request->jumlah) > floatval($saldobank->saldo)){
                    return redirect()
                        ->back()
                        ->withInput()->with('message', 'Saldo '.$saldobank->nama." Tidak Cukup!");
                };
            }

        }
        if($request->teller=="teller")$request = $this->daftar_debit_kredit($request);
        if($this->informationRepository->penyimpananDebit($request)){

            return redirect()
                ->back()
                ->withSuccess(sprintf('Konfirmasi Pembayaran berhasil dilakukan!.'));
        }
        else{
            ($this->informationRepository->delPengajuan($request->id));
            return redirect()
                ->back()
                ->withInput()->with('message', 'Konfirmasi Pembayaran gagal dilakukan!.');
        }
    }
    public function daftar_angsuran($request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        if(preg_match("/^[0-9,]+$/", $request->jumlah_)) $request->jumlah_ = str_replace(',',"",$request->jumlah_);
        if(preg_match("/^[0-9,]+$/", $request->bayar_mar)) $request->bayar_mar = str_replace(',',"",$request->bayar_mar);
        if(preg_match("/^[0-9,]+$/", $request->bayar_ang)) $request->bayar_ang = str_replace(',',"",$request->bayar_ang);
        if($request->jumlah == null){
            $jumlah = $request->jumlah_;
            $wajib = $request->jumlah_;
        }
        else {
            $wajib =$request->pokok_;
            $jumlah = $request->jumlah;
        }
        if ($jumlah < $wajib) {
            return redirect()->back()->withInput()->with('message', "Jumlah biaya minimal Rp".number_format($request->pokok_,2));
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
            'id' =>$usr['id'],
            'nama' => $usr['nama'],
            'bank_user' => $request->daribank,
            'no_bank' => $request->nobank,
            'atasnama' => $atasnama,
            'bank' => $request->bank,
            'pokok' => $request->pokok_,
            'sisa_ang' => floatval($request->sisa_ang),
            'sisa_mar' => floatval($request->sisa_mar),
            'bayar_ang' => floatval($request->bayar_ang),
            'bayar_mar' => floatval($request->bayar_mar),
            'jumlah' => floatval($request->bayar_mar) + floatval($request->bayar_ang),
            'nisbah' => $request->nisbah,
            'jenis' => $request->jenis_,
            'tipe_pembayaran' => $request->tipe_,
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
            $detail_usr=json_decode($this->informationRepository->getPemUsrId($request->id_)['detail'],true);
            if($detail_usr['tagihan_bulanan'] < $detail_usr['angsuran_pokok']){
                $msg="";
                if(number_format($detail_usr['tagihan_bulanan'])==number_format($detail_usr['angsuran_pokok']-($detail_usr['margin']/$detail_usr['lama_angsuran'])) )
                    if($request->tipe_==1) goto end;
                    else $msg ="Angsuran";
                elseif (number_format($detail_usr['tagihan_bulanan'])==number_format($detail_usr['margin']/$detail_usr['lama_angsuran']))
                    if($request->tipe_==0) goto end;
                    else $msg="Margin";
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Silahkan melunasi tagihan ' .$msg.' terlebih dahulu!.');
            }
            end:
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
//end of TRANSAKSI

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
        $data = $this->informationRepository->getAllpengajuanTab($date);
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
    public function periode_tab(Request $request){
        $home = new HomeController;
        $date = $home->date_query($request->periode);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanTab($date);
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
        return view('admin.tabungan.nasabah',[
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'kegiatan' => $dropdown,
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
//end of NAVBAR TABUNGAN

    //NAVBAR DAFTAR NASABAH DEPOSITO
    public function pengajuan_deposito(){
        $home = new HomeController;
        $date = $home->date_query(0);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanDep($date);

        $idRekeningTeller = json_decode(Auth::user()->detail,true)['id_rekening'];
        $selfRekening = array($this->informationRepository->getDetailTeller($idRekeningTeller));

        return view('admin.deposito.pengajuan',[
            'datasaldoDep' =>  $this->informationRepository->getAllDep(),
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
            'dropdown' => $dropdown,
            'dropdown2' => $dropdown2,
            'dropdown3' => $dropdown3,
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'periode'  => $this->informationRepository->periode(),
            'selfRekening'  => $selfRekening,
        ]);
    }
    public function periode_dep(Request $request){
        $home = new HomeController;
        $date = $home->date_query($request->periode);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanDep($date);
        return view('admin.deposito.pengajuan',[
            'datasaldoDep' =>  $this->informationRepository->getAllDepUsr(),
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
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
        return view('admin.deposito.nasabah',[
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
//end of NAVBAR DEPOSITO

    //NAVBAR DAFTAR NASABAH PEMBIAYAAN
    public function pengajuan_pembiayaan(){
        $home = new HomeController;
        $date = $home->date_query(0);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanPem($date);

        return view('admin.pembiayaan.pengajuan',[
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
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
    public function periode_pem(Request $request){
        $home = new HomeController;
        $date = $home->date_query($request->periode);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanPem($date);

        return view('admin.pembiayaan.pengajuan',[
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'kegiatan' => $dropdown,
            'datasaldo' =>  $this->informationRepository->getAllTabUsr(),
            'data' => $data,
            'tab' =>  $this->informationRepository->getAllTab(),
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
        return view('admin.pembiayaan.nasabah',[
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


    /* --------------------------------------------------------------------
    -----------------------------------------------------------------------
    ------------- Admin Dashbord Transaksi Menu Controller ----------------
    -----------------------------------------------------------------------
    ---------------------------------------------------------------------*/

    /**
     * Simpanan anggota controller
     * @return View
    */
    public function simpanan(Request $request)
    {
        return view('admin.transaksi.simpanan');
    }

    /**
     * Tabungan anggota controller
     * @return View
    */
    public function tabungan(){
        return view('admin.transaksi.tabungan');
    }

    /**
     * Deposito anggota controller
     * @return View
    */
    public function deposito(){
        return view('admin.transaksi.deposito');
    }
    

//end of NAVBAR PEMBIAYAAN


}
