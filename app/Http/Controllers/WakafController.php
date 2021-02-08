<?php

namespace App\Http\Controllers;

use App\Maal;
use App\PenyimpananWakaf;
use App\Repositories\HelperRepositories;
use App\Wakaf;
use App\Pengajuan;
use App\Repositories\InformationRepository;
use App\Repositories\PengajuanReporsitories;
use App\Tabungan;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use App\BMT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Repositories\DonasiReporsitories;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

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
                                PengajuanReporsitories $pengajuanReporsitory,
                                HelperRepositories $helperRepositories
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
        $this->helperRepository = $helperRepositories;
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
        $saldo = BMT::where('id',336)->select('saldo')->first();
        return view('admin.wakaf.transaksi',[
            'notification' => $notification,
            'notification_count' => count($notification),
            'riwayat_wakaf' =>$this->informationRepository->getAllPenyimpananWakaf(),
            'saldo_terkumpul' => $saldo
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

    public function cetak_donasi_wakaf($id){

        $data = PenyimpananWakaf::select('penyimpanan_wakaf.*','users.nama','wakaf.nama_kegiatan')
            ->where('penyimpanan_wakaf.id',$id)
            ->leftjoin('users','users.id','id_donatur')
            ->leftjoin('wakaf','wakaf.id','penyimpanan_wakaf.id_wakaf')
            ->first();

        if ($data->nama == "Umum")
        {
            $alamat = "";
        }
        else{
            $alamat = User::where('id', $data->id_donatur)->select('alamat')->first();
            $alamat = $alamat->alamat;
        }

        $nameForFile = preg_replace('/[^A-Za-z0-9_\.-]/', ' ',json_decode($data->transaksi)->nama);
        $path = public_path('template/tanda_terima_wakaf.docx');
        $template = new TemplateProcessor($path);
        Settings::setOutputEscapingEnabled(true);
        $template->setValue('nama',strtoupper(json_decode($data->transaksi)->nama));
        $template->setValue('tanggal',Carbon::now()->format("d") . " " . $this->helperRepository->getMonthName() . " " . Carbon::now()->format("Y"));
        $template->setValue('jumlah',number_format(json_decode($data->transaksi)->jumlah,2));
        $template->setValue('alamat',$alamat);
        $template->setValue('tujuan',json_decode($data->transaksi)->untuk_rekening);
        $filename = "tandaterima_wakaf_uang - " .$nameForFile.".docx";
        $template->saveAs('storage/docx/'.$filename);
        $headers = array(
            'Content-Type: application/docx',
            'Cache-Control: must-revalidate, post- check=0, pre-check=0',
            'Content-disposition: inline',
        );

        $location = public_path('storage/docx/' . $filename);

        return response()->download($location, "tandaterima_wakaf_uang - " .$nameForFile.".docx", $headers);
    }
}
