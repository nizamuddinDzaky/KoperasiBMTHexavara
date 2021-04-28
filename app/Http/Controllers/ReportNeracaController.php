<?php

namespace App\Http\Controllers;
use App\Repositories\InformationRepository;
use App\PenyimpananRekening;
use Illuminate\Http\Request;
use Carbon\Carbon;
class ReportNeracaController extends Controller
{

    public function __construct(
                                InformationRepository $informationRepository
                                )
    {
        $this->informationRepository = $informationRepository;
        
    }


    public function get_all_neraca()
    {
        $home = new HomeController;
        $date = $home->MonthShifter(0)->format(('Ym'));
        $data_aktiva = $this->informationRepository->getAktiva();
        $data_pasiva = $this->informationRepository->getPasiva();
        $data_pasiva= collect ($data_pasiva);
        $aktiva = $pasiva=null;

        foreach ($data_aktiva as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $dt['saldo']=$dt->saldo;
            $aktiva += floatval($dt['saldo']);
        }
        $data_pasiva_array = array();
        foreach($data_pasiva as $dt2){
            $dt2['point'] = substr_count($dt2['id_bmt'], '.');
            $dt2['saldo']=$dt2['saldo'];
            array_push($data_pasiva_array,$dt2);
            $pasiva += floatval($dt2['saldo']);
        }
        $data_pasiva = collect($data_pasiva_array);

        $statusNeraca = true;

        if (abs($aktiva-$pasiva) > 0.00001) {
            $statusNeraca =false;
        }
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $str = substr($date,0,4)."/".substr($date,4,2)."/01";
        $time_input = date_create($str);
        
        return response()->json([
            'data' => [
                'periode' => $periode,
                'aktiva' =>$aktiva,
                'pasiva' =>$pasiva,
                'data_aktiva' => $data_aktiva,
                'data_pasiva' => $data_pasiva,
                'statusNeraca' => $statusNeraca,
                'bulan' => date_format($time_input,"F Y")
            ]
        ], 200);
    }

    public function get_all_neraca_by_periode(Request $request)
    {
        $date = $request->tahun.$request->bulan;
        $data_aktiva = $this->informationRepository->getAktiva();
        $data_pasiva = $this->informationRepository->getPasiva();
        $data_pasiva= collect ($data_pasiva);
        $aktiva = $pasiva=null;

        foreach ($data_aktiva as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            if(!$saldo){
                continue;
            }
            $dt['saldo']=$saldo['saldo'];
            $aktiva += floatval($dt['saldo']);
        }

        $data_pasiva_array = array();
        foreach($data_pasiva as $dt2){
            $dt2['point'] = substr_count($dt2['id_bmt'], '.');
            $saldo2 = PenyimpananRekening::where('id_rekening',$dt2['id_rekening'])->where('periode',$date)->first();
            if(!$saldo2){
                continue;
            }
            $dt2['saldo']=$saldo2['saldo'];
            $dt2['saldo']=$dt2['saldo'];
            array_push($data_pasiva_array,$dt2);
            $pasiva += floatval($dt2['saldo']);
        }
        $data_pasiva = collect($data_pasiva_array);

        $statusNeraca = true;

        if (abs($aktiva-$pasiva) > 0.00001) {
            $statusNeraca =false;
        }

        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $time = Carbon::createFromDate(substr($date,0,4), substr($date,4,6), 1)->format('F Y');

        return response()->json([
            'data' => [
                'periode' => $periode,
                'aktiva' =>$aktiva,
                'pasiva' =>$pasiva,
                'data_aktiva' => $data_aktiva,
                'data_pasiva' => $data_pasiva,
                'statusNeraca' => $statusNeraca,
                'bulan' => $time
            ]
        ], 200);
    }

    public function laba_rugi(){
        $laba = $this->informationRepository->getPendapatan();
        $rugi = $this->informationRepository->getRugi();
        $sum_laba = $sum_rugi=null;
        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));
        foreach ($laba as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $sum_laba += floatval($dt->saldo);
        }
        foreach ($rugi as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            $sum_rugi += floatval($dt->saldo);
        }
        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        
        $str = substr($date,0,4)."/".substr($date,4,2)."/01";
        $time_input = date_create($str);
        return response()->json([
            'data'=>[
                'data_laba' => $laba,
                'data_rugi' => $rugi,
                'laba' =>$sum_laba,
                'rugi' =>$sum_rugi,
                'periode' =>$periode,
                'bulan' => date_format($time_input,"F Y")
            ]
        ]);
    }

    public function laba_rugi_by_periode(Request $request)
    {
        $date = $request->tahun.$request->bulan;
        $laba = $this->informationRepository->getPendapatan();
        $rugi = $this->informationRepository->getRugi();
        $sum_laba = $sum_rugi=null;

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

        $periode = PenyimpananRekening::select('periode')->distinct()->pluck('periode');
        $str = substr($date,0,4)."/".substr($date,4,2)."/01";
        $time_input = date_create($str);

        return response()->json([
            'data'=>[
                'data_laba' => $laba,
                'data_rugi' => $rugi,
                'laba' =>$sum_laba,
                'rugi' =>$sum_rugi,
                'periode' =>$periode,
                'bulan' => date_format($time_input,"F Y")
            ]
        ]);
    }
}
