<?php

namespace App\Http\Controllers;

use App\BMT;
use App\PenyimpananBMT;
use App\PenyimpananRekening;
use App\Repositories\InformationRepository;
use App\Tabungan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rekening;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
                                InformationRepository $informationRepository)
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
    public function realisasi_pem(){
        $dropdown = $this->informationRepository->getDdTab();
        $dropdown2 = $this->informationRepository->getDdDep();
        $dropdown3 = $this->informationRepository->getDdPem();
        $data = $this->informationRepository->getAllpengajuanReal();
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
    public function daftar_kolektibilitas(){
        $dropdown = $this->informationRepository->getDd();
        $data = $this->informationRepository->getAllPemNasabahKolek();
        return view('admin.laporan.daftar_kolektibilitas',[
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
    public function kas_harian(){
        $home = new HomeController();
        $date = $home->MonthShifter(-1)->format(('Ym'));
        $date_now = $home->MonthShifter(+1)->format(('Y-m'));
        $date_prev = $home->MonthShifter(-1)->format(('Y-m-t'));
        $periode = PenyimpananRekening::select('periode')->distinct()->orderBy('periode','DESC')->pluck('periode');
        $saldo=$periode_=0;
        $date = $home->date_query(substr($periode_,4,2));
        $date=(substr($date['now'],5,2));
        $data = $this->informationRepository->getKasHarianUpdate($date);

        $plus=$min=0;$i=0;
        if(Auth::user()->tipe=="admin") {
            $id_rek=Rekening::where('katagori_rekening',"TELLER")->get()->pluck('id')->toArray();
//            $id_rek=BMT::whereIn('id_rekening',$id_rek)->get()->pluck('id')->toArray();
            foreach ($periode as $p) {
                // $saldo = PenyimpananRekening::whereIn('id_rekening', $id_rek)->where('periode', $p)->get()->pluck('saldo')->toArray();
                $rekening = PenyimpananRekening::select('penyimpanan_rekening.id_rekening','saldo','nama_rekening as teller')
                    ->join('rekening','rekening.id','penyimpanan_rekening.id_rekening')
                    ->whereIn('penyimpanan_rekening.id_rekening', $id_rek)->where('periode', $p)->get()->toArray();
                if (isset($rekening)) {
                    $periode_ = $p;
                    $data =$rekening;
                    break;
                }
            }
//
//            foreach ($id_rek as $rek) {
//                $data = $this->informationRepository->getKasHarianUpdate($date);
//                foreach ($periode as $p) {
//                    $rekening = PenyimpananRekening::where('id_rekening', $rek)->where('periode', $p)->get();
//                    if (isset($rekening)) {
//                        $periode_ = $p;
//                        break;
//                    }
//                }
//                $rekening = PenyimpananRekening::where('id_rekening', $rek)->where('periode', $p)->get();
//                $saldo=json_decode($data[0]['transaksi'],true)['saldo_awal'];
//                if(isset($rekening)){
//                    $saldo_ = BMT::where('id_rekening', $rek)->first()['saldo'];
//                    $saldo+=$saldo_;
//                }
//            }
            // $saldo = array_sum($saldo);
            // dd($data);
            $data=array();
            $id_rek=BMT::whereIn('id_rekening',$id_rek)->get()->pluck('id')->toArray();
            foreach ($id_rek as $rek) {
                $s = PenyimpananBMT::where('id_bmt', $rek)
                    ->where('penyimpanan_bmt.created_at',">",$date_prev)
                    ->where('penyimpanan_bmt.created_at',"<",$date_now."-01")
                    ->orderBy('id','DESC')
                    ->get()->pluck('transaksi')->toArray();
                $rekening = BMT::select('rekening.id','saldo','nama_rekening as teller')
                    ->join('rekening','rekening.id','bmt.id_rekening')
                    ->where('bmt.id', $rek)->first()->toArray();
                if(isset($s)){
                    $rekening['saldo'];
                    $saldo+=$rekening['saldo'];
                }else{
                    $rekening['saldo'] =json_decode($s[0],true)['saldo_akhir'];
                    $saldo+=json_decode($s[0],true)['saldo_akhir'];
                }
                array_push($data,$rekening);

            }
        }
        else {
            foreach ($periode as $p) {
                $rekening = PenyimpananRekening::where('id_rekening', Auth::user()->id)->where('periode', $p)->first();
                if (isset($rekening)) {
                    $periode_ = $p;
                    break;
                }
            }

            $saldo=json_decode($data[0]['transaksi'],true)['saldo_awal'];

            foreach ($data as $dt){
                if(json_decode($dt['transaksi'],true)['jumlah']>0)
                    $plus+=json_decode($dt['transaksi'],true)['jumlah'];

                else $min+=json_decode($dt['transaksi'],true)['jumlah'];
                if($i==0)$data[$i]['total']=$saldo+json_decode($dt['transaksi'],true)['jumlah'];
                else $data[$i]['total']=$data[$i-1]['total']+json_decode($dt['transaksi'],true)['jumlah'];
                $i++;
            }

        }

        return view('admin.laporan.kas_harian',[
            'data' => $data,
            'saldo' => $saldo,
            'plus' => $plus,
            'min' => $min,
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
        return view('admin.laporan.quitas',[
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
        return view('admin.laporan.laba_rugi',[
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
        $str = substr($date,0,4)."/".substr($date,4,2)."/01";
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

        return view('admin.laporan.buku_besar',[
            'data' => null,
            'rekening' => $this->informationRepository->getAllRekeningDetail(),
            'periode' =>$periode
        ]);
    }

    public function rekening_buku(Request $request){
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $data =$this->informationRepository->BukuBesar($request);
        return view('admin.laporan.buku_besar',[
            'data' => $data,
            'rekening' => $this->informationRepository->getAllRekeningDetail(),
            'periode' =>$periode
        ]);
    }
    public function rekening_buku_periodik(Request $request){
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $data =$this->informationRepository->BukuBesar_($request);
        return view('admin.laporan.buku_besar',[
            'data' => $data,
            'rekening' => $this->informationRepository->getAllRekeningDetail(),
            'periode' =>$periode
        ]);
    }
    public function distribusi(){

        return view('admin.laporan.distribusi',[
            'data' => $this->informationRepository->distribusi(),
            'status' => $this->informationRepository->cekdistribusi(0),
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
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        return view('admin.laporan.shu',[
            'data' => $this->informationRepository->shu(),
            'status' => $this->informationRepository->cekshu(0),
            'periode'  => $periode
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
        return view('admin.laporan.labarugi');
    }
}
