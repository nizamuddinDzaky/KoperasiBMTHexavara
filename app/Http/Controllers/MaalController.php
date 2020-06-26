<?php

namespace App\Http\Controllers;

use App\Maal;
use App\Pengajuan;
use App\Repositories\InformationRepository;
use App\Tabungan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
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
                                Tabungan $tabungan,
                                Pengajuan $pengajuan,
                                InformationRepository $informationRepository,
                                DonasiReporsitories $donasiReporsitory
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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(){
        return view('maal',[
            'data' =>$this->informationRepository->getAllMaal(),
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
        if(Auth::user()->tipe=="admin")
        $data = $this->informationRepository->getAllMaal();
        elseif(Auth::user()->tipe=="teller")
        $data = $this->informationRepository->getAllMaalTell();
        return view('admin.maal.maal',[
            'data' =>$data,
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown' => $this->informationRepository->getDdBMT(),
        ]);
    }
    public function transaksi_maal(){
        return view('admin.maal.transaksi',[
            'data' =>$this->informationRepository->getAllPenyimpananMaal(),
        ]);
    }
    public function detail_maal(Request $request){
        return view('admin.maal.detail_maal',[
            'data' =>$this->informationRepository->getAllTransaksiMaal($request),
        ]);
    }

    public function add_kegiatan(Request $request){
        $store = $this->donasiReporsitory->createNewKegiatan($request);
        if($store['type'] == "success")
            return redirect()
                ->back()
                ->withSuccess(sprintf($store['message']));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', $store['message']);
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
}
