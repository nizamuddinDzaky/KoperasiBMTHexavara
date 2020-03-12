<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Pengajuan;
use App\Rekening;
use App\BMT;
use App\PenyimpananBMT;

class RekeningReporsitories {

    /** 
     * Get rekening with several category excluded
     * @return Response
    */
    public function getRekeningExcludedCategory($excluded)
    {
        $rekening = "SELECT rekening.*, bmt.saldo FROM rekening INNER JOIN bmt ON rekening.id=bmt.id_rekening WHERE rekening.nama_rekening NOT LIKE '%" . $excluded[0] . "%'";
        for($i=1; $i < count($excluded); $i++) {
            $rekening .= " AND rekening.nama_rekening NOT LIKE '%" . $excluded[$i] . "%'";
        }

        $data = DB::select( DB::raw($rekening) );
        
        return $data;
    }

    /** 
     * Pengeluaran pemasukan rekening 
     * use in teller dashboard
     * @return Response
    */
    public function transferRekening($data)
    {
        DB::beginTransaction();

        try
        {
            /** 
             * Inset data to penyimpanan bmt table 
             * Using for history data
             * @return Null
            */
            if($data->tipe == 1) {
                $jenis = "Pemasukan";
                $dari = $data->dari;
                $ke = json_decode(Auth::user()->detail)->id_rekening;
                $bmt_penerima = BMT::where('id_rekening', $ke)->select('id')->first();
                $id_penerima = $bmt_penerima->id;
            } else {
                $jenis = "Pengeluaran";
                $dari = json_decode(Auth::user()->detail)->id_rekening;
                $ke = $data->dari;
                $bmt_penerima = BMT::where('id_rekening', $ke)->select('id')->first();
                $id_penerima = $bmt_penerima->id;
            }

            $id_user = Auth::user()->id;
            $id_bmt = $id_penerima;
            $status = "Jurnal Lain";
            $saldo = BMT::where('id', $id_bmt)->select('saldo')->first();
            
            if($saldo['saldo'] == "") {
                $saldo['saldo'] = 0;
            }

            $detail = [
                "jumlah"    => preg_replace('/[^\d.]/', '', $data->jumlah),
                "saldo_awal"=> floatval($saldo['saldo']),
                "saldo_akhir" => floatval($saldo['saldo']) + preg_replace('/[^\d.]/', '', $data->jumlah),
                "dari"      => $dari,
                "ke"    => $ke,
                "keterangan"=> "[" . $jenis . "] " . $data->keterangan
            ];
            $teller = Auth::user()->id;

            $dataToPenyimpananBMT = [
                "id_user"   => $id_user,
                "id_bmt"    => $id_bmt,
                "status"    => $status,
                "transaksi" => $detail,
                "teller"    => $teller,
                "saldo_awal"=> $saldo['saldo'],
                "jumlah"    => preg_replace('/[^\d.]/', '', $data->jumlah),
            ];
            $dataToBMT = [
                "id_rekening_pengirim"  => $dari,
                "id_rekening_penerima"  => $ke,
                "jumlah"                => preg_replace('/[^\d.]/', '', $data->jumlah)
            ];

            if( 
                $this->insertPenyimpananBMT($dataToPenyimpananBMT) == "success" &&
                $this->updateSaldoRekening($dataToBMT, $jenis) == "success"
              )
            {
                DB::commit();
                $result = array('type' => 'success', 'message' => 'Transfer Pengeluaran/Pemasukan Berhasil Dilakukan');
            }
            else
            { 
                $result = array('type' => 'error', 'message' => 'Transfer Pengeluaran/Pemasukan Gagal. Pastikan data benar dan saldo pemindahan cukup.');
            }

            return $result;
        }
        catch(\Exception $e)
        {
            DB::rollback();

            $result = array('type' => 'error', 'message' => 'Transfer Pengeluaran/Pemasukan Gagal');
        }

        return $result;
    }

    /** 
     * Insert data to penyimpanan BMT table
     * @return Response
    */
    public function insertPenyimpananBMT($data)
    {
        if(floatval($data['saldo_awal']) > floatval($data['jumlah'])) {
            $penyimpanan = new PenyimpananBMT();
            $penyimpanan->id_user = $data['id_user'];
            $penyimpanan->id_bmt = $data['id_bmt'];
            $penyimpanan->status = $data['status'];
            $penyimpanan->transaksi = json_encode($data['transaksi']);
            $penyimpanan->teller = $data['teller'];

            if($penyimpanan->save()) {
                return "success";
            }
            else {
                return "error";
            }
            return "success";
        }
        else {
            return "error";
        }
    }

    /** 
     * Update rekening saldo in BMT table
     * @return Response
    */
    public function updateSaldoRekening($data, $type)
    {
        $saldoPengirim = BMT::where('id_rekening', $data['id_rekening_pengirim'])->select('saldo')->first();
        $saldoPenerima = BMT::where('id_rekening', $data['id_rekening_penerima'])->select('saldo')->first();
        if(floatval($saldoPengirim->saldo) > floatval($data['jumlah'])) {
            $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($saldoPengirim->saldo) - floatval($data['jumlah'])  ]);
            $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($saldoPenerima->saldo) + floatval($data['jumlah'])  ]);

            return "success";
        } else {
            return "error";
        }
    }   
}

?>