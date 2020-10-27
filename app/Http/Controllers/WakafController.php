<?php

namespace App\Http\Controllers;

use App\Maal;
use App\Wakaf;
use App\Pengajuan;
use App\Repositories\InformationRepository;
use App\Repositories\PengajuanReporsitories;
use App\Tabungan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Repositories\DonasiReporsitories;

class WakafController extends Controller
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
                                Tabungan $tabungan,
                                Pengajuan $pengajuan,
                                InformationRepository $informationRepository,
                                DonasiReporsitories $donasiReporsitory,
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
        $this->pengajuan = $pengajuan;
        $this->informationRepository = $informationRepository;
        $this->donasiReporsitory = $donasiReporsitory;
        $this->pengajuanReporsitory = $pengajuanReporsitory;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(){
        return view('wakaf',[
            'data' =>$this->informationRepository->getAllMaal(),
        ]);
    }


    public function konfirmasi_donasi_wakaf(Request $request){
        $confirmDonasi = $this->donasiReporsitory->confirmDonasiWakaf($request);
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
            $data = $this->informationRepository->getAllWakaf();
//        elseif(Auth::user()->tipe=="teller")
//            $data = $this->informationRepository->getAllWakafTell();
        $notification = $this->pengajuanReporsitory->getNotification();

        return view('admin.wakaf.wakaf',[
            'notification' => $notification,
            'notification_count' => count($notification),
            'data' =>$data,
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown' => $this->informationRepository->getDdBMT(),
            'dropdownPencairan' => $this->informationRepository->getDdPencairan()
        ]);
    }
    public function transaksi_wakaf(){
        $notification = $this->pengajuanReporsitory->getNotification();
        return view('admin.wakaf.transaksi',[
            'notification' => $notification,
            'notification_count' => count($notification),
            'riwayat_wakaf' =>$this->informationRepository->getAllPenyimpananWakaf(),
        ]);
    }
    public function detail_wakaf(Request $request){
        $notification = $this->pengajuanReporsitory->getNotification();
        return view('admin.wakaf.detail_wakaf',[
            'notification' => $notification,
            'notification_count' => count($notification),
            'data' =>$this->informationRepository->getAllTransaksiWakaf($request)
        ]);
    }

    public function add_kegiatan(Request $request){
        $kegiatan = $this->donasiReporsitory->createNewKegiatanWakaf($request);
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
        if($this->informationRepository->editKegiatanWakaf($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Kegiatan Wakaf berhasil diedit!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Kegiatan Wakaf gagal diedit!.');
        }
    }
    public function delete_kegiatan(Request $request){
        if($this->informationRepository->deleteKegiatanWakaf($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Kegiatan Wakaf berhasil dihapus!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Kegiatan Wakaf gagal dihapus!.');
        }
    }

    public function pencairan(Request $request){
        if (floatval(str_replace(',',"",$request->jumlahPencairan)) > floatval(str_replace(',',"",$request->danaTersisa)))
        {
            return redirect()
                ->back()
                ->withInput()->with('message', 'Jumlah pencairan tidak boleh melebihi dana yang terkumpul!');
        }

        if($this->informationRepository->pencairanDonasiWakaf($request)) {
            return redirect()
                ->back()
                ->withSuccess(sprintf('Dana Donasi Wakaf berhasil dicairkan!'));
        }
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Dana Donasi Wakaf gagal dicairkan!');
        }

    }
}
