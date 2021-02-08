<?php

namespace App\Repositories;
use App\User;
use App\Pengajuan;
use App\Tabungan;
use App\Deposito;
use App\BMT;
use App\Rekening;
use App\SHU;
use App\PenyimpananSHU;
use App\PenyimpananDistribusi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\SimpananReporsitory;
use App\Repositories\DepositoReporsitories;
use App\PenyimpananBMT;
use Carbon\Carbon;

class SHUTahunanRepositories {

    public function __construct(RekeningReporsitories $rekeningReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                SimpananReporsitory $simpananReporsitory,
                                DepositoReporsitories $depositoReporsitory
                                ) 
    {
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->simpananReporsitory = $simpananReporsitory;
        $this->depositoReporsitory = $depositoReporsitory;
    }

    /** 
     * Get SHU tahunan untuk distribusi
     * @return Response
    */
    public function getDataDistribusiSHU()
    {
        $harta_anggota = array();
        $margin_anggota = array();
        $anggota = $this->getTotalHarta();
        foreach($anggota as $item)
        {
            array_push($harta_anggota, json_decode($item->wajib_pokok)->wajib + json_decode($item->wajib_pokok)->pokok + json_decode($item->wajib_pokok)->khusus + json_decode($item->wajib_pokok)->margin);
        }
        foreach ($anggota as $item){
            array_push($margin_anggota, json_decode($item->wajib_pokok)->margin);
        }
        $total_harta = array_sum($harta_anggota);
        $total_margin =  array_sum($margin_anggota);

        $distribusi = array();

        $data_shu = SHU::where('status', 'active')->get();


        foreach($data_shu as $item) {
            foreach($anggota as $value)
            {
                if($item->nama_shu == "ANGGOTA" && $value->role == "anggota" && $value->tipe =="anggota") {
                    $porsi_shu = $this->getPorsiSHU("ANGGOTA");
                    $harta_anggota = json_decode($value->wajib_pokok)->wajib + json_decode($value->wajib_pokok)->pokok + json_decode($value->wajib_pokok)->khusus + json_decode($value->wajib_pokok)->margin;
                    $margin_anggota = json_decode($value->wajib_pokok)->margin;
                    $dibagikan_ke_anggota = $harta_anggota > 0 && $total_harta > 0 ? $harta_anggota / $total_harta * (50/100) * $porsi_shu : 0;
                    $dibagikan_ke_anggota = $dibagikan_ke_anggota + ($margin_anggota > 0 && $total_margin > 0 ? $margin_anggota / $total_margin * (50/100) * $porsi_shu : 0);
                    $temp = array(
                        "no_ktp"    => $value->no_ktp,
                        "nama"  => $value->nama,
                        "account_type" => $item->nama_shu,
                        "simpanan_wajib" => json_decode($value->wajib_pokok)->wajib,
                        "margin" => json_decode($value->wajib_pokok)->margin,
                        "simpanan_pokok" => json_decode($value->wajib_pokok)->pokok,
                        "simpanan_khusus" => json_decode($value->wajib_pokok)->khusus,
                        "shu_anggota" => $dibagikan_ke_anggota,
                        "shu_pengelola" => 0,
                        "shu_pengurus"  => 0,
                        "id_rekening" => $item->id_rekening
                    );
                    array_push($distribusi, $temp);
                }

                if($item->nama_shu == "PENGURUS" && $value->role == "pengurus"  && $value->tipe =="anggota") {
                    $porsi_shu_pengurus = $this->getPorsiSHU("PENGURUS");
                    $porsi_shu_anggota = $this->getPorsiSHU("ANGGOTA");
                    $user = User::where([ ['status', '2'], ['role', 'pengurus'], ['tipe', 'anggota'] ])
                        ->orWhere([ ['status', '2'], ['tipe', 'anggota'], ['role', 'pengelolah&pengurus'] ])->get();
                    $harta_anggota = json_decode($value->wajib_pokok)->wajib + json_decode($value->wajib_pokok)->pokok + json_decode($value->wajib_pokok)->khusus + json_decode($value->wajib_pokok)->margin;
                    $dibagikan_ke_anggota = $harta_anggota > 0 && $total_harta > 0 ? $harta_anggota / $total_harta * (50/100) * $porsi_shu : 0;
                    $dibagikan_ke_anggota = $dibagikan_ke_anggota + ($margin_anggota > 0 && $total_margin > 0 ? $margin_anggota / $total_margin * (50/100) * $porsi_shu : 0);
                    $temp = array(
                        "no_ktp"    => $value->no_ktp,
                        "nama"  => $value->nama,
                        "account_type" => $item->nama_shu,
                        "simpanan_wajib" => json_decode($value->wajib_pokok)->wajib,
                        "margin" => json_decode($value->wajib_pokok)->margin,
                        "simpanan_pokok" => json_decode($value->wajib_pokok)->pokok,
                        "simpanan_khusus" => json_decode($value->wajib_pokok)->khusus,
                        "shu_anggota" => $dibagikan_ke_anggota, 
                        "shu_pengelola" => 0,
                        "shu_pengurus"  => $porsi_shu_pengurus / count($user),
                        "id_rekening" => $item->id_rekening
                    );
                    array_push($distribusi, $temp);
                }
                if($item->nama_shu == "PENGELOLAH" && $value->role == "pengelolah" && $value->tipe =="anggota") {
                    $porsi_shu_pengelolah = $this->getPorsiSHU("PENGELOLAH");
                    $porsi_shu_anggota = $this->getPorsiSHU("ANGGOTA");
                    $user = User::where([ ['status', '2'], ['role', 'pengelolah'], ['tipe', 'anggota'] ])
                        ->orWhere([ ['status', '2'], ['tipe', 'anggota'], ['role', 'pengelolah&pengurus'] ])->get();
                    $harta_anggota = json_decode($value->wajib_pokok)->wajib + json_decode($value->wajib_pokok)->pokok + json_decode($value->wajib_pokok)->khusus + json_decode($value->wajib_pokok)->margin;
                    $dibagikan_ke_anggota = $harta_anggota > 0 && $total_harta > 0 ? $harta_anggota / $total_harta * (50/100) * $porsi_shu : 0;
                    $dibagikan_ke_anggota = $dibagikan_ke_anggota + ($margin_anggota > 0 && $total_margin > 0 ? $margin_anggota / $total_margin * (50/100) * $porsi_shu : 0);
                    $temp = array(
                        "no_ktp"    => $value->no_ktp,
                        "nama"  => $value->nama,
                        "account_type" => $item->nama_shu,
                        "simpanan_wajib" => json_decode($value->wajib_pokok)->wajib,
                        "margin" => json_decode($value->wajib_pokok)->margin,
                        "simpanan_pokok" => json_decode($value->wajib_pokok)->pokok,
                        "simpanan_khusus" => json_decode($value->wajib_pokok)->khusus,
                        "shu_anggota" => $dibagikan_ke_anggota, 
                        "shu_pengelola" => $porsi_shu_pengelolah / count($user),
                        "shu_pengurus"  => 0,
                        "id_rekening" => $item->id_rekening
                    );
                    array_push($distribusi, $temp);
                }
                if ($item->nama_shu == "PENGELOLAH"  && $value->role == "pengelolah&pengurus" && $value->tipe =="anggota" )
                {
                    $porsi_shu_pengelolah = $this->getPorsiSHU("PENGELOLAH");
                    $porsi_shu_pengurus = $this->getPorsiSHU("PENGURUS");
                    $porsi_shu_anggota = $this->getPorsiSHU("ANGGOTA");
                    $userPengurus = User::where([ ['status', '2'], ['role', 'pengurus'], ['tipe', 'anggota'] ])
                        ->orWhere([ ['status', '2'], ['tipe', 'anggota'], ['role', 'pengelolah&pengurus'] ])->get();
                    $userPengelolah = User::where([ ['status', '2'], ['role', 'pengelolah'], ['tipe', 'anggota'] ])
                        ->orWhere([ ['status', '2'], ['tipe', 'anggota'], ['role', 'pengelolah&pengurus'] ])->get();
                    $harta_anggota = json_decode($value->wajib_pokok)->wajib + json_decode($value->wajib_pokok)->pokok + json_decode($value->wajib_pokok)->khusus + json_decode($value->wajib_pokok)->margin;
                    $dibagikan_ke_anggota = $harta_anggota > 0 && $total_harta > 0 ? $harta_anggota / $total_harta * (50/100) * $porsi_shu : 0;
                    $dibagikan_ke_anggota = $dibagikan_ke_anggota + ($margin_anggota > 0 && $total_margin > 0 ? $margin_anggota / $total_margin * (50/100) * $porsi_shu : 0);
                    $temp = array(
                        "no_ktp"    => $value->no_ktp,
                        "nama"  => $value->nama,
                        "account_type" => $item->nama_shu,
                        "simpanan_wajib" => json_decode($value->wajib_pokok)->wajib,
                        "margin" => json_decode($value->wajib_pokok)->margin,
                        "simpanan_pokok" => json_decode($value->wajib_pokok)->pokok,
                        "simpanan_khusus" => json_decode($value->wajib_pokok)->khusus,
                        "shu_anggota" => $dibagikan_ke_anggota,
                        "shu_pengelola" => $porsi_shu_pengelolah / count($userPengelolah),
                        "shu_pengurus"  => $porsi_shu_pengurus / count($userPengurus),
                        "id_rekening" => $item->id_rekening
                    );
                    array_push($distribusi, $temp);

                }
            }


            
            if($item->nama_shu !== "ANGGOTA" && $item->nama_shu !== "PENGELOLAH" && $item->nama_shu !== "PENGURUS") {
                $bmt = BMT::where('nama', $item->nama_shu)->first();
                $temp = array(
                    "no_ktp"    => "",
                    "nama"      => "",
                    "account_type" => $item->nama_shu,
                    "simpanan_wajib" => 0,
                    "simpanan_pokok"    => 0,
                    "simpanan_khusus" => 0,
                    "margin"        => 0,
                    "shu_anggota"   => 0,
                    "shu_pengelola" => 0,
                    "shu_pengurus"  => 0,
                    "id_rekening" => $item->id_rekening,
                    "porsi_shu" => $this->getPorsiSHU($item->nama_shu),
                    "nama_shu"  => $item->nama_shu
                );
                array_push($distribusi, $temp);

            }
        }




        return $distribusi;
    }

