<?php

namespace App\Http\Controllers;
use App\Repositories\InformationRepository;
use Illuminate\Http\Request;

class ReportNeracaController extends Controller
{

    public function __construct(
                                InformationRepository $informationRepository
                                )
    {
        $this->informationRepository = $informationRepository;
        
    }


    public function get_neraca()
    {
        $data_aktiva = $this->informationRepository->getAktiva();
        $data_pasiva = $this->informationRepository->getPasiva();
        $data_pasiva= collect ($data_pasiva);
        $aktiva = $pasiva=null;

        foreach ($data_aktiva as $dt){
            $dt['point'] = substr_count($dt->id_bmt, '.');
            // $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->where('periode',$date)->first();
            // if(empty($saldo)) {
            //     $saldo = PenyimpananRekening::where('id_rekening',$dt->id_rekening)->orderBy('id','desc')->first();
            // }
            $dt['saldo']=$dt->saldo;
            $aktiva += floatval($dt['saldo']);
        }

        $data_pasiva_array = array();
        foreach($data_pasiva as $dt2){
            $dt2['point'] = substr_count($dt2['id_bmt'], '.');
            // $saldo2 = PenyimpananRekening::where('id_rekening',$dt2['id_rekening'])->where('periode',$date)->first();
            // if(empty($saldo2))
            //     $saldo2 = PenyimpananRekening::where('id_rekening',$dt2['id_rekening'])->orderBy('id','desc')->first();
            // $dt2['saldo']=$saldo2['saldo'];
            $dt2['saldo']=$dt2['saldo'];
            array_push($data_pasiva_array,$dt2);
            $pasiva += floatval($dt2['saldo']);
        }
        $data_pasiva = collect($data_pasiva_array);

        $statusNeraca = true;

        if (abs($aktiva-$pasiva) > 0.00001) {
            $statusNeraca =false;
        }

        return response()->json([
            'data_aktiva' => $data_aktiva,
            'data_pasiva' => $data_pasiva,
            'statusNeraca' => $statusNeraca,
        ], 200);
    }
}
