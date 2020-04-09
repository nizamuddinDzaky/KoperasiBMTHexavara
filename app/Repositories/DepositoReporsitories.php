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
use App\PenyimpananDeposito;
use App\Pengajuan;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\TabunganReporsitories;
use Carbon\Carbon;
use App\User;

class DepositoReporsitories {
    
    public function __construct(
        PengajuanReporsitories $pengajuanReporsitory,
        RekeningReporsitories $rekeningReporsitory,
        TabunganReporsitories $tabunganReporsitory
    )
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
    }

    /** 
     * Pencairan deposito pengguna
     * @return Response
    */
    public function pencairanDeposito($data)
    {
        DB::beginTransaction();
        try {
            $tabungan = Tabungan::where('id', $data->id_pencairan)->get();
            $saldo = floatval(preg_replace('/[^\d.]/', '', $data->saldo));
            foreach($tabungan as $tabungan)
            {
                $saldo_awal = json_decode($tabungan->detail, true)['saldo'];
            }

            // Insert data to penyimpanan tabungan to record the history of tabungan
            $detailToPenyimpananTabungan = [
                "teller" => Auth::user()->id,
                "dari_rekening" => $data->id_deposito,
                "untuk_rekening" => $data->id_pencairan,
                "jumlah" => $saldo,
                "saldo_awal" => $saldo_awal,
                "saldo_akhir" => $saldo_awal + $saldo
            ];
            $dataToPenyimpananTabungan = [
                "id_user"       => $data->id_user_pencairan,
                "id_tabungan"   => $data->id_pencairan,
                "status"        => "Pencairan Mudharabah Berjangka",
                "transaksi"     => $detailToPenyimpananTabungan,
                "teller"        => Auth::user()->id
            ];
            $saveToPenyimpananTabungan = $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
            
            if($saveToPenyimpananTabungan == "success")
            {
                if($data->teller != "teller")
                {
                    $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id);
                }
                $tabunganPencairan = $this->tabunganReporsitory->findTabungan($id=$data->id_pencairan, $id_tabungan="");
                $bmtTabunganPencairan = $this->tabunganReporsitory->findRekening($kategori_rekening="TABUNGAN", $id_tabungan="", $nama_rekening=$tabunganPencairan->jenis_tabungan);
                foreach($bmtTabunganPencairan as $bmtTabunganPencairan)
                {
                    $bmtTabunganPencairan = BMT::where('id_rekening', $bmtTabunganPencairan['id'])->first();
                }
                
                if($data->teller == null)
                {
                    $depositoDicairkan = Deposito::where('id_deposito', json_decode($pengajuan->detail)->id_deposito)->first();
                    $bmtDepositoDicairkan = BMT::where('id_rekening', $depositoDicairkan->id_rekening)->first();
                    $jumlah = json_decode($pengajuan->detail)->jumlah;
                    $id_pengajuan = $pengajuan->id;
                    $teller = "";
                }
                else
                {
                    $depositoDicairkan = Deposito::where('id_deposito', $data->idRek)->first();
                    $bmtDepositoDicairkan = BMT::where('id_rekening', $depositoDicairkan->id_rekening)->first();
                    $jumlah = $data->saldo;
                    $id_pengajuan = null;
                    $teller = "teller";
                }

                $detailToPenyimpananDeposito = [
                    "teller"        => Auth::user()->id,
                    "dari_rekening" => $data->idRek,
                    "untuk_rekening"=> $data->id_pencairan,
                    "jumlah"        => $jumlah,
                    "saldo_awal"    => $bmtDepositoDicairkan->saldo,
                    "saldo_akhir"   => floatval($bmtDepositoDicairkan->saldo) - floatval($jumlah)
                ];
                $dataToPenyimpananDeposito = [
                    "id_user"       => $data->id_user_pencairan,
                    "id_deposito"   => $depositoDicairkan->id,
                    "status"        => "Pencairan Deposito",
                    "transaksi"     => $detailToPenyimpananDeposito,
                    "teller"        => Auth::user()->id
                ];
                
                $detailToPenyimpananBMT = [
                    "jumlah"    => $jumlah,
                    "saldo_awal"    => $bmtDepositoDicairkan->saldo,
                    "saldo_akhir"   => floatval($bmtDepositoDicairkan->saldo) - floatval($jumlah),
                    "id_pengajuan"  => $id_pengajuan
                ];
                $dataToPenyimpananBMT = [
                    "id_user"   => $data->id_user_pencairan,
                    "id_bmt"    => $bmtDepositoDicairkan->id,
                    "status"    => "Pencairan Deposito",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];

                $insertToPenyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                $insertToPenyimpananDeposito = $this->insertToPenyimpananDeposito($dataToPenyimpananDeposito);

                if($insertToPenyimpananBMT == "success" && $insertToPenyimpananDeposito == "success")
                {
                    
                    $detailToPenyimpananBMT['saldo_awal'] = $bmtTabunganPencairan->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = floatval($bmtTabunganPencairan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                    $dataToPenyimpananBMT['id_bmt'] = $bmtTabunganPencairan->id;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $dataToUpdateBMTDeposito = [
                        "bmtBankTujuan" => $bmtTabunganPencairan,
                        "bmtDeposito"   => $bmtDepositoDicairkan,
                        "jumlah"        => $jumlah,
                        "id_pengajuan"  => $id_pengajuan
                    ];
                    $updateBMTDeposito = $this->updateBMTDeposito($dataToUpdateBMTDeposito, $teller=$teller, $status="pencairan");

                    // Update deposito status
                    $updateDeposito = Deposito::where('id_deposito', $data->id_deposito)->update([ 'status' => 'closed']);

                    // Update saldo in tabungan table
                    $detailTabungan = [
                        "saldo" => $saldo_awal + $saldo,
                        "id_pengajuan" => $data->id
                    ];
                    $updateTabungan = Tabungan::where('id', $data->id_pencairan)->update([ 'detail' => json_encode($detailTabungan) ]);

                    // update BMT deposito

                    // Update pengajuan status in table pengajuan
                    if($data->teller != "teller") {
                        $updatePengajuan = Pengajuan::where('id', $data->id)->update([
                            "status"    => "Sudah Dikonfirmasi",
                            "teller"    => Auth::user()->id
                        ]);
                    }

                    if($updateDeposito && $updateTabungan && $updateBMTDeposito)
                    {
                        DB::commit();
                        $response = array("type" => "success", "message" => "Pencairan Deposito Berhasil Dikonfirmasi");
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pencairan Deposito Gagal Dilakukan");
                    }
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pencairan Deposito Gagal Dilakukan");
                }
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pencairan Deposito Gagal Dilakukan");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pencairan Deposito Gagal Dilakukan");
        }
        
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

    /** 
     * Confirm Pengajuan Deposito
     * @return Response
    */
    public function confirmPengajuan($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data['id_']);
            $statement = DB::select("SHOW TABLE STATUS LIKE 'deposito'");
            $nextId = $statement[0]->Auto_increment;
            if($this->insertToDeposito($pengajuan)['type'] == "success")
            {
                if(json_decode($pengajuan->detail)->kredit == "Tunai")
                {
                    $untukRekening = json_decode(Auth::user()->detail)->id_rekening;
                    $bmtBankTujuan = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first();
                }
                if(json_decode($pengajuan->detail)->kredit == "Transfer")
                {
                    $untukRekening = json_decode($pengajuan->detail)->bank_bmt_tujuan;
                    $bmtBankTujuan = BMT::where('id_rekening', json_decode($pengajuan->detail)->bank_bmt_tujuan)->first();
                }
                
                $detailToPenyimpananDeposito = [
                    "teller"    => Auth::user()->id,
                    "dari_rekening" => "",
                    "untuk_rekening" => $untukRekening,
                    "jumlah"    => json_decode($pengajuan->detail)->jumlah,
                    "saldo_awal"    => 0,
                    "saldo_akhir"   => json_decode($pengajuan->detail)->jumlah
                ];
                $dataToPenyimpananDeposito = [
                    "id_user"       => $pengajuan->id_user,
                    "id_deposito"   => $nextId,
                    "status"        => "Deposit Awal",
                    "transaksi"     => $detailToPenyimpananDeposito,
                    "teller"        => Auth::user()->id
                ];
                $insertToPenyimpananDeposito = $this->insertToPenyimpananDeposito($dataToPenyimpananDeposito);

                $bmtDeposito = BMT::where('id_rekening', $pengajuan->id_rekening)->first();
                $detailToPenyimpananBMT = [
                    "jumlah"    => json_decode($pengajuan->detail)->jumlah,
                    "saldo_awal"    => 0,
                    "saldo_akhir"   => json_decode($pengajuan->detail)->jumlah,
                    "id_pengajuan"  => $pengajuan->id
                ];
                $dataToPenyimpananBMT = [
                    "id_user"   => $pengajuan->id_user,
                    "id_bmt"    => $bmtDeposito->id,
                    "status"    => "Deposit Awal",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];
                $insertToPenyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                if(json_decode($pengajuan->detail)->kredit == "Transfer")
                {
                    $dataToUpdateBMTDeposito = [
                        "bmtBankTujuan" => $bmtBankTujuan,
                        "bmtDeposito"   => $bmtDeposito,
                        "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                        "id_pengajuan"  => $pengajuan->id
                    ];
                }
                if(json_decode($pengajuan->detail)->kredit == "Tunai")
                {
                    $dataToUpdateBMTDeposito = [
                        "bmtBankTujuan" => $bmtBankTujuan,
                        "bmtDeposito"   => $bmtDeposito,
                        "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                        "id_pengajuan"  => $pengajuan->id
                    ];
                }

                if($insertToPenyimpananBMT == "success" && $insertToPenyimpananDeposito == "success")
                {

                    $detailToPenyimpananBMT['saldo_awal'] = $bmtBankTujuan->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = floatval($bmtBankTujuan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                    $dataToPenyimpananBMT['id_bmt'] = $bmtBankTujuan->id;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $updateDataBMT = $this->updateBMTDeposito($dataToUpdateBMTDeposito);

                    if($updateDataBMT)
                    {
                        DB::commit();
                        return array("type" => "success", "message" => "Pengajuan Pembukaan Deposito Berhasil Dikonfirmasi");
                    }
                    else
                    {
                        DB::rollback();
                        return array("type" => "error", "message" => "Pengajuan Pembukaan Deposito Gagal Dikonfirmasi");
                    }
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Pembukaan Deposito Gagal Dikonfirmasi");
                }
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Pembukaan Deposito Gagal Dikonfirmasi");
            }
        }
        catch(Excaption $ex)
        {
            DB::rollback();
            $response =  array("type" => "error", "message" => "Pengajuan Pembukaan Deposito Gagal Dikonfirmasi");
        }

        return $response;
    }

    /** 
     * Insert to deposito table
     * This is for pembukaan deposito awal
     * @return Response
    */
    public function insertToDeposito($data)
    {
        if($data['teller'] != null && $data['teller'] == "teller")
        {
            $nextId = $data['id'];
            $id_deposito = $data['id_deposito'];
            $id_rekening = $data['id_rekening'];
            $id_user = $data['id_user'];
            $id_pengajuan = $data['id_pengajuan'];
            $jenis_deposito = $data['jenis_deposito'];
            $detailToDeposito = $data['detail'];
        }
        else
        {
            // Getting last id recorded in db and get the next auto increment
            $statement = DB::select("SHOW TABLE STATUS LIKE 'deposito'");
            $nextId = $statement[0]->Auto_increment;

            $id_deposito = $data->id_user . "." . $nextId;
            $id_rekening = json_decode($data->detail)->deposito;
            $id_user = $data->id_user;
            $id_pengajuan = $data->id;
            $jenis_deposito = json_decode($data->detail)->nama_rekening;
            $detailToDeposito = [
                "id_pengajuan"  => $data->id,
                "perpanjangan_otomatis" => json_decode($data->detail)->perpanjangan_otomatis,
                "atasnama" => json_decode($data->detail)->atasnama,
                "nama" => json_decode($data->detail)->nama,
                "id" => json_decode($data->detail)->id,
                "jumlah" => json_decode($data->detail)->jumlah,
                "deposito" => json_decode($data->detail)->deposito,
                "keterangan" => json_decode($data->detail)->keterangan,
                "id_pencairan" => json_decode($data->detail)->id_pencairan,
                "kredit" => json_decode($data->detail)->kredit,
                "bank_bmt_tujuan" => json_decode($data->detail)->bank_bmt_tujuan,
                "path_bukti" => json_decode($data->detail)->path_bukti,
                "nama_rekening" => json_decode($data->detail)->nama_rekening,
                "saldo" => json_decode($data->detail)->jumlah
            ];
        }

        $rekeningDeposito = Rekening::where('nama_rekening', json_decode($data->detail)->nama_rekening)->first();
        $jatuh_tempo = Carbon::now()->addMonth(json_decode($rekeningDeposito->detail)->jangka_waktu)->format('Y-m-d');
        
        $deposito = new Deposito();
        $deposito->id = $nextId;
        $deposito->id_deposito = $id_deposito;
        $deposito->id_rekening = $id_rekening;
        $deposito->id_user = $id_user;
        $deposito->id_pengajuan = $id_pengajuan;
        $deposito->jenis_deposito = $jenis_deposito;
        $deposito->detail = json_encode($detailToDeposito);
        $deposito->tempo = $jatuh_tempo;
        $deposito->status = "active";
        
        if($deposito->save())
        {
            $response = array("type" => "success");
        }
        else
        {
            $response = array("type" => "error");
        }
        return $response;
    }

    /** 
     * Insert data to penyimpanan deposito table
     * This is for tracking history of deposito
     * @return Response
    */
    public function insertToPenyimpananDeposito($data)
    {
        $penyimpananDeposito = new PenyimpananDeposito();
        $penyimpananDeposito->id_user = $data['id_user'];
        $penyimpananDeposito->id_deposito = $data['id_deposito'];
        $penyimpananDeposito->status = $data['status'];
        $penyimpananDeposito->transaksi = json_encode($data['transaksi']);
        $penyimpananDeposito->teller = $data['teller'];
        
        if($penyimpananDeposito->save())
        {
            return "success";
        }
        else
        {
            return "error";
        }
    }

    /** 
     * Update data BMT
     * Excetude it when do every thing with deposito
     * @return Response
    */
    public function updateBMTDeposito($data, $teller="", $status="")
    {
        // Update bmt deposito
        if($data['bmtDeposito']->saldo == "") 
        { 
            $bmtDepositoSaldo = 0 ;
        }
        else
        {
            $bmtDepositoSaldo = $data['bmtDeposito']->saldo ;
        }
        if($status != "" && $status == 'pencairan')
        {
            $updateBMTDeposito = BMT::where('id', $data['bmtDeposito']->id)->update([
                "saldo" => $bmtDepositoSaldo - $data['jumlah']
            ]);
        }
        else
        {
            $updateBMTDeposito = BMT::where('id', $data['bmtDeposito']->id)->update([
                "saldo" => $bmtDepositoSaldo + $data['jumlah']
            ]);
        }

        // Update bmt bank/teller
        if($data['bmtBankTujuan']->saldo == "") 
        { 
            $bmtBankTujuanSaldo = 0 ;
        }
        else
        {
            $bmtBankTujuanSaldo = $data['bmtBankTujuan']->saldo ;
        }

        if($status != "" && $status == 'pencairan')
        {
            $updateBMTTeller = BMT::where('id', $data['bmtBankTujuan']->id)->update([
                "saldo" => $bmtBankTujuanSaldo + $data['jumlah']
            ]);
        }
        else
        {
            $updateBMTTeller = BMT::where('id', $data['bmtBankTujuan']->id)->update([
                "saldo" => $bmtBankTujuanSaldo + $data['jumlah']
            ]);
        }

        if($teller != "teller")
        {
            // Update pengajuan
            $updatePengajuan = Pengajuan::where('id', $data['id_pengajuan'])->update([
                "teller"    => Auth::user()->id,
                "status"    => "Disetujui"
            ]);
        }

        if($updateBMTDeposito && $updateBMTTeller)
        {
            return "success";
        }
        else
        {
            return "error";
        }
    }

    /** 
     * Open deposito direct to db without pengajuan
     * Excute it from teller page
     * @return Response
    */
    public function openDeposito($data)
    {
        DB::beginTransaction();
        try
        {
            $statement = DB::select("SHOW TABLE STATUS LIKE 'deposito'");
            $nextId = $statement[0]->Auto_increment;

            $pengajuanId = DB::select("SHOW TABLE STATUS LIKE 'pengajuan'");
            $nextIdPengajuan = $pengajuanId[0]->Auto_increment;

            $userDeposito = User::where('no_ktp', $data['nama_nasabah'])->first();
            $rekening = Rekening::where('id', $data->deposito_)->first();
            $bmt = BMT::where('id_rekening', $data->deposito_)->first();

            if($data->atasnama == 1)
            {
                $atasnama = "Pribadi";
                $kredit = "Tunai";
                $bmt_tujuan = json_decode(Auth::user()->detail)->id_rekening;
                $path_bukti = null;
            }
            if($data->atasnama == 2)
            {
                $atasnama = "Lembaga";
                $kredit = "Transfer";
                $bmt_tujuan = null;
                $path_bukti = null;
            }
            $detailToPengajuan = [
                "atasnama"  => $atasnama,
                "nama"      => $userDeposito->nama,
                "id"        => $userDeposito->id,
                "jumlah"    => preg_replace('/[^\d.]/', '', $data->jumlah),
                "deposito"  => $data->deposito_,
                "keterangan"=> $data->keterangan,
                "id_pencairan"=> $data->rek_tabungan,
                "kredit"    => $kredit,
                "bank_bmt_tujuan" => $bmt_tujuan,
                "path_bukti" => $path_bukti,
                "nama_rekening" => $rekening->nama_rekening
            ];
            $dataToPengajuan = [
                "teller"    => "teller",
                "id"        => $nextIdPengajuan,
                "id_user"   => $userDeposito->id,
                "id_rekening"   => $data->deposito_,
                "jenis_pengajuan" => "Buka Mudharabah Berjangka " . $rekening->nama_rekening,
                "status"    => "Disetujui",
                "kategori"  => "Deposito",
                "detail"    => $detailToPengajuan,
                "teller"    => Auth::user()->id
            ];

            $pengajuan = $this->pengajuanReporsitory->createPengajuan($dataToPengajuan);

            if($pengajuan['type'] == "success")
            {
                if(isset($data->perpanjang_otomatis) && $data->perpanjang_otomatis == "on")
                {
                    $perpanjang_otomatis = true;
                }
                else
                {
                    $perpanjang_otomatis = false;
                }
                $detailDeposito = [
                    "id_pengajuan"  => $nextIdPengajuan,
                    "perpanjangan_otomatis" => $perpanjang_otomatis,
                    "atasnama"  => $atasnama,
                    "nama"      => $userDeposito->nama,
                    "id"        => $userDeposito->id,
                    "jumlah"    => preg_replace('/[^\d.]/', '', $data->jumlah),
                    "deposito"  => $data->deposito_,
                    "keterangan"=> $data->keterangan,
                    "id_pencairan"=> $data->rek_tabungan,
                    "kredit"    => $kredit,
                    "bank_bmt_tujuan" => $bmt_tujuan,
                    "path_bukti" => $path_bukti,
                    "nama_rekening" => $rekening->nama_rekening,
                    "saldo"    => preg_replace('/[^\d.]/', '', $data->jumlah)
                ];
                $dataToDeposito = [
                    "id"    => $nextId,
                    "id_deposito"   => $userDeposito->id . "." . $nextId,
                    "id_rekening"    => $data->deposito_,
                    "id_user"       => $userDeposito->id,
                    "id_pengajuan"  => $nextIdPengajuan,
                    "jenis_deposito"=> $rekening->nama_rekening,
                    "detail"    => $detailDeposito,
                    "teller"    => "teller"
                ];

                if($this->insertToDeposito($dataToDeposito)["type"] == "success")
                {
                    $detailToPenyimpananDeposito = [
                        "teller"    => Auth::user()->id,
                        "dari_rekening" => "",
                        "untuk_rekening"    => json_decode(Auth::user()->detail)->id_rekening,
                        "jumlah"    => preg_replace('/[^\d.]/', '', $data->jumlah),
                        "saldo_awal"    => 0,
                        "saldo_akhir"   => preg_replace('/[^\d.]/', '', $data->jumlah)
                    ];
                    $dataToPenyimpananDeposito = [
                        "id_user"   => $userDeposito->id,
                        "id_deposito"   => $nextId,
                        "status"    => "Deposit Awal",
                        "transaksi" => $detailToPenyimpananDeposito,
                        "teller"    => Auth::user()->id
                    ];

                    $detailToPenyimpananBMT = [
                        "jumlah"    => preg_replace('/[^\d.]/', '', $data->jumlah),
                        "saldo_awal"    => 0,
                        "saldo_akhir"   => preg_replace('/[^\d.]/', '', $data->jumlah),
                        "id_pengajuan"  => $nextIdPengajuan
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user"   => $userDeposito->id,
                        "id_bmt"    => $bmt->id,
                        "status"    => "Deposit Awal",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller"    => Auth::user()->id
                    ];

                    if(
                        $this->insertToPenyimpananDeposito($dataToPenyimpananDeposito) == "success" &&
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                    )
                    {
                        $BMTTeller = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first();

                        $detailToPenyimpananBMT['saldo_awal'] = $BMTTeller->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = floatval($BMTTeller->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                        $dataToPenyimpananBMT['id_bmt'] = $BMTTeller->id;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        // Update saldo BMT bank/teller
                        $updateBMTTeller = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->update([
                            "saldo" => $BMTTeller->saldo + preg_replace('/[^\d.]/', '', $data->jumlah)
                        ]);

                        // Update saldo rekening deposito
                        $saldoBMTDeposito = BMT::where('id_rekening', $data->deposito_)->first();
                        $updateBMTDeposito = BMT::where('id_rekening', $data->deposito_)->update([
                            "saldo" => $saldoBMTDeposito->saldo + preg_replace('/[^\d.]/', '', $data->jumlah)
                        ]);

                        if($updateBMTTeller && $updateBMTDeposito)
                        {
                            DB::commit();
                            $response = array("type" => "success", "message" => "Pembukaan deposito berhasil dilakukan.");
                        }
                        else
                        {
                            DB::rollback();
                            $response = array("type" => "error", "message" => "Pembukaan deposito gagal 1.");
                        }
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pembukaan deposito gagal 2.");
                    }
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pembukaan deposito gagal 2.");
                }
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pembukaan deposito gagal 2.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pembukaan deposito gagal 3.");
        }
        
        return $response;
    }

    /** 
     * Confirm Pengajuan Deposito
     * @return Response
    */
    public function confirmPePerpanjangan($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data['id_']);

            $statement = DB::select("SHOW TABLE STATUS LIKE 'deposito'");
            $nextId = $statement[0]->Auto_increment;
            // if($this->insertToDeposito($pengajuan)['type'] == "success")
            // {
                
                $depositoDiperpanjang = Deposito::where('id_deposito', json_decode($pengajuan->detail)->id_deposito)->first();
                $tabunganPencairan = Tabungan::where('id', json_decode($depositoDiperpanjang->detail)->id_pencairan)->first();
                $bmtTabunganPencairan = BMT::where('id_rekening', $tabunganPencairan->id_rekening)->first();

                $untukRekening = json_decode($pengajuan->detail)->id_rekening_baru;
                $bmtDepositoTujuan = BMT::where('id_rekening', $untukRekening)->first();
                
                $dariRekening = json_decode($pengajuan->detail)->id_rekening_lama;
                $bmtDepositoDicairkan = BMT::where('id_rekening', $dariRekening)->first();
                
                
                $detailToPenyimpananDeposito = [
                    "teller"    => Auth::user()->id,
                    "dari_rekening" => $dariRekening,
                    "untuk_rekening" => $untukRekening,
                    "jumlah"    => json_decode($pengajuan->detail)->jumlah,
                    "saldo_awal"    => $bmtDepositoTujuan->saldo,
                    "saldo_akhir"   => $bmtDepositoTujuan->saldo + json_decode($pengajuan->detail)->jumlah
                ];
                $dataToPenyimpananDeposito = [
                    "id_user"       => $pengajuan->id_user,
                    "id_deposito"   => $nextId,
                    "status"        => "Perpanjangan Deposito",
                    "transaksi"     => $detailToPenyimpananDeposito,
                    "teller"        => Auth::user()->id
                ];

                $jumlahSaldoDiperpanjang = json_decode($pengajuan->detail)->jumlah;
                $saldoDeposito = json_decode($pengajuan->detail)->saldo;
                $jumlahSaldoTidakDiperpanjang = $saldoDeposito - $jumlahSaldoDiperpanjang;
                $lama_perpanjangan = explode(" ", json_decode($pengajuan->detail)->lama)[0];

                $detailToDeposito = [
                    "id_pengajuan" => $pengajuan->id,
                    "perpanjangan_otomatis" => true,
                    "atasnama" => "Pribadi",
                    "nama" => "demo",
                    "id" => 59,
                    "jumlah" => "2000000",
                    "deposito" => "48",
                    "keterangan" => null,
                    "id_pencairan" => "9",
                    "kredit" => "Tunai",
                    "bank_bmt_tujuan" => null,
                    "path_bukti" => null,
                    "nama_rekening" => "MUDHARABAH 1 BULAN",
                    "saldo" => "2000000"
                ];
                $dataToDeposito = [
                    "id_rekening"   => $bmtDepositoTujuan->id_rekening,
                    "jenis_deposito"=> $bmtDepositoTujuan->nama
                ];
                $response = $detailToDeposito;
            //     $insertToPenyimpananDeposito = $this->insertToPenyimpananDeposito($dataToPenyimpananDeposito);

            //     $bmtDeposito = BMT::where('id_rekening', $pengajuan->id_rekening)->first();
            //     $detailToPenyimpananBMT = [
            //         "jumlah"    => json_decode($pengajuan->detail)->jumlah,
            //         "saldo_awal"    => 0,
            //         "saldo_akhir"   => json_decode($pengajuan->detail)->jumlah,
            //         "id_pengajuan"  => $pengajuan->id
            //     ];
            //     $dataToPenyimpananBMT = [
            //         "id_user"   => $pengajuan->id_user,
            //         "id_bmt"    => $bmtDeposito->id,
            //         "status"    => "Deposit Awal",
            //         "transaksi" => $detailToPenyimpananBMT,
            //         "teller"    => Auth::user()->id
            //     ];
            //     $insertToPenyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            //     if(json_decode($pengajuan->detail)->kredit == "Transfer")
            //     {
            //         $dataToUpdateBMTDeposito = [
            //             "bmtBankTujuan" => $bmtBankTujuan,
            //             "bmtDeposito"   => $bmtDeposito,
            //             "jumlah"        => json_decode($pengajuan->detail)->jumlah,
            //             "id_pengajuan"  => $pengajuan->id
            //         ];
            //     }
            //     if(json_decode($pengajuan->detail)->kredit == "Tunai")
            //     {
            //         $dataToUpdateBMTDeposito = [
            //             "bmtBankTujuan" => $bmtBankTujuan,
            //             "bmtDeposito"   => $bmtDeposito,
            //             "jumlah"        => json_decode($pengajuan->detail)->jumlah,
            //             "id_pengajuan"  => $pengajuan->id
            //         ];
            //     }

            //     if($insertToPenyimpananBMT == "success" && $insertToPenyimpananDeposito == "success")
            //     {

            //         $detailToPenyimpananBMT['saldo_awal'] = $bmtBankTujuan->saldo;
            //         $detailToPenyimpananBMT['saldo_akhir'] = floatval($bmtBankTujuan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
            //         $dataToPenyimpananBMT['id_bmt'] = $bmtBankTujuan->id;
            //         $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

            //         $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            //         $updateDataBMT = $this->updateBMTDeposito($dataToUpdateBMTDeposito);

            //         if($updateDataBMT)
            //         {
            //             DB::commit();
            //             return array("type" => "success", "message" => "Pengajuan Pembukaan Deposito Berhasil Dikonfirmasi");
            //         }
            //         else
            //         {
            //             DB::rollback();
            //             return array("type" => "error", "message" => "Pengajuan Pembukaan Deposito Gagal Dikonfirmasi");
            //         }
            //     }
            //     else
            //     {
            //         DB::rollback();
            //         $response = array("type" => "error", "message" => "Pengajuan Pembukaan Deposito Gagal Dikonfirmasi");
            //     }
            // }
            // else
            // {
            //     DB::rollback();
            //     $response = array("type" => "error", "message" => "Pengajuan Pembukaan Deposito Gagal Dikonfirmasi");
            // }
        }
        catch(Excaption $ex)
        {
            DB::rollback();
            $response =  array("type" => "error", "message" => "Pengajuan Pembukaan Deposito Gagal Dikonfirmasi");
        }

        return $response;
    }
}