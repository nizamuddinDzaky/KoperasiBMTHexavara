<?php

namespace App\Repositories;

use App\BMT;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\Rekening;
use App\Tabungan;
use App\Deposito;
use App\PenyimpananTabungan;
use App\Pengajuan;

class DepositoReporsitories {
    
    /** 
     * Pencairan deposito pengguna
     * @return Response
    */
    public function pencairanDeposito($data)
    {
        // Update deposito status
        $deposito = Deposito::where('id_deposito', $data->id_deposito)->update([ 'status' => 'closed']);

        $tabungan = Tabungan::where('id', $data->id_pencairan)->get();
        $saldo = floatval(preg_replace('/[^\d.]/', '', $data->saldo));
        foreach($tabungan as $tabungan)
        {
            $saldo_awal = json_decode($tabungan->detail, true)['saldo'];
        }
        
        // Insert data to penyimpanan tabungan to record the history of tabungan
        $detail = [
            "teller" => Auth::user()->id,
            "dari_rekening" => $data->id_deposito,
            "jumlah" => $saldo,
            "saldo_awal" => $saldo_awal,
            "saldo_akhir" => $saldo_awal + $saldo,
            "untuk_rekening" => $data->id_pencairan
        ];
        $dt = New PenyimpananTabungan();
        $dt->id_user   =  $data->id_user_pencairan;
        $dt->id_tabungan = $data->id_pencairan;
        $dt->status    = 'Pencairan Mudharabah Berjangka';
        $dt->transaksi = json_encode($detail);
        $dt->teller = Auth::user()->id;
        $dt->save();

        // Update saldo in tabungan table
        $detail_tabungan = [
            "saldo" => $saldo_awal + $saldo,
            "id_pengajuan" => $data->id
        ];
        $tabungan_update = Tabungan::where('id', $data->id_pencairan)->update([ 'detail' => json_encode($detail_tabungan) ]);

        // Update pengajuan status in table pengajuan
        if($data->teller == null && $data->teller == "undefined") {
            $pengajuan = Pengajuan::where('id', $data->id)->update([
                "status"    => "Sudah Dikonfirmasi",
                "teller"    => Auth::user()->id
            ]);
        }

        $response = array("status" => "sukses", "message" => "Pencairan Deposito Berhasil Dikonfirmasi");
        return $response; 
    }

    /** 
     * Get deposito data
     * @return Response
    */
    public function getDeposito($status="")
    {
        if($status != "") {
            $deposito = Deposito::where('deposito.status', $status)
                        ->join('users', 'deposito.id_user', 'users.id')
                        ->select('deposito.*', 'users.id as id_user', 'users.no_ktp', 'users.nama')
                        ->get();
        }
        else {
            $deposito = Deposito::join('users', 'deposito.id_user', 'users.id')
                        ->select('deposito.*', 'users.id as id_user', 'users.no_ktp', 'users.nama')
                        ->get();;
        }

        return $deposito;
    }

    /** 
     * Get deposito from specified user
     * @return Response
    */
    public function getUserDeposito($status="", $user="")
    {
        if($user != "")
        {
            $deposito = Deposito::where('deposito.id_user', $user)
                        ->join('users', 'deposito.id_user', 'users.id')
                        ->select('deposito.*', 'users.id as id_user', 'users.no_ktp', 'users.nama')
                        ->get();
        }
        if($status != "" && $user != "")
        {
            $deposito = Deposito::where([ ['deposito.status', $status], ['deposito.id_user', $user] ])
                        ->join('users', 'deposito.id_user', 'users.id')
                        ->select('deposito.*', 'users.id as id_user', 'users.no_ktp', 'users.nama')
                        ->get();
        }

        return $deposito;
    }

}