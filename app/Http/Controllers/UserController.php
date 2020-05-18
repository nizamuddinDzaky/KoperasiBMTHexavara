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
use App\Repositories\PembiayaanReporsitory;
use App\Repositories\SimpananReporsitory;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\AccountReporsitories;
use App\Repositories\DepositoReporsitories;
use App\Repositories\DonasiReporsitories;
use App\Repositories\RekeningReporsitories;
use Carbon\Carbon;

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
                                InformationRepository $informationRepository,
                                PembiayaanReporsitory $pembiayaanReporsitory,
                                SimpananReporsitory $simpananReporsitory,
                                PengajuanReporsitories $pengajuanReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                AccountReporsitories $accountReporsitory,
                                DepositoReporsitories $depositoReporsitory,
                                DonasiReporsitories $donasiReporsitory,
                                RekeningReporsitories $rekeningReporsitory
                                )
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
        $this->pembiayaanReporsitory = $pembiayaanReporsitory;
        $this->simpananReporsitory = $simpananReporsitory;
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->accountReporsitory = $accountReporsitory;
        $this->depositoReporsitory = $depositoReporsitory;
        $this->donasiReporsitory = $donasiReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
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
        $data2=$this->pembiayaan->where([ ['id_user',Auth::user()->id], ['status', 'active'] ])->get();
        // $data3=$this->deposito->where('id_user',Auth::user()->id)->get();
        $data3=$this->depositoReporsitory->getUserDeposito($status="active", $user=Auth::user()->id);
        $sum=$sumbln=$sumtag=$sumdep=$sumpin=0;

        foreach ($data as $dt){
            $sum +=(json_decode($dt->detail,true)['saldo']);
        }
        foreach ($data2 as $dt){
            $sumpin +=(json_decode($dt->detail,true)['sisa_angsuran']);
            $sumtag +=(json_decode($dt->detail,true)['sisa_angsuran']);
            $sumbln +=(json_decode($dt->detail,true)['tagihan_bulanan']);
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
            'simpanan' => json_decode($user,true),
            'pengajuan' => $this->informationRepository->getAllpengajuanUsr(20)
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
        $pembiayaan = $this->pembiayaanReporsitory->getPembiayaanSpecificUser();
        $is_pembiayaan = false;
        foreach($pembiayaan as $pem)
        {
            if($pem->status == "active")
            {
                $is_pembiayaan = true;
            }
        }
        // return response()->json();
        return view('users.pengajuan', [
            'tabungan_anggota'  => $this->tabunganReporsitory->getUserTabungan(Auth::user()->id),
            'simpanan_anggota'  => $this->simpananReporsitory->getSimwaAndSimpok(),
            'pembiayaan_anggota' => $this->pembiayaanReporsitory->getPembiayaanSpecificUser(),
            'deposito_anggota'  => $this->depositoReporsitory->getUserDeposito($status="", $user=Auth::user()->id),
            'bank_bmt' => $this->tabunganReporsitory->getRekening('BANK'),
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

            'pembiayaanUser' => $this->pembiayaanReporsitory->getPembiayaanSpecificUser(),
            'rekening_tabungan' => $this->tabunganReporsitory->getRekening('TABUNGAN'),
            'all_deposito' => $this->tabunganReporsitory->getRekening('DEPOSITO'),
            'is_active_pembiayaan' => $is_pembiayaan,
            'tabungan'  => $this->informationRepository->getAllTabUsrActive()
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
        // return response()->json($this->informationRepository->getAllpengajuanUsrTab());
        return view('users.tabungan', [
            'bank_bmt' => $this->tabunganReporsitory->getRekening('BANK'),
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
            'dropdown9' => $this->informationRepository->getAllJaminanDD()
        ]);
    }

    public function detail_tabungan(Request $request)
    {
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
            'debit' => $debit,
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
            'jenis' => "Debit Tabungan [" . $debit . "]",
            'status' => "Menunggu Konfirmasi",
        ];
        $data = [
            "detail" => $detail,
            "keterangan" => $keterangan,
        ];

        if ($this->informationRepository->TransaksiTabUsrDeb($data, $request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan Debit Tabungan berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan Debit Tabungan gagal dilakukan!.');
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
            'kredit' => $kredit,
            'id_tabungan' => $request->id_,
            'id' => Auth::user()->id,
            'nama' => Auth::user()->nama,
            'bank' => $request->bank,
            'no_bank' => $request->nobank,
            'daribank' => null,
            'atasnama' => $atasnama,
            'jumlah' => $request->jumlah,
        ];
        $keterangan = [
            'jenis' => "Kredit Tabungan [" . $kredit . "]",
            'status' => "Menunggu Konfirmasi",
        ];
        $data = [
            "detail" => $detail,
            "keterangan" => $keterangan,
        ];
        if ($this->informationRepository->TransaksiTabUsrKre($data, $request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan Kredit Tabungan berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan Kredit Tabungan gagal dilakukan!.');
        }
    }








//    NAVBAR MENU->DEPOSITO
    public function pengajuan_dep(Request $request)
    {
        if(isset($request->perpanjang_otomatis) && $request->perpanjang_otomatis == "on")
        {
            $perpanjang_otomatis = true;
        }
        else
        {
            $perpanjang_otomatis = false;
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

        if($request->kredit == 1)
        {
            $kredit = "Transfer";

            $file_name = $request->file->getClientOriginalName();
            $file_name_replace = preg_replace('/\s+/', '_', $file_name);
            $fileToUpload = time() . "-" . $file_name_replace;

            $request->file('file')->storeAs(
                'file/', $fileToUpload
            );

            $path_bukti = "storage/file/" . $fileToUpload;
            $bank_bmt_tujuan = $request->bank_tujuan;
        }
        else
        {
            $kredit = "Tunai";
            $path_bukti = null;
            $bank_bmt_tujuan = null;
        }

        if(preg_match("/^[0-9,]+$/", $request->jumlah)) $request->jumlah = str_replace(',',"",$request->jumlah);
        $detail = [
            'atasnama' => $atasnama,
            'nama' => $nama,
            'id' => $id_user,
            'jumlah' => $request->jumlah,
            'deposito' => $request->deposito_,
            'keterangan' => $request->keterangan,
            'id_pencairan' => $request->rek_tabungan,
            'kredit'    => $kredit,
            'bank_bmt_tujuan' => $bank_bmt_tujuan,
            'path_bukti' => $path_bukti,
            "perpanjangan_otomatis" => $perpanjang_otomatis
        ];
        $keterangan = [
            'jenis' => "Buka Mudharabah Berjangka",
            'status' => "Menunggu Konfirmasi",
        ];
        if ($this->informationRepository->pengajuanDep($detail, $keterangan)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Pengajuan pembukaan Mudharabah Berjangka berhasil dilakukan!.'));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Pengajuan pembukaan Mudharabah Berjangka gagal dilakukan!.');
        }
    }

    public function deposito()
    {
        $dataInDate = $this->informationRepository->getAllDepUsrActiveInDate();
        $data = $this->informationRepository->getAllDepUsrActive();
        
        $depositoExpiredNotAutoExtended = array();
        foreach ($dataInDate as $value) {
            if(json_decode($value->detail)->perpanjangan_otomatis == true)
            {
                array_push($depositoExpiredNotAutoExtended, $value);
            }
        }
        $tab = $this->informationRepository->getAllTabUsr();
        return view('users.deposito', [
            'bank_bmt' => $this->tabunganReporsitory->getRekening('BANK'),
            'datasaldoDepInDate' => $depositoExpiredNotAutoExtended,
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
        ]);
    }

    public function detail_deposito(Request $request)
    {
        return view('users.detail_deposito', [
            'data' => $this->informationRepository->getTransaksiDepUsr($request->id_),
        ]);
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
        $data = $this->informationRepository->getAllPemUsr();
        //  dd($data[0]);
        $tab = $data;
        return view('users.pembiayaans', [
            'bank_bmt' => $this->tabunganReporsitory->getRekening('BANK'),
            'kegiatan' => $data,
            'data' => $data,
            'datasaldo' => $data,
            'tabungan'  => $this->informationRepository->getAllTabUsrActive(),
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
        // return response()->json($this->informationRepository->getTransaksiPemUsr($request->id_));
        return view('users.detail_pembiayaan', [
            'data' => $this->informationRepository->getTransaksiPemUsr($request->id_),
        ]);
    }

    public function angsur_pembiayaan(Request $request)
    {
        $sisa_pinjaman = explode(" ",$request->idRek)[8];
        $tabungan = Tabungan::where('id', $request->tabungan)->first();
        // $this->validate($request, [
        //     'file' => 'file|max:2000', // max 2MB
        // ]);
        if ($request->debit == 1) {
            $kredit = "Transfer";
            $atasnama = $request->atasnama;
            $bank = $request->bank;
        } elseif($request->debit == 2) {
            $kredit = "Tabungan";
            $atasnama = $tabungan->jenis_tabungan;
            $bank = $tabungan->id;
        } else {
            $kredit = "Tunai";
            $atasnama = Auth::user()->nama;
            $bank = null;
        }
        if(preg_match("/^[0-9,]+$/", $request->bayar_mar)) $request->bayar_mar = str_replace(',',"",$request->bayar_mar);
        if(preg_match("/^[0-9,]+$/", $request->bayar_ang)) $request->bayar_ang = str_replace(',',"",$request->bayar_ang);
        if(preg_match("/^[0-9,]+$/", $request->sisa_mar)) $request->sisa_mar = str_replace(',',"",$request->sisa_mar);
        if(preg_match("/^[0-9,]+$/", $request->sisa_ang)) $request->sisa_ang = str_replace(',',"",$request->sisa_ang);
        if(preg_match("/^[0-9,]+$/", $request->nisbah)) $request->nisbah = str_replace(',',"",$request->nisbah);
        $detail = [
            'angsuran' => $kredit,
            'id_pembiayaan' => $request->id_,
            'id' => Auth::user()->id,
            'nama' => Auth::user()->nama,
            'bank_user' => $request->daribank,
            'no_bank' => $request->nobank,
            'atasnama' => $atasnama,
            'bank' => $bank,
            'pokok' => $request->pokok_,
            'tipe_pembayaran' => $request->tipe_,
            'sisa_ang' => floatval($request->sisa_ang),
            'sisa_mar' => floatval($request->sisa_mar),
            'bayar_ang' => floatval($request->bayar_ang),
            'bayar_mar' => floatval($request->bayar_mar),
            'jumlah' => floatval($request->bayar_mar) + floatval($request->bayar_ang),
            'nisbah' => $request->nisbah,
            'jenis' => $request->jenis_,
            'sisa_pinjaman' => $sisa_pinjaman
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
        // return response()->json($this->tabunganReporsitory->getRekening("BANK"));
        return view('users.donasi_maal',[
            'bank_bmt' => $this->tabunganReporsitory->getRekening("BANK"),
            'kegiatan' => $this->informationRepository->getAllMaal(),
            'tabungan' => $this->tabunganReporsitory->getUserTabungan(Auth::user()->id),
            'riwayat_zis' => $this->donasiReporsitory->getPengajuanDonasi($type="zis", $user=Auth::user()->id),
            'riwayat_wakaf' => $this->donasiReporsitory->getPengajuanDonasi($type="wakaf", $user=Auth::user()->id),
            // 'riwayat_zis' => $this->donasiReporsitory->getUserDonasi(Auth::user()->id, "zis"),
            // 'riwayat_wakaf' => $this->donasiReporsitory->getUserDonasi(Auth::user()->id, "wakaf"),
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
    
    public function donasimaal(Request $request) {
        if($request->debit == 2 && isset($request->rekening))
        {
            if($request->rekening != null)
            {

                $rekening = Tabungan::where('id_tabungan', $request->rekening)->first();

                $saldo = json_decode($rekening->detail)->saldo;

                if($saldo > $request->nominal) {
                    $pengajuan = $this->donasiReporsitory->sendDonasi($request); 
                    if($pengajuan['type'] == 'success') {
                        return redirect()
                            ->back()
                            ->withSuccess(sprintf($pengajuan['message']));
                    } else{
                        return redirect()
                            ->back()
                            ->withInput()->with('message', $pengajuan['message']);
                    }
                } else {
                    return redirect()
                            ->back()
                            ->withInput()->with('message', 'Saldo anda tidak cukup');
                }

            } else {

                $pengajuan = $this->donasiReporsitory->sendDonasi($request); 
                if($pengajuan['type'] == 'success') {
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf($pengajuan['message']));
                } else{
                    return redirect()
                        ->back()
                        ->withInput()->with('message', $pengajuan['message']);
                }

            }
        }
        elseif($request->debit == 2 && !isset($request->rekening))
        {
            return redirect()
                            ->back()
                            ->withInput()->with('message', 'Tidak ada rekening tabungan dipilih.');
        }
        elseif($request->debit !== 2)
        {
            if($request->rekening != null)
            {

                $rekening = Tabungan::where('id_tabungan', $request->rekening)->first();

                $saldo = json_decode($rekening->detail)->saldo;

                if($saldo > $request->nominal) {
                    $pengajuan = $this->donasiReporsitory->sendDonasi($request); 
                    if($pengajuan['type'] == 'success') {
                        return redirect()
                            ->back()
                            ->withSuccess(sprintf($pengajuan['message']));
                    } else{
                        return redirect()
                            ->back()
                            ->withInput()->with('message', $pengajuan['message']);
                    }
                } else {
                    return redirect()
                            ->back()
                            ->withInput()->with('message', 'Saldo anda tidak cukup');
                }

            } else {

                $pengajuan = $this->donasiReporsitory->sendDonasi($request); 
                if($pengajuan['type'] == 'success') {
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf($pengajuan['message']));
                } else{
                    return redirect()
                        ->back()
                        ->withInput()->with('message', $pengajuan['message']);
                }

            }
        }

    }

    public function transaksi_maal(){
        return view('admin.maal.transaksi',[
            'data' =>$this->informationRepository->getAllPenyimpananMaalUsr(),
        ]);
    }

    /** 
     * Harta view controller
     * @return View
    */
    public function harta() {
        $simpananWajibAndPokok = $this->simpananReporsitory->getSimwaAndSimpok();
        return view('users.dashboard.harta', [
            "simwaAndSimpok" => $simpananWajibAndPokok
        ]);
    }


    /** 
     * Detail simpanan wajib controller
     * @return View
    */
    public function simpanan_wajib(Request $request) {
        return view('users.harta.simpanan_wajib');
    }

    /** 
     * Detail simpanan pokok controller
     * @return View
    */
    public function simpanan_pokok(Request $request) {
        return view('users.harta.simpanan_pokok');
    }
    
    /** 
     * Riwayat simpanan khusus controller
     * @return View
    */
    public function simpanan_khusus(Request $request) {
        return view('users.harta.simpanan_khusus');
    }

    /** 
     * Simpanan anggota controller
     * @return View
    */
    public function simpanan()
    {
        $simpanan = $this->simpananReporsitory->getUserPengajuanSimpananFromSpecificUser();
        $tabungan_user = $this->tabunganReporsitory->getUserTabungan(Auth::user()->id);
        $bank_bmt = $this->tabunganReporsitory->getRekening('BANK');
        return view('users.simpanan', [
            'data' => $simpanan,
            'tabungan' => $tabungan_user,
            'bank_bmt' => $bank_bmt
        ]);
    }

    /** 
     * Pengajuan simpanan anggota controller
     * @return Response
    */
    public function pengajuan_simpanan(Request $request)
    {
        $simpanan = $this->simpananReporsitory->pengajuanSimpanan($request);
        if($simpanan['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($simpanan['message']));
        } else{
            return redirect()
                ->back()
                ->withInput()->with('message', $simpanan['message']);
        }
    }

    public function extend_deposito(Request $request)
    {
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

    /** 
     * Detail simpanan anggota
     * @return Response
    */
    public function detail_simpanan($jenis)
    {
            
        $detail_simpanan = $this->simpananReporsitory->detailSimpanan($jenis);

        return view('users.detail_wajibpokok', [
            'data' => $detail_simpanan,
            'saldo' => json_decode(Auth::user()->wajib_pokok)->$jenis,
            'jenis' => $jenis
        ]);
    }

    /** 
     * Keluar dari anggota controller
     * @return Response
    */
    public function keluar_dari_anggota(Request $request)
    {
        $tabungan_user = Tabungan::where('id_user', Auth::user()->id)->first();
        $detailToPengajuan = [
            "atasnama"      => "Pribadi",
            "nama"          => Auth::user()->nama,
            "id"            => Auth::user()->id,
            "akad"          => $tabungan_user->id_rekening,
            "tabungan"      => $tabungan_user->id_rekening,
            "keterangan"    => null,
            "nama_rekening" => $tabungan_user->jenis_tabungan
        ];
        $dataToPengajuan = [
            "id_user"           => Auth::user()->id,
            "id_rekening"       => $tabungan_user->id_rekening,
            "jenis_pengajuan"   => "Penutupan Rekening",
            "status"            => "Menunggu Konfirmasi",
            "kategori"          => "Penutupan Rekening",
            "detail"            => $detailToPengajuan,
            "teller"            => 0
        ];

        $pengajuan = $this->pengajuanReporsitory->createPengajuan($dataToPengajuan);
        if($pengajuan['type'] == 'success') {
            return redirect()
                ->back()
                ->withSuccess(sprintf($pengajuan['message']));
        } else{
            return redirect()
                ->back()
                ->withInput()->with('message', $pengajuan['message']);
        }
    }

    /** 
     * Pelunasan Pembiayaan controller
     * @return Response
    */
    public function pelunasan_pembiayaan(Request $request)
    {
        $id_rekening = explode(" ", $request->idRek)[5];
        $pembiayaan = Pembiayaan::where('id_rekening', $id_rekening)->first() ;
        // return response()->json($request);

        if ($request->debit == 1) {
            $kredit = "Transfer";
            $atasnama = $request->atasnama;
            $bank = $request->bank;
            
            $file_name = $request->file->getClientOriginalName();
            $file_name_replace = preg_replace('/\s+/', '_', $file_name);
            $fileToUpload = time() . "-" . $file_name_replace;

            $request->file('file')->storeAs(
                'public/transfer/', $fileToUpload
            );

        } elseif($request->debit == 2) {
            $tabungan = Tabungan::where('id', $request->tabungan)->first();
            $kredit = "Tabungan";
            $atasnama = $tabungan->jenis_tabungan;
            $bank = $tabungan->id;
            $fileToUpload = null;
        } else {
            $kredit = "Tunai";
            $atasnama = Auth::user()->nama;
            $bank = null;
            $fileToUpload = null;
        }

        if(explode(" ", $request->idRek)[1] > explode(" ", $request->idRek)[2])
        {
            $bayar_margin = explode(" ", $request->idRek)[2] * 2;
        }
        else
        {
            $bayar_margin = explode(" ", $request->idRek)[2];
        }

        $detail = [
            'angsuran' => $kredit,
            'id_pembiayaan' => explode(" ", $request->idRek)[6],
            'id' => Auth::user()->id,
            'nama' => Auth::user()->nama,
            'bank_user' => $request->daribank,
            'no_bank' => $request->nobank,
            'atasnama' => $atasnama,
            'bank' => $bank,
            'pokok' => explode(" ", $request->idRek)[0],
            'tipe_pembayaran' => $request->debit,
            'sisa_ang' => explode(" ", $request->idRek)[0],
            'sisa_mar' => explode(" ", $request->idRek)[1],
            'bayar_ang' => explode(" ", $request->idRek)[0],
            'bayar_mar' => $bayar_margin,
            'jumlah' => explode(" ", $request->idRek)[0] + (explode(" ", $request->idRek)[2] * 2),
            'nisbah' => $request->nisbah,
            'jenis' => $request->debit,
            'sisa_pinjaman' => explode(" ", $request->idRek)[0] + explode(" ", $request->idRek)[1],
            'path_bukti'    => $fileToUpload,
            'id_rekening'   => $pembiayaan->id_rekening,
            'nama_pembiayaan'   => $pembiayaan->jenis_pembiayaan
        ];

        $dataToPengajuan = [
            "id_user"           => Auth::user()->id,
            "id_rekening"       => explode(" ", $request->idRek)[5],
            "jenis_pengajuan"   => "Pelunasan Pembiayaan [" . $kredit . "]",
            "status"            => "Menunggu Konfirmasi",
            "kategori"          => "Pelunasan Pembiayaan",
            "detail"            => $detail,
            "teller"            => 0
        ];

        $create_pengajuan = $this->pengajuanReporsitory->createPengajuan($dataToPengajuan);
        if ($create_pengajuan['type'] == "success") {
            return redirect()
                ->back()
                ->withSuccess(sprintf($create_pengajuan['message']));
        } else {
            return redirect()
                ->back()
                ->withInput()->with('message', $create_pengajuan['message']);
        }
    }
}
