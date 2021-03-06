<?php

namespace App\Http\Controllers;

use App\BMT;
use App\Maal;
use App\Pengajuan;
use App\Repositories\InformationRepository;
use App\Repositories\PengajuanReporsitories;
use App\Tabungan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use App\Wakaf;
use App\Repositories\TabunganReporsitories;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Repositories\DonasiReporsitories;

class MaalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $id_role;

    public function __construct(Rekening $rekening,
                                User $user,
                                Maal $maal,
                                Wakaf $wakaf,
                                Tabungan $tabungan,
                                Pengajuan $pengajuan,
                                InformationRepository $informationRepository,
                                DonasiReporsitories $donasiReporsitory,
                                TabunganReporsitories $tabunganReporsitories,
                                PengajuanReporsitories $pengajuanReporsitory
                                )
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()){
                $this->id_role = Auth::user()->tipe;
                if(!$this->id_role=="admin")
                    return redirect('login')->with('status', [
                        'enabled'       => true,
                        'type'          => 'danger',
                        'content'       => 'Tidak boleh mengakses'
                ]);
            }
            return $next($request);
        });

        $this->rekening = $rekening;
        $this->user = $user;
        $this->tabungan = $tabungan;
        $this->maal = $maal;
        $this->wakaf = $wakaf;
        $this->pengajuan = $pengajuan;
        $this->informationRepository = $informationRepository;
        $this->donasiReporsitory = $donasiReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitories;
        $this->pengajuanReporsitory = $pengajuanReporsitory;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(){
        $kegiatan = Maal::paginate(8);
        $kegiatan_wakaf = Wakaf::paginate(8);
        return view('maal',[
            'bank_bmt' => $this->tabunganReporsitory->getRekening("BANK"),
            "kegiatan"  => $kegiatan,
            'kegiatan_wakaf' => $kegiatan_wakaf,
            // 'riwayat_zis' => $this->donasiReporsitory->getUserDonasi(Auth::user()->id, "zis"),
            // 'riwayat_wakaf' => $this->donasiReporsitory->getUserDonasi(Auth::user()->id, "wakaf"),
            'dropdown6' => $this->informationRepository->getDdBank(),
        ]);
    }
    public function konfirmasi_donasi(Request $request){
        $confirmDonasi = $this->donasiReporsitory->confirmDonasi($request); 
        if($confirmDonasi['type'] == 'success') {
            $pengajuan = Pengajuan::where('id', $request->id_)->update([ 'status' => 'Sudah Dikonfirmasi ', 'teller' => Auth::user()->id]);
            if($pengajuan)
                return redirect()
                    ->back()
                    ->withSuccess(sprintf($confirmDonasi['message']));
            else{
                return redirect()
                    ->back()
                    ->withInput()->with('message', 'Donasi kegitan Maal gagal dilakukan!.');
            }
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $confirmDonasi['message']);

        }
    }
    public function index(){
//        if(Auth::user()->tipe=="admin")
//        {
            $data = $this->informationRepository->getAllMaal();
//        }
//        elseif(Auth::user()->tipe=="teller")
//        {
//            $data = $this->informationRepository->getAllMaalTell();
//        }
        $notification = $this->pengajuanReporsitory->getNotification();
        return view('admin.maal.maal',[
            'notification' => $notification,
            'notification_count' => count($notification),
            'data' =>$data,
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown' => $this->informationRepository->getDdBMT(),
            'dropdownPencairan' => $this->informationRepository->getDdPencairan()
        ]);
    }
    public function transaksi_maal(){
        $notification = $this->pengajuanReporsitory->getNotification();
        $saldo = BMT::where('id',335)->select('saldo')->first();
        return view('admin.maal.transaksi',[
            'notification' => $notification,
            'notification_count' => count($notification),
            'data' =>$this->informationRepository->getAllPenyimpananMaal(),
            'saldo_terkumpul' => $saldo
        ]);
    }
    public function detail_maal(Request $request){
        $notification = $this->pengajuanReporsitory->getNotification();
        return view('admin.maal.detail_maal',[
            'notification' => $notification,
            'notification_count' => count($notification),
            'data' =>$this->informationRepository->getAllTransaksiMaal($request)
        ]);
    }

    public function add_kegiatan(Request $request){
        $kegiatan = $this->donasiReporsitory->createNewKegiatan($request);
        if($kegiatan['type'] == "success")
            return redirect()
                ->back()
                ->withSuccess(sprintf($kegiatan['message']));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $kegiatan['message']);
        }
    }

    public function edit_kegiatan(Request $request){
        if($this->informationRepository->editKegiatan($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Kegiatan Maal berhasil diedit!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Kegiatan Maal gagal diedit!.');
        }
    }
    public function delete_kegiatan(Request $request){
        if($this->informationRepository->deleteKegiatan($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Kegiatan Maal berhasil dihapus!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Kegiatan Maal gagal dihapus!.');
        }
    }

    public function pencairan(Request $request){
        if (floatval(str_replace(',',"",$request->jumlahPencairan)) > floatval(str_replace(',',"",$request->danaTersisa)))
        {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Jumlah pencairan tidak boleh melebihi dana yang tersisa!');
        }

        if($this->informationRepository->pencairanDonasi($request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Dana Donasi Maal berhasil dicairkan!'));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Dana Donasi Maal gagal dicairkan!');
        }

    }

    public function pencairanZis(Request $request){
        if (floatval(str_replace(',',"",$request->jumlahPencairan)) > floatval(str_replace(',',"",$request->danaTersisa)))
        {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Jumlah pencairan tidak boleh melebihi dana yang tersisa!');
        }

        if($this->informationRepository->pencairanDonasiZis($request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Dana Donasi Zis berhasil dicairkan!'));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Dana Donasi Zis gagal dicairkan!');
        }

    }
}
