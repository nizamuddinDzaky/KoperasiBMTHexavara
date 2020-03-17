<?php

namespace App\Repositories;

use App\BMT;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\RekeningReporsitories;

use App\Rekening;
use App\Tabungan;
use App\Deposito;
use App\PenyimpananTabungan;
use App\Pengajuan;
use App\User;

class TabunganReporsitories {
    
    public function __construct(RekeningReporsitories $rekeningReporsitory) {
        $this->rekeningReporsitory = $rekeningReporsitory;
    }

    /** 
     * Get All Tabungan
     * @return Response
    */
    public function getRekening($kategori_rekening)
    {
        $tabungan = Rekening::where('katagori_rekening', $kategori_rekening)->get();
        return $tabungan;
    }

    /** 
     * Find specific tabungan
     * @return Response
    */
    public function findRekening($kategori_rekening, $id_tabungan, $nama_rekening="")
    {
        $tabungan = Rekening::where([ ['katagori_rekening', $kategori_rekening], ['id_rekening', $id_tabungan] ])->get();

        if($nama_rekening != "")
        {
            $tabungan = Rekening::where([ ['katagori_rekening', $kategori_rekening], ['nama_rekening', $nama_rekening], ['tipe_rekening', 'detail'] ])->get();
        }
        return $tabungan;
    }

    /** 
     * Get user within specific tabungan
     * @return Response
    */
    public function getUserInTabungan($id_tabungan)
    {
        $userInTabungan = Tabungan::where('id_rekening', $id_tabungan)
                        ->join('users', 'tabungan.id_user', 'users.id')
                        ->select(['users.*', 'users.id as id_user', 'users.detail as detail_user', 'tabungan.*', 'tabungan.id as id_tabungan', 'tabungan.detail as detail_tabungan'])
                        ->get();
        return $userInTabungan;
    }

    /** 
     * Find specific tabungan
     * @return Response
    */
    public function findTabungan($id="", $id_tabungan="")
    {
        if($id != "" && $id_tabungan != "") 
        {
            $tabungan = Tabungan::where([ ['id', $id], ['id_tabungan', $id_tabungan] ])->first();
        }
        if($id != "" && $id_tabungan == "") 
        {
            $tabungan = Tabungan::where('id', $id)->first();
        }
        if($id == "" && $id_tabungan != "") 
        {
            $tabungan = Tabungan::where('id_tabungan', $id_tabungan)->first();
        }
        return $tabungan;
    }

    /** 
     * Get tabungan for specific user
     * @return Response
    */
    public function getUserTabungan($id_user, $id="")
    {
        if($id == "") 
        {
            $tabunganUser = Tabungan::where('id_user', $id_user)->with('user')->get();
        }

        $tabunganUser = Tabungan::where([ ['id_user', $id_user], ['id', $id] ])->get();

        return $tabunganUser;
    }

    /** 
     * Get BMT saldo
     * @return Response
    */
    public function getBMTSaldo($id_bmt)
    {
        $bmt = BMT::where('id_rekening', $id_bmt)->get();
        return $bmt;
    }

