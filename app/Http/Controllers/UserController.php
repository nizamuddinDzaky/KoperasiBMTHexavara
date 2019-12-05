<?php

namespace App\Http\Controllers;

use App\Deposito;
use App\Pembiayaan;
use App\Pengajuan;
use App\Repositories\InformationRepository;
use App\Tabungan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use Illuminate\Support\Facades\Input;
use \Validator;
class UserController extends Controller
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
                                InformationRepository $informationRepository)
    {
        $this->middleware(function ($request, $next) {

            $this->id_role = Auth::user()->tipe;
//            if (!$this->id_role == "teller")
//                return redirect('login')->with('status', [
//                    'enabled' => true,
//                    'type' => 'danger',
//                    'content' => 'Tidak boleh mengakses'
//                ]);
            return $next($request);
        });
        $this->rekening = $rekening;
        $this->user = $user;
        $this->tabungan = $tabungan;
        $this->deposito = $deposito;
        $this->pembiayaan = $pembiayaan;
        $this->pengajuan = $pengajuan;
        $this->informationRepository = $informationRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

//    DASHBOARD
    public function index()
    {
        $data=$this->tabungan->where('id_user',Auth::user()->id)->get();
        $data2=$this->pembiayaan->where('id_user',Auth::user()->id)->get();
        $data3=$this->deposito->where('id_user',Auth::user()->id)->get();
        $sum=$sumbln=$sumtag=$sumdep=$sumpin=0;
        foreach ($data as $dt){
            $sum +=(json_decode($dt->detail,true)['saldo']);
        }
        foreach ($data2 as $dt){
            $sumpin +=(json_decode($dt->detail,true)['pinjaman']);
            
            // $sumtag +=(json_decode($dt->detail,true)['sisa_pinjaman']);
            $sumtag +=(json_decode($dt->detail,true)['sisa_angsuran']);

            // $sumbln +=(json_decode($dt->detail,true)['tagihan_bulanan']);
            $sumbln +=(json_decode($dt->detail,true)['sisa_ang_bln']);
        }
        foreach ($data3 as $dt){
            $sumdep +=(json_decode($dt->detail,true)['saldo']);
        }
        $user = User::where('id',Auth::user()->id)->first()['wajib_pokok'];
        return view('users.dashboard',[
            'tab' => $sum,
            'tagihan' => $sumtag,
            'bulanan' => $sumbln,
            'deposito' => $sumdep,
            'pinjaman' => $sumpin,
            'simwa' => json_decode($user,true)['wajib'],
            'simpok' => json_decode($user,true)['pokok'],
        ]);
    }

//    ISI IDENTITAS
    public function datadiri()
    {
        return view('users.datadiri',[
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'tab' =>  $this->informationRepository->getAllTabungan(),
            'status' =>  $this->informationRepository->getUsrByKtp(Auth::user()->no_ktp),
        ]);
    }

    public function addidentitas(Request $request)
    {
        $this->validate($request, [
            'filektp' => 'file|max:2000', // max 2MB
            'fileksk' => 'file|max:2000', // max 2MB
            'filenikah' => 'file|max:2000', // max 2MB
        ]);
        if(preg_match("/^[0-9,]+$/", $request->pendapatan)) $request->pendapatan = str_replace(',',"",$request->pendapatan);

        $detail = [
            'nama' => $request->nama,
            'no_ktp' => Auth::user()->no_ktp,
            'nik' => $request->nik,
            'telepon' => $request->telepon,
            'jenis_kelamin' => $request->jenisKel,
            'tempat_lahir' => $request->tempat,
            'tgl_lahir' => $request->tglLahir,
            'alamat_ktp' => $request->alamat,
            'alamat_domisili' => $request->domisili,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $request->kerja,
            'pendapatan' => $request->pendapatan,
            'alamat_kerja' => $request->alamatKer,
            'status' => $request->status,
            'nama_wali' => $request->wali,
            'ayah' => $request->ayah,
            'ibu' => $request->ibu,
            'jml_sumis' => $request->jsumis,
            'jml_anak' => $request->juman,
            'jml_ortu' => $request->jortu,
            'lain' => $request->lain,
            'rumah' => $request->rumah,
        ];
        if(Auth::user()->tipe=="teller"){
            $detail['id_rekening'] = json_decode(Auth::user()->detail,true)['id_rekening'];
        }

        if ($this->informationRepository->addIdentitas($detail, $request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Identitas Anggota berhasil disimpan!.'));
        }
        else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Identitas gagal disimpan!.');
        }
    }


//    NAVBAR PENGAJUAN
    public function pengajuan()
    {
        return view('users.pengajuan', [
            'kegiatan' => $this->informationRepository->getAllMaal(),
            'datasaldoDep' => $this->informationRepository->getAllDepUsr(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'datasaldoPem' => $this->informationRepository->getAllPemUsrActive(),
            'datasaldo' => $this->informationRepository->getTabUsr(),
            'data' => $this->informationRepository->getAllpengajuanUsr(),
            'data2' => $this->informationRepository->getAllpengajuanUsrTab(),
            'tab' => $this->informationRepository->getAllTabUsr(),
            'tabactive' =>  $this->informationRepository->getAllTabUsrActive(),
            'dropdown' => $this->informationRepository->getDdTab(),
            'dropdown2' => $this->informationRepository->getDdDep(),
            'dropdown3' => $this->informationRepository->getDdPem(),
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getDdTeller(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
        ]);
    }

//    NAVBAR MENU->TABUNGAN
    public function pengajuan_tab(Request $request)
    {
        if ($request->atasnama == 1) {
            $atasnama = "Pribadi";
            $nama = Auth::user()->nama;
            $id_user = Auth::user()->id;
        } else {
            $atasnama = "Lembaga";
            $nama = $request->nama;
            $id_user = $request->id_user;
        }
        $detail = [
            'atasnama' => $atasnama,
            'nama' => $nama,
            'id' => $id_user,
            'akad' => $request->tabungan,
            'tabungan' => $request->tabungan,
            'keterangan' => $request->keterangan,
        ];
        $keterangan = [
            'jenis' => "Buka Tabungan",
            'status' => "Menunggu Konfirmasi",
        ];
        if ($this->informationRepository->pengajuanTab($detail, $keterangan)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan pembukaan Tabungan berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan pembukaan Tabungan gagal dilakukan!.');
        }
    }

    public function tabungan()
    {
        $data = $this->informationRepository->getAllTabUsrActive();
        $dropdown2 = $this->informationRepository->getDdDep();
        return view('users.tabungan', [
            'kegiatan' => $data,
            'datasaldo' => $data,
            'data' => $data,
            'data2' => $this->informationRepository->getAllpengajuanUsrTab(),
            'tab' => $data,
            'tabactive' =>  $data,
            'dropdown' => $this->informationRepository->getDdTab(),
            'dropdown2' => $dropdown2,
            'dropdown3' => $data,
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getDdTeller(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'selfRekening' => array(),
        ]);
    }

    public function detail_tabungan(Request $request)
    {
        $data = $this->informationRepository->getTransaksiTabUsr($request->id_);
        // dd($data);

        return view('users.detail_tabungan', [
            'data' => $this->informationRepository->getTransaksiTabUsr($request->id_),
        ]);
    }
    public function detail_wajibpokok(Request $request)
    {
        $data = $this->informationRepository->getUsrByID($request->id_)['wajib_pokok'];
        return view('users.detail_wajibpokok', [
            'data' => $this->informationRepository->getTransaksiWajibPokokUsr($request->id_),
            'pokok' => json_decode($data,true)['pokok'],
            'wajib' => json_decode($data,true)['wajib'],
        ]);
    }

    public function debit_tabungan(Request $request)
    {
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $this->validate($request, [
            'file' => 'file|max:2000', // max 2MB
        ]);
        if ($this->informationRepository->cekRekStatusTab($request->idRek)) ;
        else {
            $rek = "Mohon maaf Rekening Tabungan Anda tidak AKTIF!.";
            return redirect()
                ->back()
                ->withInput()->with('message', $rek);
        }
        if ($request->debit == 1) {
            $debit = "Transfer";
            $bank = $request->bank;
        } else {
            $debit = "Tunai";
            $bank = null;
        }
        $detail = [
            'kredit' => $debit,
            'id_tabungan' => $request->idRek,
            'id' => Auth::user()->id,
            'nama' => Auth::user()->nama,
            'bank' => $bank,
            'no_bank' => $request->nobank,
            'daribank' => $request->daribank,
            'atasnama' => $request->atasnama,
            'jumlah' => $request->jumlah,
        ];
        $keterangan = [
            'jenis' => "Kredit Tabungan [" . $debit . "]",
            'status' => "Menunggu Konfirmasi",
        ];
        $data = [
            "detail" => $detail,
            "keterangan" => $keterangan,
        ];

        if ($this->informationRepository->TransaksiTabUsrDeb($data, $request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Kredit Tabungan berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Kredit Tabungan gagal dilakukan!.');
        }
    }

    public function kredit_tabungan(Request $request)
    {
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        if($request->idRek<$request->jumlah){
            $rek = "Mohon maaf Saldo Rekening Tabungan Anda tidak CUKUP!.";
            return redirect()
                ->back()
                ->withInput()->with('message', $rek);
        }
        $this->validate($request, [
            'file' => 'file|max:2000', // max 2MB
        ]);

        if ($this->informationRepository->cekRekStatusTab($this->informationRepository->getTabById($request->id_))) ;
        else {
            $rek = "Mohon maaf Rekening Tabungan Anda tidak AKTIF!.";
            return redirect()
                ->back()
                ->withInput()->with('message', $rek);
        }
        if ($request->kredit == 1) {
            $kredit = "Transfer";
            $atasnama = $request->atasnama;
        } else {
            $kredit = "Tunai";
            $atasnama = Auth::user()->nama;
        }
        $detail = [
            'debit' => $kredit,
            'id_tabungan' => $request->id_,
            'id' => Auth::user()->id,
            'nama' => Auth::user()->nama,
            'bank' => $request->bank,
            'no_bank' => $request->nobank,
            'atasnama' => $atasnama,
            'jumlah' => $request->jumlah,
        ];
        $keterangan = [
            'jenis' => "Debit Tabungan [" . $kredit . "]",
            'status' => "Menunggu Konfirmasi",
        ];
        $data = [
            "detail" => $detail,
            "keterangan" => $keterangan,
        ];
        if ($this->informationRepository->TransaksiTabUsrKre($data, $request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Debit Tabungan berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Debit Tabungan gagal dilakukan!.');
        }
    }

//    NAVBAR MENU->DEPOSITO
    public function pengajuan_dep(Request $request)
    {
        if(preg_match("/^[0-9,]+$/", $request->jumlah)){
            $tabunganSaldo = $this->informationRepository->getDetailTabById($request->rek_tabungan);
            $request->jumlah = str_replace(',',"",$request->jumlah);
            $tabunganSaldo->saldo = json_decode($tabunganSaldo->detail,true)['saldo'];
            if ($tabunganSaldo->saldo < $request->jumlah) {
                return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan pembukaan Deposito gagal dilakukan, saldo anda tidak cukup !');
            }
        }
        if ($request->atasnama == 1) {
            $atasnama = "Pribadi";
            $nama = Auth::user()->nama;
            $id_user = Auth::user()->id;
        } else {
            $atasnama = "Lembaga";
            $nama = $request->nama;
            $id_user = $request->id_user;
        }
        
        $detail = [
            'atasnama' => $atasnama,
            'nama' => $nama,
            'id' => $id_user,
            'jumlah' => $request->jumlah,
            'deposito' => $request->deposito_,
            'keterangan' => $request->keterangan,
            'id_pencairan' => $request->rek_tabungan,
        ];
        $keterangan = [
            'jenis' => "Buka Deposito",
            'status' => "Menunggu Konfirmasi",
        ];
        if ($this->informationRepository->pengajuanDep($detail, $keterangan)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan pembukaan Deposito berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan pembukaan Deposito gagal dilakukan!.');
        }
    }

    public function deposito()
    {
        $data = $this->informationRepository->getAllDepUsrActive();
        $tab = $this->informationRepository->getAllTabUsr();

        return view('users.deposito', [
            'datasaldoDep' => $data,
            'kegiatan' => $data,
            'datasaldo' => $data,
            'data' => $data,
            'data2' => $this->informationRepository->getAllpengajuanUsrDep(),
            'tab' => $tab,
            'dropdown' => $data,
            'dropdown2' => $this->informationRepository->getDdDep(),
            'dropdown3' => $this->informationRepository->getDdDep(),
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getDdTeller(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
            'selfRekening'  => array(),
        ]);
    }

    public function detail_deposito(Request $request)
    {
        return view('users.detail_deposito', [
            'data' => $this->informationRepository->getTransaksiDepUsr($request->id_),
        ]);
    }

    public function extend_deposito(Request $request)
    {
        $status = $this->deposito->where('id_deposito',$request->id_)->first();

        if($status['status']!="active"){
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan gagal dilakukan Rekening Deposito '.$request->id_." ".$status['jenis_deposito'].' Tidak Aktif!.');
        }
        if ($this->informationRepository->extendDeposito($request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan Perpanjangan Deposito berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan Perpanjangan Deposito gagal dilakukan!.');
        }
    }

    public function withdraw_deposito(Request $request)
    {
        $status = $this->deposito->where('id_deposito',$request->id_)->first();

        if($status['status']!="active"){
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan gagal dilakukan Rekening Deposito '.$request->id_." ".$status['jenis_deposito'].' Tidak Aktif!.');
        }
        if ($this->informationRepository->withdrawDeposito($request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan Pencairan Deposito berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan Pencairan Deposito gagal dilakukan!.');
        }
    }


//    NAVBAR MENU->PEMBIAYAAN
    public function pengajuan_pem(Request $request)
    {
        $this->validate($request, [
            'file' => 'file|max:2000', // max 2MB
        ]);
        if ($request->atasnama == 1) {
            $atasnama = "Pribadi";
            $nama = Auth::user()->nama;
            $id_user = Auth::user()->id;
        } else {
            $atasnama = "Lembaga";
            $nama = $request->nama;
            $id_user = $request->id_user;
        }
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $detail = [
            'atasnama' => $atasnama,
            'nama' => $nama,
            'id' => $id_user,
            'pembiayaan' => $request->pembiayaan,
            'jumlah' => $request->jumlah,
            'jenis_Usaha' => $request->jenisUsaha,
            'usaha' => $request->usaha,
            'keterangan' => $request->waktu . " " . $request->ketWaktu,
            'jaminan' => $request->jaminan,
        ];
        $keterangan = [
            'jenis' => "Pengajuan Pembiayaan",
            'status' => "Menunggu Konfirmasi",
        ];
        if ($this->informationRepository->pengajuanPem($detail, $keterangan, $request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan Pembiayaan berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan Pembiayaan gagal dilakukan!.');
        }
    }

    public function pembiayaan()
    {
        $data = $this->informationRepository->getAllPemUsrActive();
        // dd($this->informationRepository->getAllPemUsrActive());
        $tab = $data;
        return view('users.pembiayaan', [
            'kegiatan' => $data,
            'data' => $data,
            'datasaldo' => $data,
            'datasaldoPem' => $this->informationRepository->getAllPemUsrActive(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
            'data2' => $this->informationRepository->getAllpengajuanUsrPem(),
            'tab' => $tab,
            'dropdown' => $this->informationRepository->getDdTab(),
            'dropdown2' => $this->informationRepository->getDdDep(),
            'dropdown3' => $this->informationRepository->getDdPem(),
            'dropdown4' => $this->informationRepository->getAllrekeningNoUsrTab(),
            'dropdown5' => $this->informationRepository->getAllTabNoUsr(),
            'dropdown6' => $this->informationRepository->getDdBank(),
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getDdTeller(),
            'dropdown9' => $this->informationRepository->getAllJaminanDD(),
        ]);
    }

    public function detail_pembiayaan(Request $request)
    {
        return view('users.detail_pembiayaan', [
            'data' => $this->informationRepository->getTransaksiPemUsr($request->id_),
        ]);
    }

    public function angsur_pembiayaan(Request $request)
    {

        $this->validate($request, [
            'file' => 'file|max:2000', // max 2MB
        ]);
        if ($request->debit == 1) {
            $kredit = "Transfer";
            $atasnama = $request->atasnama;
        } else {
            $kredit = "Tunai";
            $atasnama = Auth::user()->nama;
        }
        if(preg_match("/^[0-9,]+$/", $request->bayar_mar)) $request->bayar_mar = str_replace(',',"",$request->bayar_mar);
        if(preg_match("/^[0-9,]+$/", $request->bayar_ang)) $request->bayar_ang = str_replace(',',"",$request->bayar_ang);
        if(preg_match("/^[0-9,]+$/", $request->nisbah)) $request->nisbah = str_replace(',',"",$request->nisbah);
        // dd($request);
        $detail = [
            'angsuran' => $kredit,
            'id_pembiayaan' => $request->id_,
            'id' => Auth::user()->id,
            'nama' => Auth::user()->nama,
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
        if ($this->informationRepository->TransaksiPemUsrAng($data, $request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan angsuran berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan Anggsuran gagal dilakukan!.');
        }
    }


//    UPDATE PROFPIC
    public function upload_foto(Request $request){
        $this->validate($request, [
            'file' => 'required|file|max:2000', // max 2MB
        ]);
        if($this->informationRepository->uploadProfpic($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Foto Profile berhasil diubah!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Foto Profile gagal diubah!.');
        }
    }

//    MAAL
    public function donasi_maal(){
        $dr =$this->informationRepository->getAllTabUsr();

        return view('users.donasi_maal',[
            'kegiatan' => $this->informationRepository->getAllMaal(),
            'dropdown' => $dr,
            'dropdown6' => $this->informationRepository->getDdBank(),
        ]);
    }
    public function donasi_maalt(){
        if(Auth::user()->tipe="teller")$dr =$this->informationRepository->getAllTab();
        else $dr =$this->informationRepository->getAllTabUsr();

        return view('users.donasi_maal',[
            'kegiatan' => $this->informationRepository->getAllMaal(),
            'dropdown' => $dr,
            'dropdown6' => $this->informationRepository->getDdBank(),
        ]);
    }
    public function donasimaal(Request $request){
        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $dari = $this->tabungan->where('id',$request->dari)->first();

        if($request->jenis==1){
            if(floatval(json_decode($dari['detail'],true)['saldo'])<$request->jumlah){
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Mohon maaf Saldo Rekening '. $dari['id_tabungan']." ". $dari['jenis_tabungan'].' Anda tidak cukup!.');
            }
        }else{
            $this->validate($request, [
                'file' => 'file|max:2000', // max 2MB
            ]);
            if($this->informationRepository->pengajuanMaal($request))
                return redirect()
                    ->back()
                    ->withSuccess(sprintf('Donasi kegiatan Maal berhasil dilakukan harap menunggu konfirmasi!.'));
            else{
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Donasi kegitan Maal gagal dilakukan!.');
            }
        }

        if($this->informationRepository->donasiMaal($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Donasi kegiatan Maal berhasil dilakukan!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Donasi kegitan Maal gagal dilakukan!.');
        }
    }

    public function transaksi_maal(){
        return view('admin.maal.transaksi',[
            'data' =>$this->informationRepository->getAllPenyimpananMaalUsr(),
        ]);
    }
}
