<?php

namespace App\Http\Controllers;

use App\BMT;
use App\PenyimpananBMT;
use App\PenyimpananRekening;
use App\PenyimpananMaal;
use App\PenyimpananWakaf;
use App\Repositories\InformationRepository;
use App\Repositories\RekeningReporsitories;
use App\Repositories\PembiayaanReporsitory;
use App\Repositories\DistribusiPendapatanReporsitories;
use App\Repositories\SHUTahunanRepositories;
use App\Repositories\PengajuanReporsitories;
use App\Tabungan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LaporanController extends Controller
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
                                RekeningReporsitories $rekeningReporsitory,
                                PembiayaanReporsitory $pembiayaanReporsitory,
                                InformationRepository $informationRepository,
                                DistribusiPendapatanReporsitories $distribusiPendapatanReporsitory,
                                SHUTahunanRepositories $shuTahunanRepository,
                                PengajuanReporsitories $pengajuanReporsitory
                                )
    {
        $this->middleware(function ($request, $next) {
            $this->id_role = Auth::user()->tipe;
            if(!$this->id_role=="admin")
                return redirect('login')->with('status', [
                    'enabled'       => true,
                    'type'          => 'danger',
                    'content'       => 'Tidak boleh mengakses'
                ]);
            return $next($request);
        });

        $this->rekening = $rekening;
        $this->user = $user;
        $this->tabungan = $tabungan;
        $this->informationRepository = $informationRepository;
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->pembiayaanReporsitory = $pembiayaanReporsitory;
        $this->distribusiPendapatanReporsitory = $distribusiPendapatanReporsitory;
        $this->shuTahunanRepository = $shuTahunanRepository;
        $this->pengajuanReporsitory = $pengajuanReporsitory;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

//    Mock Up

    public function pengajuan_pem(){
        $home = new HomeController;
        $date = $home->date_query(0);
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanPBY($date);
        return view('admin.laporan.pembiayaan',[
            'datasaldoPem' => $this->informationRepository->getAllPem(),
            'datasaldoPem2' => $this->informationRepository->getAllPemView(),
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
        ]);
    }

    public function realisasi_pem(Request $request) {
        // $dropdown = $this->informationRepository->getDdTab();
        // $dropdown2 = $this->informationRepository->getDdDep();
        // $dropdown3 = $this->informationRepository->getDdPem();
        // $data = $this->informationRepository->getAllpengajuanReal();
        $date = array("start" => $request->start, "end" => $request->end);
        if(isset($request->start) && isset($request->end)) {
            $data = $this->pembiayaanReporsitory->getPembiayaan($date);
        }
        else {
            $data = $this->pembiayaanReporsitory->getPembiayaan();
        }
        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.pembiayaan',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'data' => $data
        ]);
    }
    public function daftar_kolektibilitas(){
        $dropdown = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllPemNasabahKolek();
        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.daftar_kolektibilitas',[
            'notification' => $notification,
            'notification_count' =>count($notification),
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

    public function rekap_jurnal(){
        return view('admin.laporan.rekapitulasi',[
            'data' => $this->informationRepository->getAllJurnal(),
        ]);
    }
    
    public function kas_harian(Request $request) {
        if(isset($request->start))
        {
            if(isset($request->id))
            {
                $data = $this->rekeningReporsitory->getKasHarian($request->id, Carbon::parse($request->start));
            }
            else
            {
                $data = $this->rekeningReporsitory->getKasHarian(Auth::user()->id, Carbon::parse($request->start));
            }
        }
        else
        {
            if(isset($request->id))
            {
                $data = $this->rekeningReporsitory->getKasHarian($request->id, Carbon::now());
            }
            else
            {
                $data = $this->rekeningReporsitory->getKasHarian(Auth::user()->id, Carbon::now());
            }
        }

        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.kas_harian',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'data' => $data
        ]);
    }

    public function pendapatan(){
        $data = $this->informationRepository->getPendapatan();
        $sum = null;
        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));
        foreach ($data as $dt){
            $rekening = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            if(!isset($rekening)){
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                if($rek->save());
            }else {
                $rekening->saldo = $dt->saldo;
                if($rekening->save());
            }
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $sum += floatval($dt->saldo);
        }
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        return view('admin.laporan.pendapatan',[
            'data' => $data,
            'sum' =>$sum,
            'periode' =>$periode,
        ]);
    }
    public function periode_pendapatan(Request $request){
        $data = $this->informationRepository->getPendapatan();
        $sum = null;
        foreach ($data as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$request->periode)->first();
            $dt->saldo=$saldo->saldo;
            $sum += floatval($dt->saldo);
        }
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        return view('admin.laporan.pendapatan',[
            'data' => $data,
            'sum' =>$sum,
            'periode' =>$periode,
        ]);
    }
    public function getquitas($date){
        $periode = PenyimpananRekening::select('periode')->distinct()->where('periode',"<",$date)
            ->orderBy('periode',"DESC")->pluck('periode');
        if(count($periode)==0) return 0;
        $data = $this->informationRepository->getModal();
        $data2 = $this->informationRepository->getRekModal();
        $rekening = PenyimpananRekening::whereIn('id_rekening',$data2)->where('periode',$periode[0])->get()->pluck('saldo')->toArray();
        return (array_sum($rekening));

    }
    public function quitas(){
        $data = $this->informationRepository->getModal();
        $sum = null;
        $home = new HomeController();
        $date_now = $home->MonthShifter(+1)->format(('Y-m'));
        $date_prev = $home->MonthShifter(-1)->format(('Y-m-t'));
        $i=0;
        foreach ($data as $dt){
            $bmt = PenyimpananBMT::select('transaksi')->where('id_bmt',$dt['id'])
                ->where('penyimpanan_bmt.created_at',">",$date_prev)
                ->where('penyimpanan_bmt.created_at',"<",$date_now."-01")
                ->get();
            if(count($bmt)==0){
                $data[$i]['saldo'] =0;
            }
            else{
                $total=0;
                foreach($bmt as $bm){
                    $total+=json_decode($bm->transaksi,true)['jumlah'];
                }
                $data[$i]['saldo'] =$total;
            }

            $data[$i]['point'] = substr_count($dt['id_bmt'], '.');
            $sum += floatval($data[$i]['saldo']);
            $i++;

        }
        $awal = $this->getquitas($date_now."-01");
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.quitas',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'awal' => $awal,
            'data' => $data,
            'sum' =>$sum,
            'periode'  => $periode
        ]);
    }
    public function periode_quitas(Request $request){
        $data = $this->informationRepository->getModal();
        $sum = null;
        $home = new HomeController();
        $date = $home->date_query(substr($request->periode,4,2));
        $i=0;
        foreach ($data as $dt){
            $bmt = PenyimpananBMT::select('transaksi')->where('id_bmt',$dt['id'])
                ->where('penyimpanan_bmt.created_at',">",$date['prev'])
                ->where('penyimpanan_bmt.created_at',"<",$date['now'])
                ->get();

            if(count($bmt)==0){
                $data[$i]['saldo'] =0;
            }
            else{
                $total=0;
                foreach($bmt as $bm){
                    $total+=json_decode($bm->transaksi,true)['jumlah'];
                }
                $data[$i]['saldo'] =$total;
            }

            $data[$i]['point'] = substr_count($dt['id_bmt'], '.');
            $sum += floatval($data[$i]['saldo']);
            $i++;

        }
        $awal = $this->getquitas($request->periode);
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        return view('admin.laporan.quitas',[
            'awal' => $awal,
            'data' => $data,
            'sum' =>$sum,
            'periode'  => $periode
        ]);
    }

    public function laba_rugi(){
        $laba = $this->informationRepository->getPendapatan();
        $rugi = $this->informationRepository->getRugi();
        $sum_laba = $sum_rugi=null;
        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));
        foreach ($laba as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $rekening = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            if(!isset($rekening)){
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                if($rek->save());
            }else{
                $rekening->saldo = $dt->saldo;
                if($rekening->save());
            }
            $sum_laba += floatval($dt->saldo);
        }
        foreach ($rugi as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $rekening = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            if(!isset($rekening)){
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                if($rek->save());
            }else {
                $rekening->saldo = $dt->saldo;
                if($rekening->save());
            }
            $sum_rugi += floatval($dt->saldo);
        }

        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.laba_rugi',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'data' => $laba,
            'data2' => $rugi,
            'laba' =>$sum_laba,
            'rugi' =>$sum_rugi,
            'periode' =>$periode,
        ]);
    }

    public function periode_labarugi($date){
        $laba = $this->informationRepository->getPendapatan();
        $rugi = $this->informationRepository->getRugi();
        $sum_laba = $sum_rugi=null;
        try{
            foreach ($laba as $dt){
                $dt['point'] = substr_count($dt->id_bmt, '.');
                $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
                $dt->saldo=$saldo->saldo;
                $sum_laba += floatval($dt->saldo);
            }
            foreach ($rugi as $dt){
                $dt['point'] = substr_count($dt->id_bmt, '.');
                $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
                $dt->saldo=$saldo->saldo;
                $sum_rugi += floatval($dt->saldo);
            }
            $data['laba'] = $laba;
            $data['rugi'] = $rugi;
            $data['sum_laba'] = $sum_laba;
            $data['sum_rugi'] = $sum_rugi;
            return $data;
        }
        catch (\Exception $e) {
            return false;
        }
    }
    public function periode_laba_rugi(Request $request){
        $data = $this->periode_labarugi($request->periode);
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        return view('admin.laporan.laba_rugi',[
            'data' => $data['laba'],
            'data2' => $data['rugi'],
            'laba' =>$data['sum_laba'],
            'rugi' =>$data['sum_rugi'],
            'periode' =>$periode,
        ]);
    }

    public function periode_aktiva(Request $request){
        $data = $this->informationRepository->getAktiva();
        $sum = null;
        foreach ($data as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$request->periode)->first();
            $dt->saldo=$saldo->saldo;
            $sum += floatval($dt->saldo);
        }
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        return view('admin.laporan.aktiva',[
            'data' => $data,
            'sum' =>$sum,
            'periode'  => $periode
        ]);
    }
    public function aktiva(){
        $data = $this->informationRepository->getAktiva();
        $sum = null;
        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));
        foreach ($data as $dt){
            $rekening = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            if(!isset($rekening)){
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                if($rek->save());
            }else {
                $rekening->saldo = $dt->saldo;
                if($rekening->save());
            }
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $sum += floatval($dt->saldo);
        }

        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        return view('admin.laporan.aktiva',[
            'data' => $data,
            'sum' =>$sum,
            'periode'  => $periode
        ]);
    }
    public function neraca(){
        $home = new HomeController;
        $date = $home->MonthShifter(0)->format(('Ym'));
        $data = $this->informationRepository->getAktiva();
        $data2 = $this->informationRepository->getPasiva();
        $aktiva = $pasiva=null;
        foreach ($data as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            // $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            // if(empty($saldo)) {
            //     $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->orderBy('id','desc')->first();
            // }
            $dt['saldo']=$dt->saldo;
            $aktiva += floatval($dt['saldo']);
        }
        $data2= collect ($data2);
        $data2_array = array();
        foreach($data2 as $dt2){
            $dt2['point'] = substr_count($dt2['id_bmt'], '.');
            // $saldo2 = PenyimpananRekening::where('id_rekening',$dt2['id_rekening'])->where('periode',$date)->first();
            // if(empty($saldo2))
            //     $saldo2 = PenyimpananRekening::where('id_rekening',$dt2['id_rekening'])->orderBy('id','desc')->first();
            // $dt2['saldo']=$saldo2['saldo'];
            $dt2['saldo']=$dt2['saldo'];
            array_push($data2_array,$dt2);
            $pasiva += floatval($dt2['saldo']);
        }
        $data2 = collect($data2_array);
        // $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $str = substr($date,0,4)."/".substr($date,4,2)."/01";
        $time_input = date_create($str);

        // return response()->json($data2);
        $statusNeraca = true;
        if($aktiva != $pasiva)
        {
            $statusNeraca = false;
        }
        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.neraca',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'data' => $data,
            'data2' => $data2,
            'aktiva' =>$aktiva,
            'pasiva' =>$pasiva,
            'statusNeraca' => $statusNeraca,
            // 'periode'  => $periode,
            'bulan'=> date_format($time_input,"F Y")
        ]);
    }
    
    public function periode_neraca(Request $request){

        $data = $this->informationRepository->getAktiva();
        $date = $request->periode;

        $data2 = $this->informationRepository->getPasiva();
        $aktiva = $pasiva=null;
        foreach ($data as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            $dt['saldo']=$saldo['saldo'];
            $aktiva += floatval($dt['saldo']);
        }
        $data2= collect ($data2);
        $data2_array = array();
        foreach($data2 as $dt2){
            $dt2['point'] = substr_count($dt2['id_bmt'], '.');
            $saldo2 = PenyimpananRekening::where('id_rekening',$dt2['id_rekening'])->where('periode',$date)->first();
            $dt2['saldo']=$saldo2['saldo'];
            array_push($data2_array,$dt2);
            $pasiva += floatval($dt2['saldo']);
        }
        $data2 = collect($data2_array);
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $str = substr($request->periode,0,4)."/".substr($request->periode,4,2)."/01";
        $time_input = date_create($str);

        return view('admin.laporan.neraca',[
            'data' => $data,
            'data2' => $data2,
            'aktiva' =>$aktiva,
            'pasiva' =>$pasiva,
            'periode'  => $periode,
            'bulan'=> date_format($time_input,"F Y")
        ]);
    }
    public function rekapitulasi_kas(){
        $data = $this->informationRepository->getBukuTeller();
        $data2 = $this->informationRepository->getBukuBank();
        $tunai=$bank=0;
        foreach ($data as $d){
            $tunai +=floatval(json_decode($d->transaksi,true)['jumlah']);
        }
        foreach ($data2 as $d){
            $bank +=floatval(json_decode($d->transaksi,true)['jumlah']);
        }
        return view('admin.laporan.rekapitulasi_kas',[
            'data' => $data,
            'data2' => $data2,
            'tunai' => $tunai,
            'bank' => $bank,
        ]);
    }
    public function buku_besar(){
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.buku_besar',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'data' => null,
            'rekening' => $this->informationRepository->getAllRekeningDetail(),
            'periode' =>$periode
        ]);
    }

    public function rekening_buku(Request $request){
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $data =$this->informationRepository->BukuBesar($request);
        $notification = $this->pengajuanReporsitory->getNotification();
        return view('admin.laporan.buku_besar',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'data' => $data,
            'rekening' => $this->informationRepository->getAllRekeningDetail(),
            'periode' =>$periode
        ]);
    }
    public function rekening_buku_periodik(Request $request){
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $data =$this->informationRepository->BukuBesar_($request);
        $notification = $this->pengajuanReporsitory->getNotification();
        return view('admin.laporan.buku_besar',[
            'data' => $data,
            'rekening' => $this->informationRepository->getAllRekeningDetail(),
            'periode' =>$periode,
            'notification' => $notification,
            'notification_count' =>count($notification)
        ]);
    }
    public function distribusi(){
        $notification = $this->pengajuanReporsitory->getNotification();

        return view('admin.laporan.distribusi',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'data' => $this->distribusiPendapatanReporsitory->getDistribusiData(),
            'data_revenue' => $this->distribusiPendapatanReporsitory->getDistribusiRevenueData(),
            'status' => $this->distribusiPendapatanReporsitory->checkDistribusiPendapatanStatus(),
        ]);
    }

    public function distribusi_pendapatan(Request $request){
        if($this->informationRepository->distribusi_pendapatan($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Distribusi Pendapatan berhasil dilakukan!.'));
        else
            return redirect()
                ->back()
                ->withInput()->with('message', 'Distribusi Pendapatan gagal dilakukan!.');
    }

    public function delete_pendapatan(Request $request){

        if($this->informationRepository->delete_pendapatan($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Distribusi Pendapatan berhasil dihapus!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Distribusi Pendapatan gagal dihapus!.');
        }
    }

    public function shu(){
        $data_shu = $this->shuTahunanRepository->getSHU();
        $data_distribusi = $this->shuTahunanRepository->getDataDistribusiSHU();
        $status_distribusi = $this->shuTahunanRepository->checkStatus();
        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.shu',[
            'notification' => $notification,
            'notification_count' =>count($notification),
            'data_distribusi' => $data_distribusi,
            'data_shu' => $data_shu,
            'status' => $status_distribusi,
        ]);
    }

    public function periode_shu(Request $request){
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        return view('admin.laporan.shu',[
            'data' => $this->informationRepository->periode_shu($request),
            'status' => $this->informationRepository->cekshu(0),
            'periode'  => $periode
        ]);
    }

    public function distribusi_shu(Request $request){
        if($this->informationRepository->distribusi_shu($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Distribusi SHU berhasil dilakukan!.'));
        else
            return redirect()
                ->back()
                ->withInput()->with('message', 'Distribusi SHU gagal dilakukan!.');
    }
    public function delete_shu(Request $request){
        if($this->informationRepository->delete_shu($request))
            return redirect()
                ->back()
                ->withSuccess(sprintf('Distribusi SHU berhasil dihapus!.'));
        else{
            return redirect()
                ->back()
                ->withInput()->with('message', 'Distribusi SHU gagal dihapus!.');
        }
    }

    public function detail_anggota(){
        $data = $this->informationRepository->getAllAnggota();
        return view('admin.laporan.detail_anggota',[
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'dropdown8' => $this->informationRepository->getAllNasabah(),
            'data' => $data,
        ]);
    }


    public function showDetailAnggota(Request $request){
        return view('admin.laporan.user_datadiri',[
            'dropdown7' => $this->informationRepository->getDdTeller(),
            'tab' =>  $this->informationRepository->getAllTabungan(),
            'status' =>  $this->informationRepository->getUsrByKtp($request->noktp),
        ]);
    }
    public function kas_anggota(){
        return view('admin.laporan.kas_anggota');
    }
    public function jatuh_tempo(){
        return view('admin.laporan.jatuh_tempo');
    }
    public function kredit_macet(){
        return view('admin.laporan.kredit_macet');
    }
    public function transaksi_kas(){
        return view('admin.laporan.transaksi_kas');
    }
    public function pinjaman(){
        return view('admin.laporan.pinjaman');
    }
    public function simpanan(){
        return view('admin.laporan.simpanan');
    }
    public function saldo(){
        return view('admin.laporan.saldo');
    }
    public function labarugi(){
        $notification = $this->pengajuanReporsitory->getNotification();
        
        return view('admin.laporan.labarugi', [
            'notification' => $notification,
            'notification_count' =>count($notification)
        ]);
    }
    public function saldo_zis(Request $request) {
        $rekening = $this->rekeningReporsitory->findRekening("nama_rekening", "ZAKAT");
        $bmt_rekening = BMT::where('id_rekening', $rekening->id)->first();
        $data_zis = PenyimpananBMT::where([
            ['id_bmt', $bmt_rekening->id], ['created_at', '>=', Carbon::now()->subDays(30)], ['created_at', '<=', Carbon::now()]
        ])->get();

        if(isset($request->start) && isset($request->end))
        {
            $data_zis = PenyimpananBMT::where([
                ['id_bmt', $bmt_rekening->id], ['created_at', '>=', Carbon::parse($request->start)], ['created_at', '<=', Carbon::parse($request->end)]
            ])->get();
        }
        $saldo_terkumpul = $bmt_rekening->saldo;
        $notification = $this->pengajuanReporsitory->getNotification();
        $notification_count = count($notification);

        $dropdownPencairan =  $this->informationRepository->getDdPencairan();


        return view('admin.laporan.saldo_zis', compact('data_zis', 'saldo_terkumpul', 'notification', 'notification_count', 'dropdownPencairan'));
    }

    public function saldo_donasi(Request $request) {
        $data_donasi = PenyimpananMaal::where([
            ['created_at', '>=', Carbon::now()->subDays(30)], ['created_at', '<=', Carbon::now()]
        ])->get();

        if(isset($request->start) && isset($request->end))
        {
            $data_donasi = PenyimpananMaal::where([
                ['created_at', '>=', Carbon::parse($request->start)], ['created_at', '<=', Carbon::parse($request->end)]
            ])->get();
        }

        $rekening = $this->rekeningReporsitory->findRekening("nama_rekening", "DANA SOSIAL");
        $saldo_terkumpul = BMT::where('id_rekening', $rekening->id)->select('saldo')->first();
        $notification = $this->pengajuanReporsitory->getNotification();
        $notification_count = count($notification);
        $dropdownPencairan = $this->informationRepository->getDdPencairan();
        return view('admin.laporan.saldo_donasi', compact('data_donasi', 'saldo_terkumpul', 'notification', 'notification_count', 'dropdownPencairan'));
    }

    public function saldo_wakaf(Request $request) {
        $data_wakaf = PenyimpananWakaf::where([
            ['created_at', '>=', Carbon::now()->subDays(30)], ['created_at', '<=', Carbon::now()]
        ])->get();

        if(isset($request->start) && isset($request->end))
        {
            $data_wakaf = PenyimpananWakaf::where([
                ['created_at', '>=', Carbon::parse($request->start)], ['created_at', '<=', Carbon::parse($request->end)]
            ])->get();
        }

        $rekening = $this->rekeningReporsitory->findRekening("nama_rekening", "WAKAF UANG");
        $saldo_terkumpul = BMT::where('id_rekening', $rekening->id)->select('saldo')->first();
        $notification = $this->pengajuanReporsitory->getNotification();
        $notification_count = count($notification);
        $dropdownPencairan = $this->informationRepository->getDdPencairan();
        return view('admin.laporan.saldo_wakaf', compact('data_wakaf', 'saldo_terkumpul', 'notification', 'notification_count', 'dropdownPencairan'));
//        $rekening = $this->rekeningReporsitory->findRekening("nama_rekening", "WAKAF UANG");
//        $bmt_rekening = BMT::where('id_rekening', $rekening->id)->first();
//        $data_wakaf = PenyimpananWakaf::where([
//            ['id_bmt', $bmt_rekening->id], ['created_at', '>=', Carbon::now()->subDays(30)], ['created_at', '<=', Carbon::now()]])->get();
//
//        $saldo_terkumpul = BMT::where('id_rekening', $rekening->id)->select('saldo')->first();
//
//        if(isset($request->start) && isset($request->end))
//        {
//            $data_wakaf = PenyimpananWakaf::where([
//                ['id_bmt', $bmt_rekening->id], ['created_at', '>=', Carbon::parse($request->start)], ['created_at', '<=', Carbon::parse($request->end)]])->get();
//        }
//
//        $notification = $this->pengajuanReporsitory->getNotification();
//        $notification_count = count($notification);
//
//
//
//        return view('admin.laporan.saldo_wakaf', compact('data_wakaf', 'saldo_terkumpul', 'notification', 'notification_count'));
    }

    /** 
     * Proses akhir bulan View
     * @return VIEW
    */
    public function proses_akhir_bulan(Request $request)
    {
        $data = $this->distribusiPendapatanReporsitory->getDistribusiHistory($request->date);
        $status = $this->distribusiPendapatanReporsitory->checkDistribusiPendapatanStatus();
        $notification = $this->pengajuanReporsitory->getNotification();
        $notification_count = count($notification);


        //testing
        $dataSaldoTabungan = $this->distribusiPendapatanReporsitory->getRataRataSaldoTabungan();
        $dataSaldoTabunganNet = $this->distribusiPendapatanReporsitory->getRataRataSaldoTabunganNet();
        $dataSaldoDeposito = $this->distribusiPendapatanReporsitory->getRataRataSaldoDeposito();
        $dataSaldoDepositoNet = $this->distribusiPendapatanReporsitory->getRataRataSaldoDepositoNet();


        return view('admin.laporan.proses_akhir_bulan', compact('status', 'data', 'notification', 'notification_count', 'dataSaldoTabungan','dataSaldoTabunganNet', 'dataSaldoDeposito', 'dataSaldoDepositoNet'));
    }

    /** 
     * Action pendistribusian pendapatan
     * @return Response
    */
    public function do_proses_akhir_bulan(Request $request)
    {
        if($request->jenis == "revenue")
        {
            $rekening_shu_berjalan = BMT::where('nama', 'SHU BERJALAN')->first();
            if($rekening_shu_berjalan->saldo <= 0)
            {
                return redirect()
                        ->back()
                        ->withInput()->with('message', 'Pendistribusian tidak bisa dilakukan karena saldo SHU Berjalan bernilai 0 atau lebih kecil.');
            }
            else
            {
                $data = $this->distribusiPendapatanReporsitory->doPendistribusian($request);
                if($data['type'] == "success") {
                    return redirect()
                        ->back()
                        ->withSuccess(sprintf($data['message']));
                }
                else
                {
                    return redirect()
                        ->back()
                        ->withInput()->with('message', $data['message']);
                }
            }
        }
        else
        {
            $data = $this->distribusiPendapatanReporsitory->doPendistribusian($request);
            if($data['type'] == "success") {
                return redirect()
                    ->back()
                    ->withSuccess(sprintf($data['message']));
            }
            else
            {
                return redirect()
                    ->back()
                    ->withInput()->with('message', $data['message']);
            }
        }
    }

    /** 
     * Proses akhir tahun View
     * @return VIEW
    */
    public function proses_akhir_tahun(Request $request)
    {
        $date = isset($request->date) ? $request->date : "";
        $data = $this->shuTahunanRepository->getHistorySHU($date);
        $status = $this->shuTahunanRepository->checkStatus();
        $notification = $this->pengajuanReporsitory->getNotification();
        $notification_count = count($notification);

        return view('admin.laporan.proses_akhir_tahun', compact('status', 'data', 'notification', 'notification_count'));
    }

    /** 
     * do proses pendistribusian shu tahunan
     * @return Response
    */
    public function do_proses_akhir_tahun(Request $request)
    {
        
        $rekening_shu_yang_harus_dibagikan = BMT::where('nama', 'SHU YANG HARUS DIBAGIKAN')->first();
        if($rekening_shu_yang_harus_dibagikan->saldo <= 0)
        {
            return redirect()
                    ->back()
                    ->withInput()->with('message', 'Pendistribusian tidak bisa dilakukan karena saldo SHU Yang Harus Dibagikan bernilan 0 atau lebih kecil.');
        }
        else
        {
            $data = $this->shuTahunanRepository->doPendistribusian($request);
            if($data['type'] == "success") {
                return redirect()
                    ->back()
                    ->withSuccess(sprintf($data['message']));
            }
            else
            {
                return redirect()
                    ->back()
                    ->withInput()->with('message', $data['message']);
            }
        }
    } 
}