    /** 
     * Kredit tabungan
     * @return Response
    */
    public function creditTabungan($data) 
    {
        DB::beginTransaction();

        try
        {
            $pengajuan = PengajuanReporsitories::findPengajuan($data->id);
            $tabungan = $this->getUserTabungan($pengajuan->id_user, json_decode($pengajuan->detail)->id_tabungan); 
            foreach($tabungan as $tabung) {
                $tabungan = $tabung;
            }
            $bmtUser = BMT::where('id_rekening', json_decode($pengajuan->detail)->id_rekening)->first();
            // Use for transfer method
            $bmtTujuanKreditTabungan = BMT::where('id_rekening', json_decode($pengajuan->detail)->bank)->first();
            // Use for tunai method
            $userLoged = User::where('id', Auth::user()->id)->select('detail')->first();
            $userLogedBMT = BMT::where('id_rekening', json_decode($userLoged->detail)->id_rekening)->first();
            
            $dariRekening = "";
            $untukRekening = $userLogedBMT->id_rekening;
            if(json_decode($pengajuan->detail)->kredit == "Transfer") {
                $dariRekening = "Transfer";
                $untukRekening = json_decode($pengajuan->detail)->bank;
            }

            $detailToPenyimpananTabungan = [
                "teller"    => Auth::user()->id,
                "dari_rekening" => $dariRekening,
                "untuk_rekening" => $untukRekening,
                "jumlah" => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal" => json_decode($tabungan->detail)->saldo,
                "saldo_akhir" => floatval(json_decode($tabungan->detail)->saldo) + floatval(json_decode($pengajuan->detail)->jumlah)
            ];
            $dataToPenyimpananTabungan = [
                "id_user"       => $pengajuan->id_user,
                "id_tabungan"   => $tabungan->id,
                "status"        => "Kredit",
                "transaksi"     => $detailToPenyimpananTabungan,
                "teller"        => Auth::user()->id
            ];

            $detailToPenyimpananBMT = [
                "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal"    => $bmtUser->saldo,
                "saldo_akhir"   => floatval($bmtUser->saldo) + floatval(json_decode($pengajuan->detail)->jumlah),
                "id_pengajuan"  => $pengajuan->id
            ];
            $dataToPenyimpananBMT = [
                "id_user"       => $pengajuan->id_user,
                "id_bmt"        => $bmtUser->id,
                "status"        => "Kredit",
                "transaksi"     => $detailToPenyimpananBMT,
                "teller"        => Auth::user()->id
            ];

            if(
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == 'success' &&
                $this->insertPenyimpananTabungan($dataToPenyimpananTabungan) == 'success'
              ) 
            {
                // Update tabungan user in table tabungan
                $updateTabunganDetail = [
                    "saldo" => floatval(json_decode($tabungan->detail)->saldo) + floatval(json_decode($pengajuan->detail)->jumlah),
                    "id_pengajuan" => $pengajuan->id
                ];   
                $updateTabungan = Tabungan::where('id', json_decode($pengajuan->detail)->id_tabungan)->update([
                    "detail" => json_encode($updateTabunganDetail)
                ]);

                // Update bmt user in table tabungan
                $updateBMTUser = BMT::where('id', $bmtUser->id)->update([
                    "saldo" => floatval($bmtUser->saldo) + floatval(json_decode($pengajuan->detail)->jumlah)
                ]);

                // Update Pengajuan table
                $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([
                    "status"    => "Sudah Dikonfirmasi",
                    "teller"    => Auth::user()->id
                ]);

                /** 
                 * Filter transaction method 
                * */
                if(json_decode($pengajuan->detail)->kredit == "Transfer") {
                    $updateBMTTujuan = BMT::where('id', $bmtTujuanKreditTabungan->id)->update([
                        "saldo" => floatval($bmtTujuanKreditTabungan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah)
                    ]);
                }
                if(json_decode($pengajuan->detail)->kredit == "Tunai") {
                    $updateBMTTujuan = BMT::where('id_rekening', $userLogedBMT->id_rekening)->update([
                        "saldo" => floatval($userLogedBMT->saldo) + floatval(json_decode($pengajuan->detail)->jumlah)
                    ]);
                }

                DB::commit();

                $result = array('type' => 'success', 'message' => 'Pengajuan Berhasil Dikonfirmasi.');
            }
        }
        catch(\Exception $e)
        {
            DB::rollback();

            $result = array('type' => 'error', 'message' => 'Pengajuan Gagal Dikonfirmasi');
        }

        return $result;
    }