    /** 
     * Check status distribusi shu tahunan
     * @return Response
    */
    public function checkStatus()
    {
        $interval = Carbon::now()->subDays(366);
        $penyimpanan_shu = PenyimpananSHU::where('created_at', '>', $interval)->count();

        if($penyimpanan_shu > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /** 
     * Get porsi shu tahunan untuk setiap role
     * @return Response
    */
    public function getPorsiSHU($role)
    {
        $saldo_untuk_dibagikan = BMT::where('nama', 'SHU YANG HARUS DIBAGIKAN')->first();
        $role_porsi = SHU::where([ ['status', 'active'], ['nama_shu', $role] ])->first();
        $porsi = $role_porsi->persentase * $saldo_untuk_dibagikan->saldo;

        return $porsi;
    }

    /** 
     * Get total harta anggota
     * @return Response
    */
    public function getTotalHarta()
    {
        $user = User::where([ ['tipe', 'anggota'], ['status', '2'], ['is_active',1] ])->get();
        return $user;
    }

    /** 
     * Get data SHU tahunan
     * @return Response
    */
    public function getSHU()
    {
        $shu = SHU::where('status', 'active')->get();
        $data_shu = array();
        
        $bmt_shu_yang_harus_dibagikan = BMT::where('nama', 'SHU YANG HARUS DIBAGIKAN')->first();
        foreach($shu as $item) {
            array_push($data_shu, array(
                "nama_shu"     => $item->nama_shu,
                "persentase"    => $item->persentase * 100,
                "porsi"         => $this->getPorsiSHU($item->nama_shu),
                "yang_harus_dibagikan" => $bmt_shu_yang_harus_dibagikan->saldo
            ));
        }

        return $data_shu;
    }

    /** 
     * do pendistribusian shu tahunan
     * @return Response
    */
    public function doPendistribusian($data)
    {
        DB::beginTransaction();
        try
        {
            $data_distribusi = $this->getDataDistribusiSHU();
            $used = array();
            $bmt_shu_yang_harus_dibagikan = BMT::where('nama', 'SHU YANG HARUS DIBAGIKAN')->first();
            $bmt_shu_saldo = $bmt_shu_yang_harus_dibagikan->saldo;

            $dataPenyimpananDistribusi = [
                "id_user"       => Auth::user()->id,
                "status"        => "Distribusi SHU Persentase",
                "transaksi"     => $this->getSHU(),
                "teller"        => Auth::user()->id
            ];
            $this->insertPenyimpananSHU($dataPenyimpananDistribusi);


            $dataPenyimpananSHU = [
                "id_user"       => Auth::user()->id,
                "status"        => "Distribusi SHU",
                "transaksi"     => $this->getDataDistribusiSHU(),
                "teller"        => Auth::user()->id
            ];
            $this->insertPenyimpananSHU($dataPenyimpananSHU);





            foreach($data_distribusi as $item)
            {
                if($item['account_type'] == "ANGGOTA" || $item['account_type'] == "PENGELOLAH" || $item['account_type'] == "PENGURUS") 
                {
                    if($item['shu_anggota'] > 0 || $item['shu_pengelola'] > 0 || $item['shu_pengurus'] > 0)
                    {
                        $user = User::where('no_ktp', $item['no_ktp'])->first();
                        $tabungan = Tabungan::where('id_user', $user->id)->first();
                        $bmt_tabungan = BMT::where('id_rekening', $tabungan->id_rekening)->first();
                        $pendapatan_shu = $item['shu_anggota'] + $item['shu_pengelola'] + $item['shu_pengurus'];
                        $detailToPenyimpananTabungan = [
                            "teller"           => Auth::user()->id,
                            "dari_rekening"    => "",
                            "untuk_rekening"   => $tabungan->jenis_tabungan,
                            "jumlah"           => $pendapatan_shu,
                            "saldo_awal"       => json_decode($tabungan->detail)->saldo,
                            "saldo_akhir"      => json_decode($tabungan->detail)->saldo + $pendapatan_shu
                        ];
                        $dataPenyimpananTabungan = [
                            "nama_shu"      => $item['account_type'],
                            "id_user"       => $user->id,
                            "id_tabungan"   => $tabungan->id,
                            "status"        => "Distribusi SHU",
                            "transaksi"     => $detailToPenyimpananTabungan,
                            "teller"        => Auth::user()->id
                        ];
                        $this->tabunganReporsitory->insertPenyimpananTabungan($dataPenyimpananTabungan);
                        $dataToUpdateTabungan = array("saldo" => json_decode($tabungan->detail)->saldo + $pendapatan_shu, "id_pengajuan" => null);
                        $tabungan->detail = json_encode($dataToUpdateTabungan); $tabungan->save();

                        $detailToPenyimpananBMT = [
                            "jumlah"           => $pendapatan_shu,
                            "saldo_awal"       => $bmt_tabungan->saldo,
                            "saldo_akhir"      => $bmt_tabungan->saldo + $pendapatan_shu,
                            "id_pengajuan"     => null
                        ];
                        $dataPenyimpananBMT = [
                            "id_user"       => $user->id,
                            "id_bmt"        => $bmt_tabungan->id,
                            "status"        => "Distribusi SHU",
                            "transaksi"     => $detailToPenyimpananBMT,
                            "teller"        => Auth::user()->id
                        ];
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataPenyimpananBMT);

                        if(in_array($user->id, $used) == false)
                        {
                            array_push($used, $user->id);
                            
                            $bmt_tabungan->saldo = $bmt_tabungan->saldo + $pendapatan_shu;
                            $bmt_tabungan->save();

                            $dataToUpdateUser = array(
                                "wajib" => json_decode($user->wajib_pokok)->wajib,
                                "pokok" => json_decode($user->wajib_pokok)->pokok,
                                "khusus" => json_decode($user->wajib_pokok)->khusus,
                                "margin" => 0
                            );
                            $user->wajib_pokok = json_encode($dataToUpdateUser);
                            $user->save();
                        }   
                    }
                }
                else
                {
                    $bmt_rekening = BMT::where('nama', $item['account_type'])->first();
                    $detailToPenyimpananBMT = [
                        "jumlah"           => $item['porsi_shu'],
                        "saldo_awal"       => $bmt_rekening->saldo,
                        "saldo_akhir"      => $bmt_rekening->saldo + $item['porsi_shu'],
                        "id_pengajuan"     => null
                    ];
                    $dataPenyimpananBMT = [
                        "id_user"       => Auth::user()->id,
                        "id_bmt"        => $bmt_rekening->id,
                        "status"        => "Distribusi SHU",
                        "transaksi"     => $detailToPenyimpananBMT,
                        "teller"        => Auth::user()->id
                    ];
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataPenyimpananBMT);
                    
                    $bmt_rekening->saldo = $bmt_rekening->saldo + $item['porsi_shu'];
                    $bmt_rekening->save();
                }
            }

            // $bmt_shu_yang_harus_dibagikan->saldo = 0;
            $detailToPenyimpananBMT = [
                "jumlah"           => $bmt_shu_yang_harus_dibagikan->saldo,
                "saldo_awal"       => $bmt_shu_yang_harus_dibagikan->saldo,
                "saldo_akhir"      => 0,
                "id_pengajuan"     => null
            ];
            $dataPenyimpananBMT = [
                "id_user"       => Auth::user()->id,
                "id_bmt"        => $bmt_shu_yang_harus_dibagikan->id,
                "status"        => "Distribusi SHU",
                "transaksi"     => $detailToPenyimpananBMT,
                "teller"        => Auth::user()->id
            ];

            $this->rekeningReporsitory->insertPenyimpananBMT($dataPenyimpananBMT);
            $bmt_shu_yang_harus_dibagikan->saldo = 0; $bmt_shu_yang_harus_dibagikan->save();

            DB::commit();
            $response = array("type" => "success", "message" => "Pendistribusian SHU Berhasil Dilakukan.");
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pendistribusian SHU Gagal Dilakukan.");
        }

        return $response;
    }

    /** 
     * Insert pendistribusian shu history
     * @return Response
    */
    public function insertPenyimpananSHU($data)
    {
        $penyimpanan = new PenyimpananSHU();
        $penyimpanan->id_user = $data['id_user'];
        $penyimpanan->status = $data['status'];
        $penyimpanan->transaksi = json_encode($data['transaksi']);
        $penyimpanan->save();
    }

    /** 
     * Get SHU tahunan history
     * @return Response
    */
    public function getHistorySHU($date)
    {
        if($date !== "") {
            $date = Carbon::parse($date);
            $history = PenyimpananSHU::whereYear('created_at', $date->startOfYear())->get();
        }
        else {
            $date = Carbon::now();
            $history = PenyimpananSHU::whereYear('created_at', $date->startOfYear())->get();
        }
        return $history;
    }

}