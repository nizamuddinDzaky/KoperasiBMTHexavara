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
     *  Get All Tabungan
     * @return Response
    */
    public function getTabungan($jenis_tabungan="")
    {
        $tabungan = Tabungan::join('users', 'users.id', 'tabungan.id_user')
                    ->select('tabungan.*', 'users.detail as user_detail', 'users.nama')
                    ->get();
        
        if($jenis_tabungan !== "")
        {
            $tabungan = Tabungan::join('users', 'users.id', 'tabungan.id_user')
                    ->select('tabungan.*', 'users.detail as user_detail', 'users.nama')
                    ->where('jenis_tabungan', $jenis_tabungan)
                    ->get();
        }

        return $tabungan;
    }

    /** 
     * Get All Kind Of Tabungan
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
    public function getUserTabungan($id_user, $id="", $status="")
    {
        $tabunganUser = Tabungan::where('id_user', $id_user)->with('user')->get();
        
        if($id !== "") 
        {
            $tabunganUser = Tabungan::where([ ['id_user', $id_user], ['id_tabungan', $id] ])->get();
        }
        if($status !== "") 
        {
            $tabunganUser = Tabungan::where([ ['id_user', $id_user], ['status', $status] ])->get();
        }
        if($status !== "" && $id !== "") 
        {
            $tabunganUser = Tabungan::where([ ['id_user', $id_user], ['status', $status], ['id_tabungan', $id] ])->get();
        }
        
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
     * Confirm debit tabungan
     * This is for confirmation when user send pengajuan
     * @return Response
    */
    public function confirmDebitTabungan($data) 
    {
        DB::beginTransaction();

        try
        {
            $pengajuan = PengajuanReporsitories::findPengajuan($data->id);
            $tabungan = $this->getUserTabungan($pengajuan->id_user, json_decode($pengajuan->detail)->id_tabungan); 
            foreach ($tabungan as $tabung) {
                $tabungan = $tabung;
            }
            
            $bmtTabungan = BMT::where('id_rekening', json_decode($pengajuan->detail)->id_rekening)->first();

            if(json_decode($pengajuan->detail)->debit == "Transfer") {
                $bmtTujuanDebitTabungan = BMT::where('id_rekening', json_decode($pengajuan->detail)->bank)->first();
                $dariRekening = "Transfer";
                $untukRekening = json_decode($pengajuan->detail)->bank;
            }
            if(json_decode($pengajuan->detail)->debit == "Tunai") {
                $userLoged = User::where('id', Auth::user()->id)->select('detail')->first();
                $bmtTujuanDebitTabungan = BMT::where('id_rekening', json_decode($userLoged->detail)->id_rekening)->first();
                $dariRekening = "";
                $untukRekening = $bmtTujuanDebitTabungan->id_rekening;
            }

            $detailToPenyimpananTabungan = [
                "teller"    => Auth::user()->id,
                "dari_rekening" => $dariRekening,
                "untuk_rekening" => $bmtTujuanDebitTabungan->nama,
                "jumlah" => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal" => json_decode($tabungan->detail)->saldo,
                "saldo_akhir" => floatval(json_decode($tabungan->detail)->saldo) + floatval(json_decode($pengajuan->detail)->jumlah)
            ];
            $dataToPenyimpananTabungan = [
                "id_user"       => $pengajuan->id_user,
                "id_tabungan"   => $tabungan->id,
                "status"        => "Debit " . $bmtTabungan->nama,
                "transaksi"     => $detailToPenyimpananTabungan,
                "teller"        => Auth::user()->id
            ];
            
            $detailToPenyimpananBMT = [
                "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal"    => $bmtTabungan->saldo,
                "saldo_akhir"   => floatval($bmtTabungan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah),
                "id_pengajuan"  => $pengajuan->id
            ];
            $dataToPenyimpananBMT = [
                "id_user"       => $pengajuan->id_user,
                "id_bmt"        => $bmtTabungan->id,
                "status"        => "Debit " . $bmtTabungan->nama,
                "transaksi"     => $detailToPenyimpananBMT,
                "teller"        => Auth::user()->id
            ];

            if(
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == 'success' &&
                $this->insertPenyimpananTabungan($dataToPenyimpananTabungan) == 'success'
              ) 
            {

                $detailToPenyimpananBMT['jumlah'] = floatval(json_decode($pengajuan->detail)->jumlah);
                $detailToPenyimpananBMT['saldo_awal'] = $bmtTujuanDebitTabungan->saldo;
                $detailToPenyimpananBMT['saldo_akhir'] = floatval($bmtTujuanDebitTabungan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                $dataToPenyimpananBMT['id_bmt'] = $bmtTujuanDebitTabungan->id;
                $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                // Update tabungan user in table tabungan
                $updateTabunganDetail = [
                    "saldo" => floatval(json_decode($tabungan->detail)->saldo) + floatval(json_decode($pengajuan->detail)->jumlah),
                    "id_pengajuan" => $pengajuan->id
                ];   
                $updateTabungan = Tabungan::where('id_tabungan', json_decode($pengajuan->detail)->id_tabungan)->update([
                    "detail" => json_encode($updateTabunganDetail)
                ]);

                // Update bmt user in table tabungan
                $updateBMTUser = BMT::where('id', $bmtTabungan->id)->update([
                    "saldo" => floatval($bmtTabungan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah)
                ]);

                // Update Pengajuan table
                $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([
                    "status"    => "Sudah Dikonfirmasi",
                    "teller"    => Auth::user()->id
                ]);

                /** 
                 * Filter transaction method 
                * */
                $updateBMTTujuan = BMT::where('id_rekening', $bmtTujuanDebitTabungan->id_rekening)->update([
                    "saldo" => floatval($bmtTujuanDebitTabungan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah)
                ]);


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
     * Confirm credit tabungan
     * This is for confirmation when user send pengajuan
     * @return Response
    */
    public function confirmCreditTabungan($data) 
    {

        DB::beginTransaction();
        try 
        {
            $pengajuan = PengajuanReporsitories::findPengajuan($data->id); //cari pengajuan
            $tabungan = Tabungan::where([ ['id_user', $pengajuan->id_user], ['id_tabungan', json_decode($pengajuan->detail)->id_tabungan] ])->first(); // ambil tabungan user
            $rekening_tabungan = Rekening::where('id', $tabungan->id_rekening)->first(); // ambil rekening tabungan untuk minimum saldo

            if(json_decode($tabungan->detail)->saldo < floatval(json_decode($rekening_tabungan->detail)->saldo_min)) //clear
            {
                DB::rollback();
                $result = array('type' => 'error', 'message' => 'Pengajuan Gagal Dikonfirmasi. Tabungan pengirim melampaui limit transaksi.');
            }
            else
            {
                $bmtUser = BMT::where('id_rekening', json_decode($pengajuan->detail)->id_rekening)->first(); //rekening bmt untuk simpanan user
                // Use for tunai method
                $userLoged = User::where('id', Auth::user()->id)->select('detail')->first();
                $userLogedBMT = BMT::where('id_rekening', json_decode($userLoged->detail)->id_rekening)->first();
                
                $untukRekening = "";
                $dariRekening = $userLogedBMT->nama;

                if(json_decode($pengajuan->detail)->kredit == "Transfer") {
                    $bmtAsalCreditTabungan = BMT::where('id_rekening', $data->daribank)->first(); // rekening koperasi (mandiri unair, dll)
                    $untukRekening = "Transfer";
                    $dariRekening = $bmtAsalCreditTabungan->nama;
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
                    "status"        => "Kredit " . $bmtUser->nama,
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
                    "status"        => "Kredit " . $bmtUser->nama,
                    "transaksi"     => $detailToPenyimpananBMT,
                    "teller"        => Auth::user()->id
                ];

                $updateTabunganDetail = [
                    "saldo" => floatval(json_decode($tabungan->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah),
                    "id_pengajuan" => $pengajuan->id
                ];

                if(
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == 'success' &&
                    $this->insertPenyimpananTabungan($dataToPenyimpananTabungan) == 'success'
                ) 
                {

                    $detailToPenyimpananBMT['jumlah'] = -floatval(json_decode($pengajuan->detail)->jumlah);
                    $detailToPenyimpananBMT['saldo_awal'] = $userLogedBMT->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = floatval($userLogedBMT->saldo) - floatval(json_decode($pengajuan->detail)->jumlah);
                    $dataToPenyimpananBMT['id_bmt'] = $userLogedBMT->id;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    /** 
                     * Filter transaction method 
                    * */
                    if(floatval(json_decode($tabungan->detail)->saldo) >= floatval(json_decode($pengajuan->detail)->jumlah)) 
                    {
                        if(json_decode($pengajuan->detail)->kredit == "Transfer") 
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

                        if(json_decode($pengajuan->detail)->kredit == "Tunai") 
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
                                $result = array('type' => 'success', 'message' => 'Pengajuan Kredit Tabungan Berhasil Dikonfirmasi.');
                            }
                        }
                        else
                        {
                            DB::rollback();
                            $result = array('type' => 'error', 'message' => 'Pengajuan Kredit Tabungan Gagal Dikonfirmasi, Pastikan Saldo Tabungan Dan Rekening Pencair Dana Cukup.');
                        }
                    }
                    else
                    {
                        DB::rollback();
                        $result = array('type' => 'error', 'message' => 'Pengajuan Gagal Dikonfirmasi, saldo ' . json_decode($pengajuan->detail)->nama_tabungan . ' tidak cukup.');
                    }

                }
            }
        }
        catch(Exception $e)
        {
            DB::rollback();
            $result = array('type' => 'error', 'message' => 'Pengajuan Gagal Dikonfirmasi.');
        }

        return $result;
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

    /** 
     * Debit tabungan
     * @return Response
    */
    public function debitTabungan($data)
    {

        DB::beginTransaction();

        try
        {
            $tabungan = $this->findTabungan($data->idRek, "");
            $rekening = $this->rekeningReporsitory->getRekening($name=$tabungan->jenis_tabungan, $type='detail');

            foreach($rekening as $rekening)
            {
                $rekening = $rekening;
            }
            $bmtUserCredit = BMT::where('id_rekening', $rekening->id)->first();
            $bmtTellerLoged = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first();

            $dariRekening = "";
            $untukRekening = $bmtTellerLoged->nama;
            if($data->debit == 1) {
                $dariRekening = "Transfer";
                $bmtTellerLoged = BMT::where('id_rekening', $data->bank)->first();
                $untukRekening = $bmtTellerLoged->nama;
            }

            $detailToPenyimpananTabungan = [
                "teller"        => Auth::user()->id,
                "dari_rekening" => $dariRekening,
                "untuk_rekening"=> $untukRekening,
                "jumlah"        => floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                "saldo_awal"    => floatval(json_decode($tabungan->detail)->saldo),
                "saldo_akhir"   => floatval(json_decode($tabungan->detail)->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
            ];
            $dataToPenyimpananTabungan = [
                "id_user"       => $tabungan->id_user,
                "id_tabungan"   => $tabungan->id,
                "status"        => "Debit " . $bmtUserCredit->nama,
                "transaksi"     => $detailToPenyimpananTabungan,
                "teller"        => Auth::user()->id
            ];
            
            $detailToPenyimpananBMT = [
                "jumlah"        => floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                "saldo_awal"    => floatval($bmtUserCredit->saldo),
                "saldo_akhir"   => floatval(preg_replace('/[^\d.]/', '', $data->jumlah)) + floatval($bmtUserCredit->saldo),
                "id_pengajuan"  => null
            ];
            $dataToPenyimpananBMT = [
                "id_user"       => $tabungan->id_user,
                "id_bmt"        => $bmtUserCredit->id,
                "status"        => "Debit " . $bmtUserCredit->nama,
                "transaksi"     => $detailToPenyimpananBMT,
                "teller"        => Auth::user()->id
            ];

            if(
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == 'success' &&
                $this->insertPenyimpananTabungan($dataToPenyimpananTabungan) == 'success'
            ) 
            {

                $detailToPenyimpananBMT['jumlah'] = floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                $detailToPenyimpananBMT['saldo_awal'] = $bmtTellerLoged->saldo;
                $detailToPenyimpananBMT['saldo_akhir'] = floatval($bmtTellerLoged->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                $dataToPenyimpananBMT['id_bmt'] = $bmtTellerLoged->id;
                $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                
                // Update tabungan user in table tabungan
                $updateTabunganDetail = [
                    "saldo" => floatval(json_decode($tabungan->detail)->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                    "id_pengajuan" => null
                ];   
                $updateTabungan = Tabungan::where('id', $tabungan->id)->update([
                    "detail" => json_encode($updateTabunganDetail)
                ]);

                // Update bmt user in table tabungan
                $updateBMTUser = BMT::where('id', $bmtUserCredit->id)->update([
                    "saldo" => floatval($bmtUserCredit->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->jumlah))
                ]);

                /** 
                 * Filter transaction method 
                * */
                if($data->debit == 1) {
                    $updateBMTTujuan = BMT::where('id_rekening', $data->bank)->update([
                        "saldo" => floatval($bmtTellerLoged->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->jumlah))
                    ]);
                }
                if($data->debit == 0) {
                    $updateBMTTujuan = BMT::where('id_rekening', $bmtTellerLoged->id_rekening)->update([
                        "saldo" => floatval($bmtTellerLoged->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->jumlah))
                    ]);
                }

                DB::commit();

                $result = array('type' => 'success', 'message' => 'Setoran Berhasil Dilakukan.');
            }
        }
        catch(Exception $e)
        {
            DB::rollback();
            $result = array('type' => 'error', 'message' => 'Setoran Gagal Dilakukan.');
        }

        return $result;
    }

    /** 
     * Credit tabungan
     * This is for confirmation when user send pengajuan
     * @return Response
    */
    public function creditTabungan($data) 
    {
        DB::beginTransaction();
        try 
        {
            $tabungan = $this->findTabungan("", $data->id_);
            $rekening = $this->rekeningReporsitory->getRekening($name=$tabungan->jenis_tabungan, $type='detail');

            foreach($rekening as $rekening)
            {
                $rekening = $rekening;
            }
            
            if(json_decode($tabungan->detail)->saldo < floatval(json_decode($rekening->detail)->saldo_min))
            {
                DB::rollback();
                $result = array('type' => 'error', 'message' => 'Kredit tabungan gagal. Tabungan pengirim melampaui limit transaksi.');
            }
            else
            {
            
                $bmtUserDebit = BMT::where('id_rekening', $rekening->id)->first();
                $bmtTellerLoged = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first();

                $untukRekening = "";
                $dariRekening = $bmtTellerLoged->nama;
                if($data->kredit == 1) {
                    $untukRekening = "Transfer";
                    $bmtTellerLoged = BMT::where('id_rekening', $data->daribank)->first();
                    $dariRekening = $bmtTellerLoged->nama;
                }

                    $detailToPenyimpananTabungan = [
                        "teller"        => Auth::user()->id,
                        "dari_rekening" => $dariRekening,
                        "untuk_rekening"=> $untukRekening,
                        "jumlah"        => floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                        "saldo_awal"    => floatval(json_decode($tabungan->detail)->saldo),
                        "saldo_akhir"   => floatval(json_decode($tabungan->detail)->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => $tabungan->id_user,
                        "id_tabungan"   => $tabungan->id,
                        "status"        => "Kredit " . $bmtUserDebit->nama,
                        "transaksi"     => $detailToPenyimpananTabungan,
                        "teller"        => Auth::user()->id
                    ];
                    
                    $detailToPenyimpananBMT = [
                        "jumlah"        => floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                        "saldo_awal"    => floatval($bmtUserDebit->saldo),
                        "saldo_akhir"   => floatval($bmtUserDebit->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                        "id_pengajuan"  => null
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user"       => $tabungan->id_user,
                        "id_bmt"        => $bmtUserDebit->id,
                        "status"        => "Kredit " . $bmtUserDebit->nama,
                        "transaksi"     =>  $detailToPenyimpananBMT,
                        "teller"        => Auth::user()->id
                    ];
                    
                    if(
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == 'success' &&
                        $this->insertPenyimpananTabungan($dataToPenyimpananTabungan) == 'success'
                    ) 
                    {

                        $detailToPenyimpananBMT['jumlah'] = -floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                        $detailToPenyimpananBMT['saldo_awal'] = $bmtTellerLoged->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = floatval($bmtTellerLoged->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                        $dataToPenyimpananBMT['id_bmt'] = $bmtTellerLoged->id;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        /** 
                         * Filter transaction method 
                        * */
                        if(floatval(json_decode($tabungan->detail)->saldo) >= floatval(preg_replace('/[^\d.]/', '', $data->jumlah)) ) 
                        {
                            if($data->kredit == 1) 
                            {
                                $bmtPencairDana = BMT::where('id_rekening', $data->daribank)->select('saldo')->first();
                                if(floatval($bmtPencairDana->saldo) >= floatval(preg_replace('/[^\d.]/', '', $data->jumlah)) ) 
                                {
                                    $updateBMTPencairDana = BMT::where('id_rekening', $data->daribank)->update([
                                        "saldo" => floatval($bmtPencairDana->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah))
                                    ]);

                                    // Update tabungan user in table tabungan
                                    $updateTabunganDetail = [
                                        "saldo" => floatval(json_decode($tabungan->detail)->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                                        "id_pengajuan" => null
                                    ];   
                                    $updateTabungan = Tabungan::where('id', $tabungan->id)->update([
                                        "detail" => json_encode($updateTabunganDetail)
                                    ]);

                                    // Update bmt user in table tabungan
                                    $updateBMTUser = BMT::where('id', $bmtUserDebit->id)->update([
                                        "saldo" => floatval($bmtUserDebit->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah))
                                    ]);
                                }
                            }

                            if($data->kredit == 0) 
                            {
                                $bmtPencairDana = BMT::where('id_rekening', $bmtTellerLoged->id_rekening)->select('saldo')->first();
                                if(floatval($bmtPencairDana->saldo) >=  floatval(preg_replace('/[^\d.]/', '', $data->jumlah)))
                                {
                                    $updateBMTPencairDana = BMT::where('id_rekening', $bmtTellerLoged->id_rekening)->update([
                                        "saldo" => floatval($bmtTellerLoged->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah))
                                    ]);

                                    // Update tabungan user in table tabungan
                                    $updateTabunganDetail = [
                                        "saldo" => floatval(json_decode($tabungan->detail)->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                                        "id_pengajuan" => null
                                    ];   
                                    $updateTabungan = Tabungan::where('id', $tabungan->id)->update([
                                        "detail" => json_encode($updateTabunganDetail)
                                    ]);

                                    // Update bmt user in table tabungan
                                    $updateBMTUser = BMT::where('id', $bmtUserDebit->id)->update([
                                        "saldo" => floatval($bmtUserDebit->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->jumlah))
                                    ]);
                                }
                            }

                            // DB will commit if all update is successfully
                            if(isset($updateBMTPencairDana) && isset($updateTabungan) && isset($updateBMTUser))
                            {
                                if($updateBMTPencairDana && $updateTabungan && $updateBMTUser)
                                {
                                    DB::commit();
                                    $result = array('type' => 'success', 'message' => 'Kredit Tabungan Berhasil Dilakukan.');
                                }
                            }
                            else
                            {
                                DB::rollback();
                                $result = array('type' => 'error', 'message' => 'Kredit Tabungan Gagal, Pastikan Saldo Tabungan Dan Rekening Pencair Dana Cukup.');
                            }
                        }
                        else
                        {
                            DB::rollback();
                            $result = array('type' => 'error', 'message' => 'Kredit Tabungan Gagal. Saldo anda tidak cukup');
                        }
                    }


            }
        }
        catch(Exception $e)
        {
            DB::rollback();
            $result = array('type' => 'error', 'message' => 'Kredit Tabungan Gagal.');
        }
        return $result;

    }

    /** 
     * Insert data to tabungan table
     * @return Response
    */
    public function createTabungan($data)
    {
        $tabungan = new Tabungan();
        $tabungan->id_tabungan = $data['id_tabungan'];
        $tabungan->id_rekening = $data['id_rekening'];
        $tabungan->id_user = $data['id_user'];
        $tabungan->id_pengajuan = $data['id_pengajuan'];
        $tabungan->jenis_tabungan = $data['jenis_tabungan'];
        $tabungan->detail = json_encode($data['detail']);
        $tabungan->status = $data['status'];

        if($tabungan->save()) {
            return "success";
        }
        else {
            return "error";
        }
    }

    /** 
     * Get grouping tabungan
     * ambil data tabungan berdasarkan jenis tabungan
     * @return Response
    */
    public function getGroupingTabungan($id="") 
    {
        if($id == "")
        {
            $rekening_tabungan = Rekening::where([ ['tipe_rekening', 'detail'], ['katagori_rekening', 'TABUNGAN'] ])->get();
            foreach($rekening_tabungan as $rekening)
            {
                $rekening['jumlah_anggota'] = count($rekening->tabungan);

                if(count($rekening->tabungan) > 0)
                {
                    foreach($rekening->tabungan as $tabungan)
                    {
                        $pengajuan = Pengajuan::where([ ['jenis_pengajuan', 'Buka Tabungan ' . $tabungan->jenis_tabungan], ['status', 'Menunggu Konfirmasi'] ])->get();
                        $rekening['jumlah_saldo'] += json_decode($tabungan->detail)->saldo;
                        $rekening['pengajuan'] = $pengajuan;
                    }
                }
                else
                {
                    $rekening['jumlah_saldo'] = 0;
                    $rekening['pengajuan'] = [];
                }
            }
        }
        else
        {
            $rekening_tabungan = Rekening::where([ ['tipe_rekening', 'detail'], ['katagori_rekening', 'TABUNGAN'], ['id', $id] ])->get();
            foreach($rekening_tabungan as $rekening)
            {
                $rekening['jumlah_anggota'] = count($rekening->tabungan);

                if(count($rekening->tabungan) > 0)
                {
                    foreach($rekening->tabungan as $tabungan)
                    {
                        $pengajuan = Pengajuan::where([ ['jenis_pengajuan', 'Buka Tabungan ' . $tabungan->jenis_tabungan], ['status', 'Menunggu Konfirmasi'] ])->get();
                        $rekening['jumlah_saldo'] += json_decode($tabungan->detail)->saldo;
                        $rekening['pengajuan'] = $pengajuan;
                    }
                }
                else
                {
                    $rekening['jumlah_saldo'] = 0;
                    $rekening['pengajuan'] = [];
                }
            }
        }

        return $rekening_tabungan;
    }

    /** 
     * Get riwayat transaksi tabungan
     * @return Response
    */
    public function getRiwayatTabungan($id="")
    {
        if($id !== "")
        {
            $data_riwayat = PenyimpananTabungan::where([ ['id_tabungan', $id], ['status', '!=', 'Setoran Awal'] ])->get();
        }
        return $data_riwayat;
    }
}