    /** 
     * Debit tabungan
     * @return Response
    */
    public function debitTabungan($data) 
    {

        DB::beginTransaction();
        try 
        {
            $pengajuan = PengajuanReporsitories::findPengajuan($data->id);
            $tabungan = $this->getUserTabungan($pengajuan->id_user, $pengajuan->id_rekening); 
            foreach($tabungan as $tabung) {
                $tabungan = $tabung;
            }
            $bmtUser = BMT::where('id_rekening', json_decode($pengajuan->detail)->id_rekening)->first();
            
            // Use for tunai method
            $userLoged = User::where('id', Auth::user()->id)->select('detail')->first();
            $userLogedBMT = BMT::where('id_rekening', json_decode($userLoged->detail)->id_rekening)->first();
            
            $dariRekening = "";
            $untukRekening = $userLogedBMT->id_rekening;
            if(json_decode($pengajuan->detail)->debit == "Transfer") {
                $dariRekening = "Transfer";
                $untukRekening = json_decode($pengajuan->detail)->bank;
            }

            $detailToPenyimpananTabungan = [
                "teller"    => Auth::user()->id,
                "dari_rekening" => $dariRekening,
                "untuk_rekening" => $untukRekening,
                "jumlah" => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal" => json_decode($tabungan->detail)->saldo,
                "saldo_akhir" => floatval(json_decode($tabungan->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah)
            ];
            $dataToPenyimpananTabungan = [
                "id_user"       => $pengajuan->id_user,
                "id_tabungan"   => $tabungan->id,
                "status"        => "Debit",
                "transaksi"     => $detailToPenyimpananTabungan,
                "teller"        => Auth::user()->id
            ];

            $detailToPenyimpananBMT = [
                "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal"    => $bmtUser->saldo,
                "saldo_akhir"   => floatval($bmtUser->saldo) - floatval(json_decode($pengajuan->detail)->jumlah),
                "id_pengajuan"  => $pengajuan->id
            ];
            $dataToPenyimpananBMT = [
                "id_user"       => $pengajuan->id_user,
                "id_bmt"        => $bmtUser->id,
                "status"        => "Debit",
                "transaksi"     => $detailToPenyimpananBMT,
                "teller"        => Auth::user()->id
            ];

            if(
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == 'success' &&
                $this->insertPenyimpananTabungan($dataToPenyimpananTabungan) == 'success'
            ) 
            {
                /** 
                 * Filter transaction method 
                * */
                if(floatval(json_decode($tabungan->detail)->saldo) >= floatval(json_decode($pengajuan->detail)->jumlah)) 
                {
                    if(json_decode($pengajuan->detail)->debit == "Transfer") 
                    {
                        $bmtPencairDana = BMT::where('id_rekening', $data->daribank)->select('saldo')->first();
                        if(floatval($bmtPencairDana->saldo) >= floatval(json_decode($pengajuan->detail)->jumlah)) 
                        {
                            $updateBMTPencairDana = BMT::where('id_rekening', $data->daribank)->update([
                                "saldo" => floatval($bmtPencairDana->saldo) - floatval(json_decode($pengajuan->detail)->jumlah)
                            ]);

                            // Update tabungan user in table tabungan
                            $updateTabunganDetail = [
                                "saldo" => floatval(json_decode($tabungan->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah),
                                "id_pengajuan" => $pengajuan->id
                            ];   
                            $updateTabungan = Tabungan::where('id', $tabungan->id)->update([
                                "detail" => json_encode($updateTabunganDetail)
                            ]);

                            // Update bmt user in table tabungan
                            $updateBMTUser = BMT::where('id', $bmtUser->id)->update([
                                "saldo" => floatval($bmtUser->saldo) - floatval(json_decode($pengajuan->detail)->jumlah)
                            ]);

                            // Update Pengajuan table
                            $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([
                                "status"    => "Sudah Dikonfirmasi",
                                "teller"    => Auth::user()->id
                            ]);
                        }
                    }

                    if(json_decode($pengajuan->detail)->debit == "Tunai") 
                    {
                        $bmtPencairDana = BMT::where('id_rekening', $userLogedBMT->id_rekening)->select('saldo')->first();
                        if(floatval($bmtPencairDana->saldo) >=  floatval(json_decode($pengajuan->detail)->jumlah))
                        {
                            $updateBMTPencairDana = BMT::where('id_rekening', $userLogedBMT->id_rekening)->update([
                                "saldo" => floatval($userLogedBMT->saldo) - floatval(json_decode($pengajuan->detail)->jumlah)
                            ]);

                            // Update tabungan user in table tabungan
                            $updateTabunganDetail = [
                                "saldo" => floatval(json_decode($tabungan->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah),
                                "id_pengajuan" => $pengajuan->id
                            ];   
                            $updateTabungan = Tabungan::where('id', $tabungan->id)->update([
                                "detail" => json_encode($updateTabunganDetail)
                            ]);

                            // Update bmt user in table tabungan
                            $updateBMTUser = BMT::where('id', $bmtUser->id)->update([
                                "saldo" => floatval($bmtUser->saldo) - floatval(json_decode($pengajuan->detail)->jumlah)
                            ]);

                            // Update Pengajuan table
                            $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([
                                "status"    => "Sudah Dikonfirmasi",
                                "teller"    => Auth::user()->id
                            ]);
                        }
                    }

                    // DB will commit if all update is successfully
                    if(isset($updateBMTPencairDana) && isset($updateTabungan) && isset($updateBMTUser) && isset($updatePengajuan))
                    {
                        if($updateBMTPencairDana && $updateTabungan && $updateBMTUser && $updatePengajuan)
                        {
                            DB::commit();
                            $result = array('type' => 'success', 'message' => 'Pengajuan Berhasil Dikonfirmasi.');
                        }
                    }
                    else
                    {
                        DB::rollback();
                        $result = array('type' => 'error', 'message' => 'Pengajuan Debit Tabungan Gagal, Pastikan Saldo Tabungan Dan Rekening Pencair Dana Cukup.');
                    }
                }
                else
                {
                    DB::rollback();
                    $result = array('type' => 'error', 'message' => 'Pengajuan Gagal, saldo ' . json_decode($pengajuan->detail)->nama_tabungan . ' tidak cukup.');
                }

            }
            return $result;
        }
        catch(Exception $e)
        {
            DB::rollback();
            $result = array('type' => 'error', 'message' => 'Pengajuan Gagal Dikonfirmasi.');
        }

    }

    /** 
     * Insert data to penyimpanan tabungan table
     * @return Response
    */
    public function insertPenyimpananTabungan($data)
    {
        $penyimpanan = new PenyimpananTabungan();
        $penyimpanan->id_user = $data['id_user'];
        $penyimpanan->id_tabungan = $data['id_tabungan'];
        $penyimpanan->status = $data['status'];
        $penyimpanan->transaksi = json_encode($data['transaksi']);
        $penyimpanan->teller = $data['teller'];

        if($penyimpanan->save()) {
            return "success";
        }
        else {
            return "error";
        }
    }


}