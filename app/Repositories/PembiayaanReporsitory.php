<?php

namespace App\Repositories;

use App\Http\Controllers\HomeController;
use App\Pembiayaan;
use App\PenyimpananPembiayaan;
use App\User;
use App\Rekening;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\HelperRepositories;
use Illuminate\Support\Facades\DB;
use App\BMT;
use Carbon\Carbon;
use App\Pengajuan;
use App\Tabungan;
use App\PenyimpananJaminan;
use App\Repositories\ExportRepositories;

class PembiayaanReporsitory {

    public function __construct(PengajuanReporsitories $pengajuanReporsitory,
                                RekeningReporsitories $rekeningReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                HelperRepositories $helperRepository,
                                ExportRepositories $exportRepository
    )
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->helperRepository = $helperRepository;
        $this->exportRepository = $exportRepository;
    }

    /** 
     * Ambil data pembiayaan
     * @return Array
    */
    public function getPembiayaan($date="")
    {
        if($date=="") {
            $pembiayaan = Pembiayaan::with('user')->get();
        }
        else
        {
            $pembiayaan = Pembiayaan::with('user')->where([ 
                    ['created_at', '>', Carbon::parse($date['start'])->startOfDay()],
                    ['created_at', '<', Carbon::parse($date['end'])->endOfDay() ]
                ])->get();
        }

        return $pembiayaan;
    }

    /** 
     * Ambil data pembiayaan specific user
     * @return Array
    */
    public function getPembiayaanSpecificUser($custom_id="", $status="")
    {
        $pembiayaan = Pembiayaan::where("id_user", Auth::user()->id)->get();
        if($custom_id != "" && $status == "")
        {
            $pembiayaan = Pembiayaan::where("id_user", $custom_id)->get();
        } 
        elseif($custom_id != "" && $status != "")
        {
            $pembiayaan = Pembiayaan::select(['pembiayaan.*', 'users.nama', 'users.no_ktp'])->where([ ["pembiayaan.id_user", $custom_id], ['pembiayaan.status', $status] ])->join('users', 'pembiayaan.id_user', 'users.id')->get();
        }
        return $pembiayaan;
    }

    /** 
     * Konfirmasi pengajuan pembiayaan MRB anggota
     * @return Response
    */
    public function confirmPembiayaanMRB($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_);

            $jenis_pembiayaan = "PEMBIAYAAN MRB";
            $id_rekening_pembiayaan = 100;

            $bmt_pembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->first();
            $bmt_pengirim = BMT::where('id_rekening', $data->bank)->first();
            $bmt_piutang_MRB = BMT::where('id_rekening', '101')->first();

            $created_date = Carbon::now();
            $jenis_tempo = explode(" ", json_decode($pengajuan->detail)->keterangan)[1];

            if($jenis_tempo == "Bulan")
            {
                $tempo = explode(" ", json_decode($pengajuan->detail)->keterangan)[0];
            }
            if($jenis_tempo == "Hari")
            {
                $tempo = 1;
            }
            if($jenis_tempo == "Tahun")
            {
                $tempo = explode(" ", json_decode($pengajuan->detail)->keterangan)[0] * 12;
            }
            if($jenis_tempo == "Pekan")
            {
                $tempo = 1;
            }

            $pinjaman = json_decode($pengajuan->detail)->jumlah;
            $nisbah = $data->nisbah / 100;
            $margin = $pinjaman * $nisbah * $tempo;

            $saldo_awal_pengirim = floatval($bmt_pengirim->saldo);
            $saldo_akhir_pengirim = floatval($bmt_pengirim->saldo) - floatval($pinjaman);
            
            $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
            $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) + (floatval($pinjaman) + floatval($margin));

            $saldo_awal_piutang_mrb = floatval($bmt_piutang_MRB->saldo);
            $saldo_akhir_piutang_mrb = floatval($bmt_piutang_MRB->saldo) - floatval($margin);

            if($saldo_awal_pengirim > $pinjaman)
            {
                $statement = DB::select("SHOW TABLE STATUS LIKE 'pembiayaan'");
                $nextId = $statement[0]->Auto_increment;

                $detailToPembiayaan = [
                    "pinjaman"          => $pinjaman,
                    "margin"            => $margin,
                    "nisbah"            => $nisbah,
                    "total_pinjaman"    => floatval($pinjaman) + floatval($margin),
                    "sisa_angsuran"     => $pinjaman,
                    "sisa_margin"       => $margin,
                    "sisa_pinjaman"     => floatval($pinjaman) + floatval($margin),
                    "angsuran_pokok"    => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                    "lama_angsuran"     => $tempo,
                    "angsuran_ke"       => 0,
                    "tagihan_bulanan"   => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                    "jumlah_angsuran_bulanan"  => round($pinjaman / $tempo), 
                    "jumlah_margin_bulanan"    => round($margin / $tempo),
                    "sisa_ang_bln"      => 0,
                    "sisa_mar_bln"      => 0,
                    "kelebihan_angsuran_bulanan" => 0,
                    "kelebihan_margin_bulanan" => 0,
                    "id_pengajuan"      => $pengajuan->id
                ];
                $dataToPembiayaan = [
                    "id"                => $nextId,
                    "id_pembiayaan"     => $pengajuan->id_user . "." . $nextId,
                    "id_rekening"       => $id_rekening_pembiayaan,
                    "id_user"           => $pengajuan->id_user,
                    "id_pengajuan"      => $pengajuan->id,
                    "jenis_pembiayaan"  => $jenis_pembiayaan,
                    "detail"            => $detailToPembiayaan,
                    "tempo"             => $created_date->addMonth(1),
                    "status"            => "active",
                    "status_angsuran"   => 0,
                    "angsuran_ke"       => 0
                ];
                $detailToPenyimpananPembiayaan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $data->bank,
                    "untuk_rekening"    => "Tunai",
                    "angsuran_pokok"    => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                    "angsuran_ke"       => 0,
                    "nisbah"            => $nisbah,
                    "margin"            => $margin,
                    "jumlah"            => $pinjaman,
                    "tagihan"           => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                    "sisa_angsuran"     => $pinjaman,
                    "sisa_margin"       => $margin,
                    "sisa_pinjaman"     => floatval($pinjaman) + floatval($margin)
                ];
                $dataToPenyimpananPembiayaan = [
                    "id_user"       => $pengajuan->id_user,
                    "id_pembiayaan" => $nextId,
                    "status"        => "Pencairan Pembiayaan",
                    "transaksi"     => $detailToPenyimpananPembiayaan,
                    "teller"        => Auth::user()->id
                ];
                $detailToPenyimpananBMT = [
                    "jumlah"        => floatval(json_decode($pengajuan->detail)->jumlah) + floatval($margin),
                    "saldo_awal"    => $saldo_awal_pembiayaan,
                    "saldo_akhir"   => $saldo_akhir_pembiayaan,
                    "id_pengajuan"  => $data->id_
                ];
                $dataToPenyimpananBMT = [
                    "id_user"   => $pengajuan->id_user,
                    "id_bmt"    => $bmt_pembiayaan->id,
                    "status"    => "Pencairan Pembiayaan",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];
                
                if($this->insertPembiayaan($dataToPembiayaan) == "success" &&
                   $this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                   $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                )
                {
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                    $detailToPenyimpananBMT['jumlah'] = -floatval(json_decode($pengajuan->detail)->jumlah);
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $dataToPenyimpananBMT['id_bmt'] = $bmt_piutang_MRB->id;
                    $detailToPenyimpananBMT['jumlah'] = floatval($margin);
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_piutang_mrb;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_piutang_mrb;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                    
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $updateBMTPengirim = BMT::where('id_rekening', $data->bank)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                    $updateBMTPembiayaan = BMT::where('id_rekening',  json_decode($pengajuan->detail)->pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                    $updateBMTPiutangMRB = BMT::where('id_rekening', '101')->update([ "saldo" => $saldo_akhir_piutang_mrb ]);
                    $updatePengajuan = Pengajuan::where('id', $data->id_)->update([ 'status' => 'Sudah Dikonfirmasi', 'teller' => Auth::user()->id ]);
                    
                    $this->exportPerjanjian($dataToPembiayaan, $data);

                    DB::commit();
                    $response = array("type" => "success", "message" => "Pengajuan " . $jenis_pembiayaan . " Berhasil Dikonfirmasi.");    

                }
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Saldo " . $bmt_pengirim->nama . " Tidak Cukup.");
            }

        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan " . $jenis_pembiayaan . " Gagal Dikonfirmasi");
        }
        return $response;
    }

    /** 
     * Konfirmasi pengajuan pembiayaan MDA anggota
     * @return Response
    */
    public function confirmPembiayaanLain($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_);

            if($pengajuan->id_rekening == 99)
            {
                $jenis_pembiayaan = "PEMBIAYAAN MDA";
                $id_rekening_pembiayaan = 99;
            }
            elseif($pengajuan->id_rekening == 102)
            {
                $jenis_pembiayaan = "PEMBIAYAAN QORD";
                $id_rekening_pembiayaan = 102;
            }

            $bmt_pembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->first();
            $bmt_pengirim = BMT::where('id_rekening', $data->bank)->first();
            
            $created_date = Carbon::now();
            $jenis_tempo = explode(" ", json_decode($pengajuan->detail)->keterangan)[1];

            if($jenis_tempo == "Bulan")
            {
                $tempo = explode(" ", json_decode($pengajuan->detail)->keterangan)[0];
            }
            if($jenis_tempo == "Hari")
            {
                $tempo = 1;
            }
            if($jenis_tempo == "Tahun")
            {
                $tempo = explode(" ", json_decode($pengajuan->detail)->keterangan)[0] * 12;
            }
            if($jenis_tempo == "Pekan")
            {
                $tempo = 1;
            }

            $pinjaman = json_decode($pengajuan->detail)->jumlah;
            $nisbah = $data->nisbah / 100;
            $margin = $pinjaman * $nisbah * $tempo;

            $saldo_awal_pengirim = floatval($bmt_pengirim->saldo);
            $saldo_akhir_pengirim = floatval($bmt_pengirim->saldo) - floatval($pinjaman);
            
            $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
            $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) + floatval($pinjaman);

            if($saldo_awal_pengirim > $pinjaman)
            {
                $statement = DB::select("SHOW TABLE STATUS LIKE 'pembiayaan'");
                $nextId = $statement[0]->Auto_increment;

                $detailToPembiayaan = [
                    "pinjaman"          => $pinjaman,
                    "margin"            => $margin,
                    "nisbah"            => $nisbah,
                    "total_pinjaman"    => floatval($pinjaman),
                    "sisa_angsuran"     => $pinjaman,
                    "sisa_margin"       => $margin,
                    "sisa_pinjaman"     => floatval($pinjaman),
                    "angsuran_pokok"    => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                    "lama_angsuran"     => $tempo,
                    "angsuran_ke"       => 0,
                    "tagihan_bulanan"   => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                    "jumlah_angsuran_bulanan"  => round($pinjaman / $tempo), 
                    "jumlah_margin_bulanan"    => round($margin / $tempo),
                    "sisa_ang_bln"      => 0,
                    "sisa_mar_bln"      => 0,
                    "kelebihan_angsuran_bulanan" => 0,
                    "kelebihan_margin_bulanan" => 0,
                    "id_pengajuan"      => $pengajuan->id
                ];
                $dataToPembiayaan = [
                    "id"                => $nextId,
                    "id_pembiayaan"     => $pengajuan->id_user . "." . $nextId,
                    "id_rekening"       => $id_rekening_pembiayaan,
                    "id_user"           => $pengajuan->id_user,
                    "id_pengajuan"      => $pengajuan->id,
                    "jenis_pembiayaan"  => $jenis_pembiayaan,
                    "detail"            => $detailToPembiayaan,
                    "tempo"             => $created_date->addMonth(1),
                    "status"            => "active",
                    "status_angsuran"   => 0,
                    "angsuran_ke"       => 0
                ];

                $detailToPenyimpananPembiayaan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $data->bank,
                    "untuk_rekening"    => "Tunai",
                    "angsuran_pokok"    => round(floatval($pinjaman / $tempo)),
                    "angsuran_ke"       => 0,
                    "nisbah"            => $nisbah,
                    "margin"            => $margin,
                    "jumlah"            => $pinjaman,
                    "tagihan"           => round(floatval($pinjaman / $tempo)),
                    "sisa_angsuran"     => $pinjaman,
                    "sisa_margin"       => $margin,
                    "sisa_pinjaman"     => floatval($pinjaman)
                ];
                $dataToPenyimpananPembiayaan = [
                    "id_user"       => $pengajuan->id_user,
                    "id_pembiayaan" => $nextId,
                    "status"        => "Pencairan Pembiayaan",
                    "transaksi"     => $detailToPenyimpananPembiayaan,
                    "teller"        => Auth::user()->id
                ];
                
                $detailToPenyimpananBMT = [
                    "jumlah"        => floatval(json_decode($pengajuan->detail)->jumlah),
                    "saldo_awal"    => $saldo_awal_pembiayaan,
                    "saldo_akhir"   => $saldo_akhir_pembiayaan,
                    "id_pengajuan"  => $data->id_
                ];
                $dataToPenyimpananBMT = [
                    "id_user"   => $pengajuan->id_user,
                    "id_bmt"    => $bmt_pembiayaan->id,
                    "status"    => "Pencairan Pembiayaan",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];
                
                if($this->insertPembiayaan($dataToPembiayaan) == "success" &&
                   $this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                   $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                )
                {
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                    $detailToPenyimpananBMT['jumlah'] = -floatval(json_decode($pengajuan->detail)->jumlah);
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $updateBMTPengirim = BMT::where('id_rekening', $data->bank)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                    $updateBMTPembiayaan = BMT::where('id_rekening',  json_decode($pengajuan->detail)->pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                    $updatePengajuan = Pengajuan::where('id', $data->id_)->update([ 'status' => 'Sudah Dikonfirmasi', 'teller' => Auth::user()->id ]);

                    $this->exportPerjanjian($dataToPembiayaan, $data);

                    DB::commit();
                    $response = array("type" => "success", "message" => "Pengajuan " . $jenis_pembiayaan . " Berhasil Dikonfirmasi.");    
                }
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Saldo " . $bmt_pengirim->nama . " Tidak Cukup.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan " . $jenis_pembiayaan . " Gagal Dikonfirmasi");
        }
        return $response;
    }

    /** 
     * Insert data to pembiayaan
     * @return Response
    */
    public function insertPembiayaan($data)
    {
        $pembiayaan = new Pembiayaan();
        $pembiayaan->id = $data['id'];
        $pembiayaan->id_pembiayaan = $data['id_pembiayaan'];
        $pembiayaan->id_rekening = $data['id_rekening'];
        $pembiayaan->id_user = $data['id_user'];
        $pembiayaan->id_pengajuan = $data['id_pengajuan'];
        $pembiayaan->jenis_pembiayaan = $data['jenis_pembiayaan'];
        $pembiayaan->detail = json_encode($data['detail']);
        $pembiayaan->tempo = $data['tempo'];
        $pembiayaan->status = $data['status'];
        $pembiayaan->status_angsuran = $data['status_angsuran'];
        $pembiayaan->angsuran_ke = $data['angsuran_ke'];

        if($pembiayaan->save())
        {
            return "success";
        }
        else
        {
            return "error";
        }
    }

    /** 
     * Insert data to penyimpanan pembiayaan
     * @return Response
    */
    public function insertPenyimpananPembiayaan($data)
    {
        $penyimpananPembiayaan = new PenyimpananPembiayaan();
        $penyimpananPembiayaan->id_user = $data['id_user'];
        $penyimpananPembiayaan->id_pembiayaan = $data['id_pembiayaan'];
        $penyimpananPembiayaan->status = $data['status'];
        $penyimpananPembiayaan->transaksi = json_encode($data['transaksi']);
        $penyimpananPembiayaan->teller = $data['teller'];

        if($penyimpananPembiayaan->save())
        {
            return "success";
        }
        else
        {
            return "error";
        }
    }

    /** 
     * Open pembiayaan MRB anggota
     * @return Response
    */
    public function openPembiayaanMRB($data)
    {
        DB::beginTransaction();

        try
        {
            $user_pembiayaan = User::where('no_ktp', $data->nama_nasabah)->first();

            $id_pengajuan = DB::select("SHOW TABLE STATUS LIKE 'pengajuan'");
            $nextIdPengajuan = $id_pengajuan[0]->Auto_increment;

            $jenis_pembiayaan = "PEMBIAYAAN MRB";
            $id_rekening_pembiayaan = 100;

            $bmt_pembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->first();
            $bmt_pengirim = BMT::where('id_rekening', $data->bank)->first();
            $bmt_piutang_MRB = BMT::where('id_rekening', '101')->first();

            $created_date = Carbon::now();
            if($data->ketWaktu == "Bulan")
            {  
                $tempo = $data->waktu;
            }
            if($data->ketWaktu == "Hari")
            {
                $tempo = 1;
            }
            if($data->ketWaktu == "Tahun")
            {
                $tempo = $data->waktu * 12;
            }
            if($data->ketWaktu == "Pekan")
            {
                $tempo = 1;
            }

            $pinjaman = preg_replace('/[^\d.]/', '', $data->jumlah);
            $nisbah = $data->nisbah / 100;
            $margin = $pinjaman * $nisbah * $tempo;

            $saldo_awal_pengirim = floatval($bmt_pengirim->saldo);
            $saldo_akhir_pengirim = floatval($bmt_pengirim->saldo) - floatval($pinjaman);

            $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
            $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) + (floatval($pinjaman) + floatval($margin));

            $saldo_awal_piutang_mrb = floatval($bmt_piutang_MRB->saldo);
            $saldo_akhir_piutang_mrb = floatval($bmt_piutang_MRB->saldo) - floatval($margin);

            if($data->atasnama == 1)
            {
                $atasnama = "Pribadi";
                $nama_user = $user_pembiayaan->nama;
                $id_user = $user_pembiayaan->id;
            }
            if($data->atasnama == 2)
            {
                $atasnama = "Lembaga";
                $nama_user = $data->nama;
                $id_user = $data->id_user;
            }

            $file_name = $data->file->getClientOriginalName();
            $fileToUpload = time() . "-" . $file_name;
            $data->file('file')->storeAs(
                'public/jaminan/', $fileToUpload
            );

            $detailToPengajuan = [
                "atasnama"   => $atasnama,
                "nama"       => $nama_user,
                "id"         => $id_user,
                "pembiayaan" => $id_rekening_pembiayaan,
                "jumlah"     => $pinjaman,
                "jenis_Usaha"=> $data->jenisUsaha,
                "usaha"      => $data->usaha,
                "keterangan" => $data->waktu . " " . $data->ketWaktu,
                "jaminan"    => $data->jaminan,
                "nama_rekening" => $jenis_pembiayaan,
                "path_jaminan"  => "jaminan/" . $fileToUpload
            ];
            $dataToPengajuan = [
                "id"                => $nextIdPengajuan,
                "id_user"           => $user_pembiayaan->id,
                "id_rekening"       => $data->pembiayaan,
                "jenis_pengajuan"   => "Pengajuan Pembukaan " . $jenis_pembiayaan,
                "status"            => "Sudah Dikonfirmasi",
                "kategori"          => "Pembiayaan",
                "detail"            => $detailToPengajuan,
                "teller"            => Auth::user()->id
            ];

            $id_pembiayaan = DB::select("SHOW TABLE STATUS LIKE 'pembiayaan'");
            $nextIdPembiayaan = $id_pembiayaan[0]->Auto_increment;

            $detailToPembiayaan = [
                "pinjaman"          => $pinjaman,
                "margin"            => $margin,
                "nisbah"            => $nisbah,
                "total_pinjaman"    => floatval($pinjaman) + floatval($margin),
                "sisa_angsuran"     => $pinjaman,
                "sisa_margin"       => $margin,
                "sisa_pinjaman"     => floatval($pinjaman) + floatval($margin),
                "angsuran_pokok"    => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                "lama_angsuran"     => $tempo,
                "angsuran_ke"       => 0,
                "jumlah_angsuran_bulanan"  => round($pinjaman / $tempo), 
                "jumlah_margin_bulanan"    => round($margin / $tempo),
                "tagihan_bulanan"   => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                "sisa_ang_bln"      => 0,
                "sisa_mar_bln"      => 0,
                "kelebihan_angsuran_bulanan" => 0,
                "kelebihan_margin_bulanan" => 0,
                "id_pengajuan"      => $nextIdPengajuan
            ];
            $dataToPembiayaan = [
                "id"                => $nextIdPembiayaan,
                "id_pembiayaan"     => $user_pembiayaan->id . "." . $nextIdPembiayaan,
                "id_rekening"       => $id_rekening_pembiayaan,
                "id_user"           => $user_pembiayaan->id,
                "id_pengajuan"      => $nextIdPengajuan,
                "jenis_pembiayaan"  => $jenis_pembiayaan,
                "detail"            => $detailToPembiayaan,
                "tempo"             => $created_date->addMonth(1),
                "status"            => "active",
                "status_angsuran"   => 0,
                "angsuran_ke"       => 0
            ];

            $detailToPenyimpananPembiayaan = [
                "teller"            => Auth::user()->id,
                "dari_rekening"     => $data->bank,
                "untuk_rekening"    => "Tunai",
                "angsuran_pokok"    => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                "angsuran_ke"       => 0,
                "nisbah"            => $nisbah,
                "margin"            => $margin,
                "jumlah"            => $pinjaman,
                "tagihan"           => round((floatval($pinjaman) + floatval($margin)) / $tempo),
                "sisa_angsuran"     => $pinjaman,
                "sisa_margin"       => $margin,
                "sisa_pinjaman"     => floatval($pinjaman) + floatval($margin)
            ];
            $dataToPenyimpananPembiayaan = [
                "id_user"       => $user_pembiayaan->id,
                "id_pembiayaan" => $nextIdPembiayaan,
                "status"        => "Pencairan Pembiayaan",
                "transaksi"     => $detailToPenyimpananPembiayaan,
                "teller"        => Auth::user()->id
            ];

            $detailToPenyimpananBMT = [
                "jumlah"        => $pinjaman + $margin,
                "saldo_awal"    => $saldo_awal_pembiayaan,
                "saldo_akhir"   => $saldo_akhir_pembiayaan,
                "id_pengajuan"  => $nextIdPengajuan
            ];
            $dataToPenyimpananBMT = [
                "id_user"   => $user_pembiayaan->id,
                "id_bmt"    => $bmt_pembiayaan->id,
                "status"    => "Pencairan Pembiayaan",
                "transaksi" => $detailToPenyimpananBMT,
                "teller"    => Auth::user()->id
            ];

            $field = explode(".", $data->list)[1]; 
            $count = count(explode(",", $field));
            $key = array();
            $value = array();
            for($i = 0; $i < count(explode(",", $field)); $i++ )
            {
                array_push($key, explode(",", $field)[$i]);
                array_push($value, $data->field[$i]);
            }
            $dataToPenyimpananJaminan = array(
                "id_jaminan"    => explode(".", $data->list)[0],
                "id_user"       => $user_pembiayaan->id,
                "id_pengajuan"  => $nextIdPengajuan,
                "transaksi"     => array("field" => array_combine($key, $value), "jaminan" => null)
            );

            if($bmt_pengirim->saldo > $pinjaman)
            {
                if( $this->pengajuanReporsitory->createPengajuan($dataToPengajuan)["type"] == "success" &&
                    $this->insertPembiayaan($dataToPembiayaan) == "success" &&
                    $this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success" &&
                    $this->insertPenyimpananJaminan($dataToPenyimpananJaminan) == "success"
                )
                {
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                    $detailToPenyimpananBMT['jumlah'] = -floatval($pinjaman);
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $dataToPenyimpananBMT['id_bmt'] = $bmt_piutang_MRB->id;
                    $detailToPenyimpananBMT['jumlah'] = floatval($margin);
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_piutang_mrb;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_piutang_mrb;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                    
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $updateBMTPengirim = BMT::where('id_rekening', $data->bank)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                    $updateBMTPembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                    $updateBMTPiutangMRB = BMT::where('id_rekening', '101')->update([ "saldo" => $saldo_akhir_piutang_mrb ]);
                    
                    $this->exportPerjanjian($dataToPembiayaan, $data, $dataToPenyimpananJaminan);

                    DB::commit();
                    $response = array("type" => "success", "message" => "Pembukaan " . $jenis_pembiayaan . " Berhasil.");
                }
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pembukaan " . $jenis_pembiayaan . " Gagal. Saldo " . $bmt_pengirim->nama . " Tidak Cukup");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pembukaan " . $jenis_pembiayaan . " Gagal.");
        }
        return $response;
    }

    /** 
     * Open pembiayaan MDA anggota
     * @return Response
    */
    public function openPembiayaanLain($data)
    {
        DB::beginTransaction();

        try
        {
            $user_pembiayaan = User::where('no_ktp', $data->nama_nasabah)->first();

            $id_pengajuan = DB::select("SHOW TABLE STATUS LIKE 'pengajuan'");
            $nextIdPengajuan = $id_pengajuan[0]->Auto_increment;

            if($data->pembiayaan == 99)
            {
                $jenis_pembiayaan = "PEMBIAYAAN MDA";
                $id_rekening_pembiayaan = 99;
            }
            if($data->pembiayaan == 102)
            {
                $jenis_pembiayaan = "PEMBIAYAAN QORD";
                $id_rekening_pembiayaan = 102;
            }

            $bmt_pembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->first();
            $bmt_pengirim = BMT::where('id_rekening', $data->bank)->first();
            $bmt_piutang_MRB = BMT::where('id_rekening', '101')->first();

            $created_date = Carbon::now();
            if($data->ketWaktu == "Bulan")
            {  
                $tempo = $data->waktu;
            }
            if($data->ketWaktu == "Hari")
            {
                $tempo = 1;
            }
            if($data->ketWaktu == "Tahun")
            {
                $tempo = $data->waktu * 12;
            }
            if($data->ketWaktu == "Pekan")
            {
                $tempo = 1;
            }

            $pinjaman = preg_replace('/[^\d.]/', '', $data->jumlah);
            $nisbah = $data->nisbah / 100;
            $margin = $pinjaman * $nisbah * $tempo;

            $saldo_awal_pengirim = floatval($bmt_pengirim->saldo);
            $saldo_akhir_pengirim = floatval($bmt_pengirim->saldo) - floatval($pinjaman);

            $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
            $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) + floatval($pinjaman);

            if($data->atasnama == 1)
            {
                $atasnama = "Pribadi";
                $nama_user = $user_pembiayaan->nama;
                $id_user = $user_pembiayaan->id;
            }
            if($data->atasnama == 2)
            {
                $atasnama = "Lembaga";
                $nama_user = $data->nama;
                $id_user = $data->id_user;
            }

            $file_name = $data->file->getClientOriginalName();
            $fileToUpload = time() . "-" . $file_name;
            $data->file('file')->storeAs(
                'public/jaminan/', $fileToUpload
            );

            $detailToPengajuan = [
                "atasnama"   => $atasnama,
                "nama"       => $nama_user,
                "id"         => $id_user,
                "pembiayaan" => $id_rekening_pembiayaan,
                "jumlah"     => $pinjaman,
                "jenis_Usaha"=> $data->jenisUsaha,
                "usaha"      => $data->usaha,
                "keterangan" => $data->waktu . " " . $data->ketWaktu,
                "jaminan"    => $data->jaminan,
                "nama_rekening" => $jenis_pembiayaan,
                "path_jaminan"  => "jaminan/" . $fileToUpload
            ];
            $dataToPengajuan = [
                "id"                => $nextIdPengajuan,
                "id_user"           => $user_pembiayaan->id,
                "id_rekening"       => $data->pembiayaan,
                "jenis_pengajuan"   => "Pengajuan Pembukaan " . $jenis_pembiayaan,
                "status"            => "Sudah Dikonfirmasi",
                "kategori"          => "Pembiayaan",
                "detail"            => $detailToPengajuan,
                "teller"            => Auth::user()->id
            ];

            $id_pembiayaan = DB::select("SHOW TABLE STATUS LIKE 'pembiayaan'");
            $nextIdPembiayaan = $id_pembiayaan[0]->Auto_increment;

            $detailToPembiayaan = [
                "pinjaman"          => $pinjaman,
                "margin"            => $margin,
                "nisbah"            => $nisbah,
                "total_pinjaman"    => floatval($pinjaman),
                "sisa_angsuran"     => $pinjaman,
                "sisa_margin"       => $margin,
                "sisa_pinjaman"     => floatval($pinjaman),
                "angsuran_pokok"    => round(floatval($pinjaman) / $tempo),
                "lama_angsuran"     => $tempo,
                "angsuran_ke"       => 0,
                "jumlah_angsuran_bulanan"  => round($pinjaman / $tempo), 
                "jumlah_margin_bulanan"    => round($margin / $tempo),
                "tagihan_bulanan"   => round(floatval($pinjaman) / $tempo),
                "sisa_ang_bln"      => 0,
                "sisa_mar_bln"      => 0,
                "kelebihan_angsuran_bulanan" => 0,
                "kelebihan_margin_bulanan" => 0,
                "id_pengajuan"      => $nextIdPengajuan
            ];
            $dataToPembiayaan = [
                "id"                => $nextIdPembiayaan,
                "id_pembiayaan"     => $user_pembiayaan->id . "." . $nextIdPembiayaan,
                "id_rekening"       => $id_rekening_pembiayaan,
                "id_user"           => $user_pembiayaan->id,
                "id_pengajuan"      => $nextIdPengajuan,
                "jenis_pembiayaan"  => $jenis_pembiayaan,
                "detail"            => $detailToPembiayaan,
                "tempo"             => $created_date->addMonth(1),
                "status"            => "active",
                "status_angsuran"   => 0,
                "angsuran_ke"       => 0
            ];

            $detailToPenyimpananPembiayaan = [
                "teller"            => Auth::user()->id,
                "dari_rekening"     => $data->bank,
                "untuk_rekening"    => "Tunai",
                "angsuran_pokok"    => round(floatval($pinjaman) / $tempo),
                "angsuran_ke"       => 0,
                "nisbah"            => $nisbah,
                "margin"            => $margin,
                "jumlah"            => $pinjaman,
                "tagihan"           => round(floatval($pinjaman) / $tempo),
                "sisa_angsuran"     => $pinjaman,
                "sisa_margin"       => $margin,
                "sisa_pinjaman"     => floatval($pinjaman)
            ];
            $dataToPenyimpananPembiayaan = [
                "id_user"       => $user_pembiayaan->id,
                "id_pembiayaan" => $nextIdPembiayaan,
                "status"        => "Pencairan Pembiayaan",
                "transaksi"     => $detailToPenyimpananPembiayaan,
                "teller"        => Auth::user()->id
            ];

            $detailToPenyimpananBMT = [
                "jumlah"        => $pinjaman,
                "saldo_awal"    => $saldo_awal_pembiayaan,
                "saldo_akhir"   => $saldo_akhir_pembiayaan,
                "id_pengajuan"  => $nextIdPengajuan
            ];
            $dataToPenyimpananBMT = [
                "id_user"   => $user_pembiayaan->id,
                "id_bmt"    => $bmt_pembiayaan->id,
                "status"    => "Pencairan Pembiayaan",
                "transaksi" => $detailToPenyimpananBMT,
                "teller"    => Auth::user()->id
            ];

            $field = explode(".", $data->list)[1]; 
            $count = count(explode(",", $field));
            $key = array();
            $value = array();
            for($i = 0; $i < count(explode(",", $field)); $i++ )
            {
                array_push($key, explode(",", $field)[$i]);
                array_push($value, $data->field[$i]);
            }
            $dataToPenyimpananJaminan = array(
                "id_jaminan"    => explode(".", $data->list)[0],
                "id_user"       => $user_pembiayaan->id,
                "id_pengajuan"  => $nextIdPengajuan,
                "transaksi"     => array("field" => array_combine($key, $value), "jaminan" => null)
            );

            if($bmt_pengirim->saldo > $pinjaman)
            {
                if( $this->pengajuanReporsitory->createPengajuan($dataToPengajuan)["type"] == "success" &&
                    $this->insertPembiayaan($dataToPembiayaan) == "success" &&
                    $this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success" &&
                    $this->insertPenyimpananJaminan($dataToPenyimpananJaminan) == "success"
                )
                {
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                    $detailToPenyimpananBMT['jumlah'] = -floatval($pinjaman);
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $updateBMTPengirim = BMT::where('id_rekening', $data->bank)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                    $updateBMTPembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);

                    $this->exportPerjanjian($dataToPembiayaan, $data, $dataToPenyimpananJaminan);

                    DB::commit();
                    $response = array("type" => "success", "message" => "Pembukaan " . $jenis_pembiayaan . " Berhasil.");
                }
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pembukaan " . $jenis_pembiayaan . " Gagal. Saldo " . $bmt_pengirim->nama . " Tidak Cukup");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pembukaan " . $jenis_pembiayaan . " Gagal.");
        }
        
        return $response;
    }

    /** 
     * Konfirmasi pengajuan angsuran MRB anggota
     * @return Response
    */
    public function confirmAngsuranMRB($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_);
            $pembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->first();
            $penyimpananPembiayaan = PenyimpananPembiayaan::where('id_pembiayaan', $pembiayaan->id)->orderBy('created_at', 'desc')->take(1)->get();
            $user_pembiayaan = User::where('id', $pembiayaan->id_user)->first();

            if($pembiayaan->status == "active") {
                $jenis_pembiayaan = "PEMBIAYAAN MRB";
                $id_rekening_pembiayaan = 100;
                
                if(json_decode($pengajuan->detail)->angsuran == "Tunai")
                {
                    $bank_tujuan_angsuran = json_decode(Auth::user()->detail)->id_rekening;
                    $dari_bank = "Tunai";
                }
                if(json_decode($pengajuan->detail)->angsuran == "Transfer")
                {
                    $bank_tujuan_angsuran = json_decode($pengajuan->detail)->bank;
                    $dari_bank = "[" . json_decode($pengajuan->detail)->no_bank . "] " . json_decode($pengajuan->detail)->bank_user;
                }
                if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                {
                    $tabungan =  Tabungan::where('id', json_decode($pengajuan->detail)->bank)->first();
                    $rekening_tabungan =  Rekening::where('id', $tabungan->id_rekening)->first();
                    $bmt_tabungan = BMT::where('id_rekening', $tabungan->id_rekening)->first();
                    $bank_tujuan_angsuran = $tabungan->id_rekening;
                    $dari_bank = "[" . json_decode($pengajuan->detail)->bank . "] " . json_decode($pengajuan->detail)->atasnama;
                }

                $tagihan_pokok_angsuran = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
                $jumlah_bayar_angsuran = json_decode($pengajuan->detail)->bayar_ang;
                $tagihan_pokok_margin = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;
                $jumlah_bayar_margin = json_decode($pengajuan->detail)->bayar_mar;
                $kekurangan_pembayaran_angsuran = 0;
                $kekurangan_pembayaran_margin = 0;

                if(json_decode($pembiayaan->detail)->sisa_margin > 0)
                {
                    if($jumlah_bayar_margin < $tagihan_pokok_margin)
                    {
                        $kekurangan_pembayaran_margin = $tagihan_pokok_margin - $jumlah_bayar_margin;
                        $jumlah_bayar_angsuran = $jumlah_bayar_angsuran - $kekurangan_pembayaran_margin;
                        $jumlah_bayar_margin = $jumlah_bayar_margin + $kekurangan_pembayaran_margin;
                    }
                }

                if(json_decode($pembiayaan->detail)->sisa_angsuran > $tagihan_pokok_angsuran)
                {
                    if($jumlah_bayar_angsuran < $tagihan_pokok_angsuran)
                    {
                        $kekurangan_pembayaran_angsuran = $tagihan_pokok_angsuran - $jumlah_bayar_angsuran;
                    }
                }
                else
                {
                    $kekurangan_pembayaran_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran - $jumlah_bayar_angsuran;
                }

                if($jumlah_bayar_angsuran > $tagihan_pokok_angsuran)
                {
                    $kelebihan_angsuran_bulanan = $jumlah_bayar_angsuran - $tagihan_pokok_angsuran;
                }
                else
                {
                    $kelebihan_angsuran_bulanan = 0;
                }
                
                if($jumlah_bayar_margin > $tagihan_pokok_margin)
                {
                    $kelebihan_margin_bulanan = $jumlah_bayar_margin - $tagihan_pokok_margin;
                }
                else
                {
                    $kelebihan_margin_bulanan = 0;
                }
                
                $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran;
                $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin;
                $total_pinjaman = json_decode($pembiayaan->detail)->pinjaman;
                $total_margin = json_decode($pembiayaan->detail)->margin;
                
                $tagihan = $tagihan_pokok_angsuran + $tagihan_pokok_margin;

                $sisa_margin_bulanan = $kekurangan_pembayaran_margin;
                $sisa_angsuran_bulanan = $kekurangan_pembayaran_angsuran;

                $sisa_pinjaman = (json_decode($pembiayaan->detail)->sisa_pinjaman) - ($jumlah_bayar_angsuran + $jumlah_bayar_margin);
                
                $detailToUpdatePembiayaan = [
                    "pinjaman"          => json_decode($pembiayaan->detail)->pinjaman,
                    "margin"            => json_decode($pembiayaan->detail)->margin,
                    "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                    "total_pinjaman"    => json_decode($pembiayaan->detail)->total_pinjaman,
                    "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                    "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                    "sisa_pinjaman"     => $sisa_pinjaman,
                    "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                    "lama_angsuran"     => json_decode($pembiayaan->detail)->lama_angsuran,
                    "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                    "tagihan_bulanan"   => json_decode($pembiayaan->detail)->tagihan_bulanan,
                    "jumlah_angsuran_bulanan"  => round($total_pinjaman / json_decode($pembiayaan->detail)->lama_angsuran), 
                    "jumlah_margin_bulanan"    => round($total_margin / json_decode($pembiayaan->detail)->lama_angsuran),
                    "sisa_ang_bln"      => $sisa_angsuran_bulanan,
                    "sisa_mar_bln"      => 0,
                    "kelebihan_angsuran_bulanan" => $kelebihan_angsuran_bulanan,
                    "kelebihan_margin_bulanan" =>  $kelebihan_margin_bulanan,
                    "id_pengajuan"      => $pengajuan->id
                ];

                $bmt_tujuan_angsuran = BMT::where('id_rekening', $bank_tujuan_angsuran)->first();
                $bmt_piutang_mrb = BMT::where('id_rekening', 101)->first();
                $bmt_pembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->first();
                $bmt_shu_berjalan = BMT::where('id_rekening', 122)->first();
                $bmt_pendapatan_mrb = BMT::where('id_rekening', 130)->first();

                $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
                $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) + (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));
                
                if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                {
                    $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
                    $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) - (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));

                    $detailToPenyimpananTabungan = [
                        "teller"            => Auth::user()->id,
                        "dari_rekening"     => $tabungan->jenis_tabungan,
                        "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                        "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                        "saldo_awal"        => $saldo_awal_pengirim,
                        "saldo_akhir"       => $saldo_akhir_pengirim
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => $tabungan->id_user,
                        "id_tabungan"   => $tabungan->id,
                        "status"        => "Angsuran Pembiayaan MRB",
                        "transaksi"     => $detailToPenyimpananTabungan,
                        "teller"        => Auth::user()->id
                    ];

                    $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                }

                $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
                $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) - (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));

                $detailToPenyimpananPembiayaan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $dari_bank,
                    "untuk_rekening"    => $bank_tujuan_angsuran,
                    "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                    "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                    "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                    "margin"            => json_decode($pembiayaan->detail)->margin,
                    "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
                    "tagihan"           => $tagihan,
                    "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                    "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                    "sisa_pinjaman"     => $sisa_pinjaman,
                    "bayar_angsuran"    => $jumlah_bayar_angsuran,
                    "bayar_margin"      => $jumlah_bayar_margin,
                    "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
                ];
                $dataToPenyimpananPembiayaan = [
                    "id_user"       => $pengajuan->id_user,
                    "id_pembiayaan" => $pembiayaan->id,
                    "status"        => "Angsuran Pembiayaan [Pokok]",
                    "transaksi"     => $detailToPenyimpananPembiayaan,
                    "teller"        => Auth::user()->id
                ];

                $detailToPenyimpananBMT = [
                    "jumlah"        => -$jumlah_bayar_angsuran - $jumlah_bayar_margin,
                    "saldo_awal"    => $saldo_awal_pembiayaan,
                    "saldo_akhir"   => $saldo_akhir_pembiayaan,
                    "id_pengajuan"  => $pengajuan->id
                ];
                $dataToPenyimpananBMT = [
                    "id_user"   => $pengajuan->id_user,
                    "id_bmt"    => $bmt_pembiayaan->id,
                    "status"    => "Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];

                if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                {
                    if(json_decode($tabungan->detail)->saldo > ($jumlah_bayar_angsuran + $jumlah_bayar_margin))
                    {
                        if($this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                        )
                        {
                            $dataToPenyimpananBMT['id_bmt'] = $bmt_tujuan_angsuran->id;
                            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_angsuran + $jumlah_bayar_margin;
                            if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                            {
                                $detailToPenyimpananBMT['jumlah'] = -$jumlah_bayar_angsuran - $jumlah_bayar_margin;
                            }
                            $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                            $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                            
                            $dataToPenyimpananBMT['id_bmt'] = $bmt_pendapatan_mrb->id;
                            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                            $detailToPenyimpananBMT['saldo_awal'] = $bmt_pendapatan_mrb->saldo;
                            $detailToPenyimpananBMT['saldo_akhir'] = $bmt_pendapatan_mrb->saldo + $jumlah_bayar_margin;
                            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                            
                            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                            $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
                            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                            $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
                            $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
                            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                            $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                            $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                            $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                            $updatePendapatanMRB = BMT::where('id_rekening', 130)->update([ "saldo" => $bmt_pendapatan_mrb->saldo + $jumlah_bayar_margin ]);
                            $updatePiutangMRB = BMT::where('id_rekening', 101)->update([ "saldo" => $bmt_piutang_mrb->saldo + $jumlah_bayar_margin ]);

                            if($kekurangan_pembayaran_angsuran <= 0)
                            {
                                $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1)->format('yy-m-d');
                            }
                            else
                            {
                                $tempo_pembayaran = $pembiayaan->tempo;
                            }

                            if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                            {
                                $dataToUpdateTabungan = [
                                    "saldo" => json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin),
                                    "id_pengajuan" => $pengajuan->id
                                ];

                                $tabungan->detail = json_encode($dataToUpdateTabungan);
                                $tabungan->save();
                            }
                            
                            $updatePembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
                                'tempo'     => $tempo_pembayaran,
                                'detail'    => json_encode($detailToUpdatePembiayaan),
                                'angsuran_ke' => $pembiayaan->angsuran_ke + 1
                            ]);
                            $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([ 'status' => 'Sudah Dikonfirmasi', 'teller' => Auth::user()->id ]);
                            
                            // Update margin yang sudah dibayarkan user
                            if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                            {
                                $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                            }
                            else
                            {
                                $total_margin_anggota = floatval($jumlah_bayar_margin);
                            }
                            $user_pembiayaan->wajib_pokok = json_encode([
                                "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                                "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                                "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                                "margin" => $total_margin_anggota
                            ]);
                            $user_pembiayaan->save();

                            if($sisa_pinjaman <= 10)
                            {
                                $detailToUpdatePembiayaan['sisa_angsuran'] = 0;
                                $detailToUpdatePembiayaan['sisa_margin'] = 0;
                                $detailToUpdatePembiayaan['sisa_pinjaman'] = 0;
                                
                                $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
                                    'detail'    => json_encode($detailToUpdatePembiayaan),
                                    'status'    => 'lunas'
                                ]);
                            }

                            DB::commit();
                            $response = array("type" => "success", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                        }
                        else
                        {
                            DB::rollback();
                            $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
                        }
                    }
                    elseif(json_decode($tabungan->detail)->saldo < json_decode($rekening_tabungan->detail)->saldo_min)
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Rekening tabungan " . $tabungan->jenis_tabungan . " melampaui limit transaksi.");
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Saldo " . $tabungan->jenis_tabungan . " Tidak Cukup.");
                    }
                }
                else
                {
                    if($this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                    )
                    {
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_tujuan_angsuran->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_angsuran + $jumlah_bayar_margin;
                        if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                        {
                            $detailToPenyimpananBMT['jumlah'] = -$jumlah_bayar_angsuran - $jumlah_bayar_margin;
                        }
                        $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                        
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_pendapatan_mrb->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_pendapatan_mrb->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_pendapatan_mrb->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        $response = $bmt_tujuan_angsuran;
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                        $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                        $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                        $updatePendapatanMRB = BMT::where('id_rekening', 130)->update([ "saldo" => $bmt_pendapatan_mrb->saldo + $jumlah_bayar_margin ]);
                        $updatePiutangMRB = BMT::where('id_rekening', 101)->update([ "saldo" => $bmt_piutang_mrb->saldo + $jumlah_bayar_margin ]);

                        if($kekurangan_pembayaran_angsuran <= 0)
                        {
                            $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1)->format('yy-m-d');
                        }
                        else
                        {
                            $tempo_pembayaran = $pembiayaan->tempo;
                        }

                        if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                        {
                            $dataToUpdateTabungan = [
                                "saldo" => json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin),
                                "id_pengajuan" => $pengajuan->id
                            ];

                            $tabungan->detail = json_encode($dataToUpdateTabungan);
                            $tabungan->save();
                        }
                        
                        $updatePembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
                            'tempo'     => $tempo_pembayaran,
                            'detail'    => json_encode($detailToUpdatePembiayaan),
                            'angsuran_ke' => $pembiayaan->angsuran_ke + 1
                        ]);
                        $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([ 'status' => 'Sudah Dikonfirmasi', 'teller' => Auth::user()->id ]);

                        // Update margin yang sudah dibayarkan user
                        if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                        {
                            $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                        }
                        else
                        {
                            $total_margin_anggota = floatval($jumlah_bayar_margin);
                        }
                        $user_pembiayaan->wajib_pokok = json_encode([
                            "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                            "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                            "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                            "margin" => $total_margin_anggota
                        ]);
                        $user_pembiayaan->save();

                        if($sisa_pinjaman <= 10)
                        {
                            $detailToUpdatePembiayaan['sisa_angsuran'] = 0;
                            $detailToUpdatePembiayaan['sisa_margin'] = 0;
                            $detailToUpdatePembiayaan['sisa_pinjaman'] = 0;

                            $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
                                'detail'    => json_encode($detailToUpdatePembiayaan),
                                'status'    => 'lunas'
                            ]);
                        }

                        DB::commit();
                        $response = array("type" => "success", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
                    }
                }
            }
            else {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. " . $pembiayaan->jenis_pembiayaan . " Sudah Lunas.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
        }
        return $response;
    }

    /** 
     * Konfirmasi pengajuan angsuran MDA anggota
     * @return Response
    */
    public function confirmAngsuranLain($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_);
            $pembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->first();
            $penyimpananPembiayaan = PenyimpananPembiayaan::where('id_pembiayaan', $pembiayaan->id)->orderBy('created_at', 'desc')->take(1)->get();
            $user_pembiayaan = User::where('id', $pembiayaan->id_user)->first();

            if($pembiayaan->status == "active")
            {
                if($pengajuan->id_rekening == 99)
                {
                    $jenis_pembiayaan = "PEMBIAYAAN MDA";
                    $id_rekening_pembiayaan = 99;
                    $id_rekening_pendapatan = 129;
                }
                if($pengajuan->id_rekening == 102)
                {
                    $jenis_pembiayaan = "PEMBIAYAAN QORD";
                    $id_rekening_pembiayaan = 102;
                    $id_rekening_pendapatan = 131;
                }
                
                if(json_decode($pengajuan->detail)->angsuran == "Tunai")
                {
                    $bank_tujuan_angsuran = json_decode(Auth::user()->detail)->id_rekening;
                    $dari_bank = "Tunai";
                }
                if(json_decode($pengajuan->detail)->angsuran == "Transfer")
                {
                    $bank_tujuan_angsuran = json_decode($pengajuan->detail)->bank;
                    $dari_bank = "[" . json_decode($pengajuan->detail)->no_bank . "] " . json_decode($pengajuan->detail)->bank_user;
                }
                if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                {
                    $tabungan =  Tabungan::where('id', json_decode($pengajuan->detail)->bank)->first();
                    $rekening_tabungan = Rekening::where('id', $tabungan->id_rekening)->first();
                    $bmt_tabungan = BMT::where('id_rekening', $tabungan->id_rekening)->first();
                    $bank_tujuan_angsuran = $tabungan->id_rekening;
                    $dari_bank = "[" . json_decode($pengajuan->detail)->bank . "] " . json_decode($pengajuan->detail)->atasnama;
                }

                $tagihan_pokok_angsuran = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
                $jumlah_bayar_angsuran = json_decode($pengajuan->detail)->bayar_ang;
                $tagihan_pokok_margin = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;
                $jumlah_bayar_margin = json_decode($pengajuan->detail)->bayar_mar;
                $kekurangan_pembayaran_angsuran = 0;
                $kekurangan_pembayaran_margin = 0;

                if(json_decode($pembiayaan->detail)->sisa_angsuran > $tagihan_pokok_angsuran)
                {
                    if($jumlah_bayar_angsuran < $tagihan_pokok_angsuran)
                    {
                        $kekurangan_pembayaran_angsuran = $tagihan_pokok_angsuran - $jumlah_bayar_angsuran;
                    }
                }
                else
                {
                    $kekurangan_pembayaran_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran - $jumlah_bayar_angsuran;
                }

                if($jumlah_bayar_angsuran > $tagihan_pokok_angsuran)
                {
                    $kelebihan_angsuran_bulanan = $jumlah_bayar_angsuran - $tagihan_pokok_angsuran;
                }
                else
                {
                    $kelebihan_angsuran_bulanan = 0;
                }
                
                if($jumlah_bayar_margin > $tagihan_pokok_margin)
                {
                    $kelebihan_margin_bulanan = $jumlah_bayar_margin - $tagihan_pokok_margin;
                }
                else
                {
                    $kelebihan_margin_bulanan = 0;
                }
                
                $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran;
                $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin;
                $total_pinjaman = json_decode($pembiayaan->detail)->pinjaman;
                $total_margin = json_decode($pembiayaan->detail)->margin;
                
                $tagihan = $tagihan_pokok_angsuran;

                $sisa_margin_bulanan = $kekurangan_pembayaran_margin;
                $sisa_angsuran_bulanan = $kekurangan_pembayaran_angsuran;

                
                $sisa_pinjaman = (json_decode($pembiayaan->detail)->sisa_pinjaman) - $jumlah_bayar_angsuran;
                
                $detailToUpdatePembiayaan = [
                    "pinjaman"          => json_decode($pembiayaan->detail)->pinjaman,
                    "margin"            => json_decode($pembiayaan->detail)->margin,
                    "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                    "total_pinjaman"    => json_decode($pembiayaan->detail)->total_pinjaman,
                    "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                    "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                    "sisa_pinjaman"     => $sisa_pinjaman,
                    "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                    "lama_angsuran"     => json_decode($pembiayaan->detail)->lama_angsuran,
                    "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                    "tagihan_bulanan"   => json_decode($pembiayaan->detail)->tagihan_bulanan,
                    "jumlah_angsuran_bulanan"  => round($total_pinjaman / json_decode($pembiayaan->detail)->lama_angsuran), 
                    "jumlah_margin_bulanan"    => round($total_margin / json_decode($pembiayaan->detail)->lama_angsuran),
                    "sisa_ang_bln"      => $sisa_angsuran_bulanan,
                    "sisa_mar_bln"      => 0,
                    "kelebihan_angsuran_bulanan" => $kelebihan_angsuran_bulanan,
                    "kelebihan_margin_bulanan" =>  $kelebihan_margin_bulanan,
                    "id_pengajuan"      => $pengajuan->id
                ];

                $bmt_tujuan_angsuran = BMT::where('id_rekening', $bank_tujuan_angsuran)->first();
                $bmt_pembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->first();
                $bmt_shu_berjalan = BMT::where('id_rekening', 122)->first();
                $bmt_rekening_pendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->first();

                $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
                $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) + floatval($jumlah_bayar_angsuran + $jumlah_bayar_margin);
                
                if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                {
                    $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
                    $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) - floatval($jumlah_bayar_angsuran + $jumlah_bayar_margin);

                    $detailToPenyimpananTabungan = [
                        "teller"            => Auth::user()->id,
                        "dari_rekening"     => $tabungan->jenis_tabungan,
                        "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                        "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                        "saldo_awal"        => $saldo_awal_pengirim,
                        "saldo_akhir"       => $saldo_akhir_pengirim
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => $tabungan->id_user,
                        "id_tabungan"   => $tabungan->id,
                        "status"        => "Angsuran Pembiayaan MRB",
                        "transaksi"     => $detailToPenyimpananTabungan,
                        "teller"        => Auth::user()->id
                    ];

                    $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                }

                $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
                $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) - floatval($jumlah_bayar_angsuran);

                if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                {
                    if(json_decode($tabungan->detail)->saldo > ($jumlah_bayar_angsuran + $jumlah_bayar_margin))
                    {
                        $detailToPenyimpananPembiayaan = [
                            "teller"            => Auth::user()->id,
                            "dari_rekening"     => $dari_bank,
                            "untuk_rekening"    => $bank_tujuan_angsuran,
                            "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                            "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                            "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                            "margin"            => json_decode($pembiayaan->detail)->margin,
                            "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
                            "tagihan"           => $tagihan,
                            "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                            "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                            "sisa_pinjaman"     => $sisa_pinjaman,
                            "bayar_angsuran"    => $jumlah_bayar_angsuran,
                            "bayar_margin"      => $jumlah_bayar_margin,
                            "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
                        ];
                        $dataToPenyimpananPembiayaan = [
                            "id_user"       => $pengajuan->id_user,
                            "id_pembiayaan" => $pembiayaan->id,
                            "status"        => "Angsuran Pembiayaan [Pokok]",
                            "transaksi"     => $detailToPenyimpananPembiayaan,
                            "teller"        => Auth::user()->id
                        ];

                        $detailToPenyimpananBMT = [
                            "jumlah"        => -$jumlah_bayar_angsuran,
                            "saldo_awal"    => $saldo_awal_pembiayaan,
                            "saldo_akhir"   => $saldo_akhir_pembiayaan,
                            "id_pengajuan"  => $pengajuan->id
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"   => $pengajuan->id_user,
                            "id_bmt"    => $bmt_pembiayaan->id,
                            "status"    => "Angsuran",
                            "transaksi" => $detailToPenyimpananBMT,
                            "teller"    => Auth::user()->id
                        ];

                        if($this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                        )
                        {
                            $dataToPenyimpananBMT['id_bmt'] = $bmt_tujuan_angsuran->id;
                            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_angsuran + $jumlah_bayar_margin;
                            if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                            {
                                $detailToPenyimpananBMT['jumlah'] = -$jumlah_bayar_angsuran - $jumlah_bayar_margin;
                            }
                            $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                            $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                            
                            $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
                            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                            $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
                            $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
                            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                            
                            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                            $dataToPenyimpananBMT['id_bmt'] = $bmt_rekening_pendapatan->id;
                            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                            $detailToPenyimpananBMT['saldo_awal'] = $bmt_rekening_pendapatan->saldo;
                            $detailToPenyimpananBMT['saldo_akhir'] = $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin;
                            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                            $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                            $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                            $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                            $updateBMTPendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->update([ "saldo" => $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin ]);

                            if($kekurangan_pembayaran_angsuran <= 0)
                            {
                                $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1)->format('yy-m-d');
                            }
                            else
                            {
                                $tempo_pembayaran = $pembiayaan->tempo;
                            }

                            if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                            {
                                $dataToUpdateTabungan = [
                                    "saldo" => floatval(json_decode($tabungan->detail)->saldo) - floatval($jumlah_bayar_angsuran + $jumlah_bayar_margin),
                                    "id_pengajuan" => $pengajuan->id
                                ];

                                $tabungan->detail = json_encode($dataToUpdateTabungan);
                                $tabungan->save();
                            }
                            
                            $updatePembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
                                'tempo'     => $tempo_pembayaran,
                                'detail'    => json_encode($detailToUpdatePembiayaan),
                                'angsuran_ke' => $pembiayaan->angsuran_ke + 1
                            ]);
                            $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([ 'status' => 'Sudah Dikonfirmasi', 'teller' => Auth::user()->id ]);
                            
                            // Update margin yang sudah dibayarkan user
                            if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                            {
                                $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                            }
                            else
                            {
                                $total_margin_anggota = floatval($jumlah_bayar_margin);
                            }
                            $user_pembiayaan->wajib_pokok = json_encode([
                                "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                                "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                                "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                                "margin" => $total_margin_anggota
                            ]);
                            $user_pembiayaan->save();
                            
                            if($sisa_pinjaman <= 10)
                            {
                                $detailToUpdatePembiayaan['sisa_pinjaman'] = 0;
                                $detailToUpdatePembiayaan['sisa_angsuran'] = 0;
                                $detailToUpdatePembiayaan['sisa_margin'] = 0;

                                $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
                                    'detail'    => json_encode($detailToUpdatePembiayaan),
                                    'status'    => 'lunas'
                                ]);
                            }

                            DB::commit();
                            $response = array("type" => "success", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                        }
                        else
                        {
                            DB::rollback();
                            $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
                        }
                    }
                    elseif(json_decode($tabungan->detail)->saldo < json_decode($rekening_tabungan->detail)->saldo_min)
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Tabungan " . $tabungan->jenis_tabungan . " Melampaui Batas Transaksi.");
                    }
                    else {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Saldo " . $tabungan->jenis_tabungan . " Tidak Cukup.");
                    }
                }
                else
                {
                    $detailToPenyimpananPembiayaan = [
                        "teller"            => Auth::user()->id,
                        "dari_rekening"     => $dari_bank,
                        "untuk_rekening"    => $bank_tujuan_angsuran,
                        "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                        "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                        "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                        "margin"            => json_decode($pembiayaan->detail)->margin,
                        "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
                        "tagihan"           => $tagihan,
                        "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                        "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                        "sisa_pinjaman"     => $sisa_pinjaman,
                        "bayar_angsuran"    => $jumlah_bayar_angsuran,
                        "bayar_margin"      => $jumlah_bayar_margin,
                        "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
                    ];
                    $dataToPenyimpananPembiayaan = [
                        "id_user"       => $pengajuan->id_user,
                        "id_pembiayaan" => $pembiayaan->id,
                        "status"        => "Angsuran Pembiayaan [Pokok]",
                        "transaksi"     => $detailToPenyimpananPembiayaan,
                        "teller"        => Auth::user()->id
                    ];

                    $detailToPenyimpananBMT = [
                        "jumlah"        => -$jumlah_bayar_angsuran,
                        "saldo_awal"    => $saldo_awal_pembiayaan,
                        "saldo_akhir"   => $saldo_akhir_pembiayaan,
                        "id_pengajuan"  => $pengajuan->id
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user"   => $pengajuan->id_user,
                        "id_bmt"    => $bmt_pembiayaan->id,
                        "status"    => "Angsuran",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller"    => Auth::user()->id
                    ];

                    if($this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                    )
                    {
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_tujuan_angsuran->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_angsuran + $jumlah_bayar_margin;
                        if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                        {
                            $detailToPenyimpananBMT['jumlah'] = -$jumlah_bayar_angsuran - $jumlah_bayar_margin;
                        }
                        $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                        
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $dataToPenyimpananBMT['id_bmt'] = $bmt_rekening_pendapatan->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_rekening_pendapatan->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                        $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                        $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                        $updateBMTPendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->update([ "saldo" => $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin ]);

                        if($kekurangan_pembayaran_angsuran <= 0)
                        {
                            $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1)->format('yy-m-d');
                        }
                        else
                        {
                            $tempo_pembayaran = $pembiayaan->tempo;
                        }

                        if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
                        {
                            $dataToUpdateTabungan = [
                                "saldo" => floatval(json_decode($tabungan->detail)->saldo) - floatval($jumlah_bayar_angsuran + $jumlah_bayar_margin),
                                "id_pengajuan" => $pengajuan->id
                            ];

                            $tabungan->detail = json_encode($dataToUpdateTabungan);
                            $tabungan->save();
                        }
                        
                        $updatePembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
                            'tempo'     => $tempo_pembayaran,
                            'detail'    => json_encode($detailToUpdatePembiayaan),
                            'angsuran_ke' => $pembiayaan->angsuran_ke + 1
                        ]);
                        $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([ 'status' => 'Sudah Dikonfirmasi', 'teller' => Auth::user()->id ]);
                        
                        // Update margin yang sudah dibayarkan user
                        if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                        {
                            $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                        }
                        else
                        {
                            $total_margin_anggota = floatval($jumlah_bayar_margin);
                        }
                        $user_pembiayaan->wajib_pokok = json_encode([
                            "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                            "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                            "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                            "margin" => $total_margin_anggota
                        ]);
                        $user_pembiayaan->save();

                        if($sisa_pinjaman <= 10)
                        {
                            $detailToUpdatePembiayaan['sisa_pinjaman'] = 0;
                            $detailToUpdatePembiayaan['sisa_angsuran'] = 0;
                            $detailToUpdatePembiayaan['sisa_margin'] = 0;

                            $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
                                'detail'    => json_encode($detailToUpdatePembiayaan),
                                'status'    => 'lunas'
                            ]);
                        }

                        DB::commit();
                        $response = array("type" => "success", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
                    }
                }
            }
            else {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. " . $pembiayaan->jenis_pembiayaan . " Telah Lunas.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
        }
        return $response;
    }

    /** 
     * Angsuran pembiayaan MDA via teller
     * @return Response
    */
    public function angsuranLain($data)
    {
        DB::beginTransaction();
        try
        {
            $pembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->first();
            $penyimpananPembiayaan = PenyimpananPembiayaan::where('id_pembiayaan', $pembiayaan->id)->orderBy('created_at', 'desc')->take(1)->get();
            $user_pembiayaan = User::where('id', $pembiayaan->id_user)->first();

            if($pembiayaan->id_rekening == 99)
            {
                $jenis_pembiayaan = "PEMBIAYAAN MDA";
                $id_rekening_pembiayaan = 99;
                $id_rekening_pendapatan = 129;
            }
            if($pembiayaan->id_rekening == 102)
            {
                $jenis_pembiayaan = "PEMBIAYAAN QORD";
                $id_rekening_pembiayaan = 102;
                $id_rekening_pendapatan = 131;
            }
            
            if($data->debit == 0)
            {
                $bank_tujuan_angsuran = json_decode(Auth::user()->detail)->id_rekening;
                $dari_bank = "Tunai";
            }
            if($data->debit == 1)
            {
                $bank_tujuan_angsuran = $data->bank;
                $dari_bank = "[" . $data->nobank . "] " . $data->daribank;
            }
            if($data->debit == 2)
            {
                $tabungan =  Tabungan::where('id', $data->tabungan)->first();
                $rekening_tabungan = Rekening::where('id', $tabungan->id_rekening)->first();
                $bmt_tabungan = BMT::where('id_rekening', $tabungan->id_rekening)->first();
                $bank_tujuan_angsuran = $tabungan->id_rekening;
                $dari_bank = "[" . $tabungan->id_tabungan . "] " . $tabungan->jenis_tabungan;
            }

            $tagihan_pokok_angsuran = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
            $jumlah_bayar_angsuran = preg_replace('/[^\d.]/', '', $data->bayar_ang);
            $tagihan_pokok_margin = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;
            if($data->bayar_mar !== null)
            {
                $jumlah_bayar_margin = preg_replace('/[^\d.]/', '', $data->bayar_mar);
            }
            else
            {
                $jumlah_bayar_margin = 0;
            }

            $kekurangan_pembayaran_angsuran = 0;
            $kekurangan_pembayaran_margin = 0;

            if(json_decode($pembiayaan->detail)->sisa_angsuran > $tagihan_pokok_angsuran)
            {
                if($jumlah_bayar_angsuran < $tagihan_pokok_angsuran)
                {
                    $kekurangan_pembayaran_angsuran = $tagihan_pokok_angsuran - $jumlah_bayar_angsuran;
                }
            }
            else
            {
                $kekurangan_pembayaran_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran - $jumlah_bayar_angsuran;
            }

            if($jumlah_bayar_angsuran > $tagihan_pokok_angsuran)
            {
                $kelebihan_angsuran_bulanan = $jumlah_bayar_angsuran - $tagihan_pokok_angsuran;
            }
            else
            {
                $kelebihan_angsuran_bulanan = 0;
            }
            
            if($jumlah_bayar_margin > $tagihan_pokok_margin)
            {
                $kelebihan_margin_bulanan = $jumlah_bayar_margin - $tagihan_pokok_margin;
            }
            else
            {
                $kelebihan_margin_bulanan = 0;
            }
            
            $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran;
            $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin;
            $total_pinjaman = json_decode($pembiayaan->detail)->pinjaman;
            $total_margin = json_decode($pembiayaan->detail)->margin;
            
            $tagihan = $tagihan_pokok_angsuran + $tagihan_pokok_margin;

            $sisa_margin_bulanan = $kekurangan_pembayaran_margin;
            $sisa_angsuran_bulanan = $kekurangan_pembayaran_angsuran;

            $sisa_pinjaman = (json_decode($pembiayaan->detail)->sisa_pinjaman) - $jumlah_bayar_angsuran;
            
            $detailToUpdatePembiayaan = [
                "pinjaman"          => json_decode($pembiayaan->detail)->pinjaman,
                "margin"            => json_decode($pembiayaan->detail)->margin,
                "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                "total_pinjaman"    => json_decode($pembiayaan->detail)->total_pinjaman,
                "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                "sisa_pinjaman"     => $sisa_pinjaman,
                "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                "lama_angsuran"     => json_decode($pembiayaan->detail)->lama_angsuran,
                "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                "tagihan_bulanan"   => json_decode($pembiayaan->detail)->tagihan_bulanan,
                "jumlah_angsuran_bulanan"  => round($total_pinjaman / json_decode($pembiayaan->detail)->lama_angsuran), 
                "jumlah_margin_bulanan"    => round($total_margin / json_decode($pembiayaan->detail)->lama_angsuran),
                "sisa_ang_bln"      => $sisa_angsuran_bulanan,
                "sisa_mar_bln"      => 0,
                "kelebihan_angsuran_bulanan" => $kelebihan_angsuran_bulanan,
                "kelebihan_margin_bulanan" =>  $kelebihan_margin_bulanan,
                "id_pengajuan"      => null
            ];

            $bmt_tujuan_angsuran = BMT::where('id_rekening', $bank_tujuan_angsuran)->first();
            $bmt_pembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->first();
            $bmt_shu_berjalan = BMT::where('id_rekening', 122)->first();
            $bmt_rekening_pendapatan = BMT::where('id_rekening', $id_rekening_pembiayaan)->first();
            
            $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
            $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) + (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));
            
            if($data->debit == 2)
            {
                $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
                $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) - (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));

                $detailToPenyimpananTabungan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $tabungan->jenis_tabungan,
                    "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                    "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                    "saldo_awal"        => $saldo_awal_pengirim,
                    "saldo_akhir"       => $saldo_akhir_pengirim
                ];
                $dataToPenyimpananTabungan = [
                    "id_user"       => $tabungan->id_user,
                    "id_tabungan"   => $tabungan->id,
                    "status"        => "Angsuran Pembiayaan MRB",
                    "transaksi"     => $detailToPenyimpananTabungan,
                    "teller"        => Auth::user()->id
                ];

                $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
            }

            $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
            $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) - floatval($jumlah_bayar_angsuran);

            if($data->debit == 2)
            {
                if(json_decode($tabungan->detail)->saldo > ($jumlah_bayar_angsuran + $jumlah_bayar_margin))
                {
                    $detailToPenyimpananPembiayaan = [
                        "teller"            => Auth::user()->id,
                        "dari_rekening"     => $dari_bank,
                        "untuk_rekening"    => $bank_tujuan_angsuran,
                        "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                        "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                        "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                        "margin"            => json_decode($pembiayaan->detail)->margin,
                        "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
                        "tagihan"           => $tagihan,
                        "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                        "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                        "sisa_pinjaman"     => $sisa_pinjaman,
                        "bayar_angsuran"    => $jumlah_bayar_angsuran,
                        "bayar_margin"      => $jumlah_bayar_margin,
                        "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
                    ];
                    $dataToPenyimpananPembiayaan = [
                        "id_user"       => $pembiayaan->id_user,
                        "id_pembiayaan" => $pembiayaan->id,
                        "status"        => "Angsuran Pembiayaan [Pokok]",
                        "transaksi"     => $detailToPenyimpananPembiayaan,
                        "teller"        => Auth::user()->id
                    ];

                    $detailToPenyimpananBMT = [
                        "jumlah"        => -$jumlah_bayar_angsuran,
                        "saldo_awal"    => $saldo_awal_pembiayaan,
                        "saldo_akhir"   => $saldo_akhir_pembiayaan,
                        "id_pengajuan"  => null
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user"   => $pembiayaan->id_user,
                        "id_bmt"    => $bmt_pembiayaan->id,
                        "status"    => "Angsuran",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller"    => Auth::user()->id
                    ];

                    if($this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                    )
                    {
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_tujuan_angsuran->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_angsuran + $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                        
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $dataToPenyimpananBMT['id_bmt'] = $bmt_rekening_pendapatan->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_rekening_pendapatan->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                        $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                        $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                        $updateBMTPendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->update([ "saldo" => $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin ]);

                        if($kekurangan_pembayaran_angsuran <= 0)
                        {
                            $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1)->format('yy-m-d');
                        }
                        else
                        {
                            $tempo_pembayaran = $pembiayaan->tempo;
                        }
                        
                        $updatePembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                            'tempo'     => $tempo_pembayaran,
                            'detail'    => json_encode($detailToUpdatePembiayaan),
                            'angsuran_ke' => $pembiayaan->angsuran_ke + 1
                        ]);
                        
                        // Update margin yang sudah dibayarkan user
                        if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                        {
                            $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                        }
                        else
                        {
                            $total_margin_anggota = floatval($jumlah_bayar_margin);
                        }
                        $user_pembiayaan->wajib_pokok = json_encode([
                            "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                            "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                            "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                            "margin" => $total_margin_anggota
                        ]);
                        $user_pembiayaan->save();

                        if($sisa_pinjaman <= 10)
                        {
                            $detailToUpdatePembiayaan['sisa_angsuran'] = 0;
                            $detailToUpdatePembiayaan['sisa_margin'] = 0;
                            $detailToUpdatePembiayaan['sisa_pinjaman'] = 0;
                            
                            $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                                'detail'    => json_encode($detailToUpdatePembiayaan),
                                'status'    => 'lunas'
                            ]);
                        }

                        DB::commit();
                        $response = array("type" => "success", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                    }
                }
                elseif(json_decode($tabungan->detail)->saldo < json_decode($rekening_tabungan->detail)->saldo_min)
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Tabungan " . $tabungan->jenis_tabungan . " Melampaui Batas Transaksi.");
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Saldo " . $tabungan->jenis_tabungan . " Tidak Cukup.");
                }
            }
            else
            {
                $detailToPenyimpananPembiayaan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $dari_bank,
                    "untuk_rekening"    => $bank_tujuan_angsuran,
                    "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                    "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                    "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                    "margin"            => json_decode($pembiayaan->detail)->margin,
                    "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
                    "tagihan"           => $tagihan,
                    "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                    "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                    "sisa_pinjaman"     => $sisa_pinjaman,
                    "bayar_angsuran"    => $jumlah_bayar_angsuran,
                    "bayar_margin"      => $jumlah_bayar_margin,
                    "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
                ];
                $dataToPenyimpananPembiayaan = [
                    "id_user"       => $pembiayaan->id_user,
                    "id_pembiayaan" => $pembiayaan->id,
                    "status"        => "Angsuran Pembiayaan [Pokok]",
                    "transaksi"     => $detailToPenyimpananPembiayaan,
                    "teller"        => Auth::user()->id
                ];

                $detailToPenyimpananBMT = [
                    "jumlah"        => -$jumlah_bayar_angsuran,
                    "saldo_awal"    => $saldo_awal_pembiayaan,
                    "saldo_akhir"   => $saldo_akhir_pembiayaan,
                    "id_pengajuan"  => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user"   => $pembiayaan->id_user,
                    "id_bmt"    => $bmt_pembiayaan->id,
                    "status"    => "Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];

                if($this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                )
                {
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_tujuan_angsuran->id;
                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_angsuran + $jumlah_bayar_margin;
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                    
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                    
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $dataToPenyimpananBMT['id_bmt'] = $bmt_rekening_pendapatan->id;
                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_rekening_pendapatan->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                    $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                    $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                    $updateBMTPendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->update([ "saldo" => $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin ]);

                    if($kekurangan_pembayaran_angsuran <= 0)
                    {
                        $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1)->format('yy-m-d');
                    }
                    else
                    {
                        $tempo_pembayaran = $pembiayaan->tempo;
                    }
                    
                    $updatePembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                        'tempo'     => $tempo_pembayaran,
                        'detail'    => json_encode($detailToUpdatePembiayaan),
                        'angsuran_ke' => $pembiayaan->angsuran_ke + 1
                    ]);
                    
                    // Update margin yang sudah dibayarkan user
                    if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                    {
                        $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                    }
                    else
                    {
                        $total_margin_anggota = floatval($jumlah_bayar_margin);
                    }
                    $user_pembiayaan->wajib_pokok = json_encode([
                        "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                        "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                        "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                        "margin" => $total_margin_anggota
                    ]);
                    $user_pembiayaan->save();

                    if($sisa_pinjaman <= 10)
                    {
                        $detailToUpdatePembiayaan['sisa_angsuran'] = 0;
                        $detailToUpdatePembiayaan['sisa_margin'] = 0;
                        $detailToUpdatePembiayaan['sisa_pinjaman'] = 0;
                        
                        $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                            'detail'    => json_encode($detailToUpdatePembiayaan),
                            'status'    => 'lunas'
                        ]);
                    }

                    DB::commit();
                    $response = array("type" => "success", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                }
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
        }
        return $response;
    }

    /** 
     * Angsuran pembiayaan MRB via teller
     * @return Response
    */
    public function angsuranMRB($data)
    {
        DB::beginTransaction();
        try
        {
            $pembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->first();
            $penyimpananPembiayaan = PenyimpananPembiayaan::where('id_pembiayaan', $pembiayaan->id)->orderBy('created_at', 'desc')->take(1)->get();
            $user_pembiayaan = User::where('id', $pembiayaan->id_user)->first();

            $jenis_pembiayaan = "PEMBIAYAAN MRB";
            $id_rekening_pembiayaan = 100;
        
            if($data->debit == 0)
            {
                $bank_tujuan_angsuran = json_decode(Auth::user()->detail)->id_rekening;
                $dari_bank = "Tunai";
            }
            if($data->debit == 1)
            {
                $bank_tujuan_angsuran = $data->bank;
                $dari_bank = "[" . $data->nobank . "] " . $data->daribank;
            }
            if($data->debit == 2)
            {
                $tabungan =  Tabungan::where('id', $data->tabungan)->first();
                $rekening_tabungan = Rekening::where('id', $tabungan->id_rekening)->first();
                $bmt_tabungan = BMT::where('id_rekening', $tabungan->id_rekening)->first();
                $bank_tujuan_angsuran = $tabungan->id_rekening;
                $dari_bank = "[" . $tabungan->id_tabungan . "] " . $tabungan->jenis_tabungan;
            }

            $tagihan_pokok_angsuran = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
            $jumlah_bayar_angsuran = preg_replace('/[^\d.]/', '', $data->bayar_ang);
            $tagihan_pokok_margin = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;
            $jumlah_bayar_margin = preg_replace('/[^\d.]/', '', $data->bayar_mar);
            $kekurangan_pembayaran_angsuran = 0;
            $kekurangan_pembayaran_margin = 0;

            if(json_decode($pembiayaan->detail)->sisa_margin > 0)
            {
                if($jumlah_bayar_margin < $tagihan_pokok_margin)
                {
                    $kekurangan_pembayaran_margin = $tagihan_pokok_margin - $jumlah_bayar_margin;
                    $jumlah_bayar_angsuran = $jumlah_bayar_angsuran - $kekurangan_pembayaran_margin;
                    $jumlah_bayar_margin = $jumlah_bayar_margin + $kekurangan_pembayaran_margin;
                }
            }

            if(json_decode($pembiayaan->detail)->sisa_angsuran > $tagihan_pokok_angsuran)
            {
                if($jumlah_bayar_angsuran < $tagihan_pokok_angsuran)
                {
                    $kekurangan_pembayaran_angsuran = $tagihan_pokok_angsuran - $jumlah_bayar_angsuran;
                }
            }
            else
            {
                $kekurangan_pembayaran_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran - $jumlah_bayar_angsuran;
            }

            if($jumlah_bayar_angsuran > $tagihan_pokok_angsuran)
            {
                $kelebihan_angsuran_bulanan = $jumlah_bayar_angsuran - $tagihan_pokok_angsuran;
            }
            else
            {
                $kelebihan_angsuran_bulanan = 0;
            }
            
            if($jumlah_bayar_margin > $tagihan_pokok_margin)
            {
                $kelebihan_margin_bulanan = $jumlah_bayar_margin - $tagihan_pokok_margin;
            }
            else
            {
                $kelebihan_margin_bulanan = 0;
            }

            $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran;
            $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin;
            $total_pinjaman = json_decode($pembiayaan->detail)->pinjaman;
            $total_margin = json_decode($pembiayaan->detail)->margin;
            
            $tagihan = $tagihan_pokok_angsuran + $tagihan_pokok_margin;

            $sisa_margin_bulanan = $kekurangan_pembayaran_margin;
            $sisa_angsuran_bulanan = $kekurangan_pembayaran_angsuran;

            $sisa_pinjaman = (json_decode($pembiayaan->detail)->sisa_pinjaman) - ($jumlah_bayar_angsuran + $jumlah_bayar_margin);
            
            $detailToUpdatePembiayaan = [
                "pinjaman"          => json_decode($pembiayaan->detail)->pinjaman,
                "margin"            => json_decode($pembiayaan->detail)->margin,
                "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                "total_pinjaman"    => json_decode($pembiayaan->detail)->total_pinjaman,
                "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                "sisa_pinjaman"     => $sisa_pinjaman,
                "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                "lama_angsuran"     => json_decode($pembiayaan->detail)->lama_angsuran,
                "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                "tagihan_bulanan"   => json_decode($pembiayaan->detail)->tagihan_bulanan,
                "jumlah_angsuran_bulanan"  => round($total_pinjaman / json_decode($pembiayaan->detail)->lama_angsuran), 
                "jumlah_margin_bulanan"    => round($total_margin / json_decode($pembiayaan->detail)->lama_angsuran),
                "sisa_ang_bln"      => $sisa_angsuran_bulanan,
                "sisa_mar_bln"      => 0,
                "kelebihan_angsuran_bulanan" => $kelebihan_angsuran_bulanan,
                "kelebihan_margin_bulanan" =>  $kelebihan_margin_bulanan,
                "id_pengajuan"      => null
            ];
            
            $bmt_tujuan_angsuran = BMT::where('id_rekening', $bank_tujuan_angsuran)->first();
            $bmt_piutang_mrb = BMT::where('id_rekening', 101)->first();
            $bmt_pembiayaan = BMT::where('id_rekening',  $id_rekening_pembiayaan)->first();
            $bmt_shu_berjalan = BMT::where('id_rekening', 122)->first();
            $bmt_pendapatan_mrb = BMT::where('id_rekening', 130)->first();

            $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
            $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) + (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));
            
            if($data->debit == 2)
            {
                $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
                $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) - (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));

                $detailToPenyimpananTabungan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $tabungan->jenis_tabungan,
                    "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                    "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                    "saldo_awal"        => $saldo_awal_pengirim,
                    "saldo_akhir"       => $saldo_akhir_pengirim
                ];
                $dataToPenyimpananTabungan = [
                    "id_user"       => $tabungan->id_user,
                    "id_tabungan"   => $tabungan->id,
                    "status"        => "Angsuran Pembiayaan MRB",
                    "transaksi"     => $detailToPenyimpananTabungan,
                    "teller"        => Auth::user()->id
                ];

                $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
            }

            $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
            $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) - (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));

            if($data->debit == 2)
            {
                if(json_decode($tabungan->detail)->saldo > ($jumlah_bayar_angsuran + $jumlah_bayar_margin))
                {
                    $detailToPenyimpananPembiayaan = [
                        "teller"            => Auth::user()->id,
                        "dari_rekening"     => $dari_bank,
                        "untuk_rekening"    => $bank_tujuan_angsuran,
                        "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                        "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                        "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                        "margin"            => json_decode($pembiayaan->detail)->margin,
                        "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
                        "tagihan"           => $tagihan,
                        "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                        "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                        "sisa_pinjaman"     => $sisa_pinjaman,
                        "bayar_angsuran"    => $jumlah_bayar_angsuran,
                        "bayar_margin"      => $jumlah_bayar_margin,
                        "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
                    ];
                    $dataToPenyimpananPembiayaan = [
                        "id_user"       => $pembiayaan->id_user,
                        "id_pembiayaan" => $pembiayaan->id,
                        "status"        => "Angsuran Pembiayaan [Pokok]",
                        "transaksi"     => $detailToPenyimpananPembiayaan,
                        "teller"        => Auth::user()->id
                    ];

                    $detailToPenyimpananBMT = [
                        "jumlah"        => -$jumlah_bayar_angsuran - $jumlah_bayar_margin,
                        "saldo_awal"    => $saldo_awal_pembiayaan,
                        "saldo_akhir"   => $saldo_akhir_pembiayaan,
                        "id_pengajuan"  => null
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user"   => $pembiayaan->id_user,
                        "id_bmt"    => $bmt_pembiayaan->id,
                        "status"    => "Angsuran",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller"    => Auth::user()->id
                    ];

                    if($this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                    )
                    {
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_tujuan_angsuran->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_angsuran + $jumlah_bayar_margin;
                        if($data->debit == 2)
                        {
                            $detailToPenyimpananBMT['jumlah'] = -$jumlah_bayar_angsuran - $jumlah_bayar_margin;
                        }
                        $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                        
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_piutang_mrb->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_piutang_mrb->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_piutang_mrb->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        
                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $dataToPenyimpananBMT['id_bmt'] = $bmt_pendapatan_mrb->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_pendapatan_mrb->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_pendapatan_mrb->saldo + $jumlah_bayar_margin;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                        $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                        $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                        $updatePiutangMRB = BMT::where('id_rekening', 101)->update([ "saldo" => $bmt_piutang_mrb->saldo + $jumlah_bayar_margin ]);
                        $updatePendapatanMRB = BMT::where('id_rekening', 130)->update([ "saldo" => $bmt_pendapatan_mrb->saldo + $jumlah_bayar_margin ]);

                        if($kekurangan_pembayaran_angsuran <= 0)
                        {
                            $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1)->format('yy-m-d');
                        }
                        else
                        {
                            $tempo_pembayaran = $pembiayaan->tempo;
                        }

                        if($data->debit == 2)
                        {
                            $dataToUpdateTabungan = [
                                "saldo" => json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin),
                                "id_pengajuan" => null
                            ];

                            $tabungan->detail = json_encode($dataToUpdateTabungan);
                            $tabungan->save();
                        }
                        
                        $updatePembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                            'tempo'     => $tempo_pembayaran,
                            'detail'    => json_encode($detailToUpdatePembiayaan),
                            'angsuran_ke' => $pembiayaan->angsuran_ke + 1
                        ]);

                        // Update margin yang sudah dibayarkan user
                        if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                        {
                            $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                        }
                        else
                        {
                            $total_margin_anggota = floatval($jumlah_bayar_margin);
                        }
                        $user_pembiayaan->wajib_pokok = json_encode([
                            "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                            "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                            "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                            "margin" => $total_margin_anggota
                        ]);
                        $user_pembiayaan->save();

                        if($sisa_pinjaman <= 10)
                        {
                            $detailToUpdatePembiayaan['sisa_angsuran'] = 0;
                            $detailToUpdatePembiayaan['sisa_margin'] = 0;
                            $detailToUpdatePembiayaan['sisa_pinjaman'] = 0;

                            $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                                'detail'    => json_encode($detailToUpdatePembiayaan),
                                'status'    => 'lunas'
                            ]);
                        }

                        DB::commit();
                        $response = array("type" => "success", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
                    }
                }
                elseif(json_decode($tabungan->detail)->saldo < json_decode($rekening_tabungan->detail)->saldo_min)
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Tabungan " . $tabungan->jenis_tabungan . " Melampaui Batas Transaksi.");
                }
                else {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Saldo " . $tabungan->jenis_tabungan . " Tidak Cukup.");
                }
            } 
            else {
                $detailToPenyimpananPembiayaan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $dari_bank,
                    "untuk_rekening"    => $bank_tujuan_angsuran,
                    "angsuran_pokok"    => json_decode($pembiayaan->detail)->angsuran_pokok,
                    "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                    "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                    "margin"            => json_decode($pembiayaan->detail)->margin,
                    "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
                    "tagihan"           => $tagihan,
                    "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                    "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                    "sisa_pinjaman"     => $sisa_pinjaman,
                    "bayar_angsuran"    => $jumlah_bayar_angsuran,
                    "bayar_margin"      => $jumlah_bayar_margin,
                    "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
                ];
                $dataToPenyimpananPembiayaan = [
                    "id_user"       => $pembiayaan->id_user,
                    "id_pembiayaan" => $pembiayaan->id,
                    "status"        => "Angsuran Pembiayaan [Pokok]",
                    "transaksi"     => $detailToPenyimpananPembiayaan,
                    "teller"        => Auth::user()->id
                ];

                $detailToPenyimpananBMT = [
                    "jumlah"        => -$jumlah_bayar_angsuran - $jumlah_bayar_margin,
                    "saldo_awal"    => $saldo_awal_pembiayaan,
                    "saldo_akhir"   => $saldo_akhir_pembiayaan,
                    "id_pengajuan"  => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user"   => $pembiayaan->id_user,
                    "id_bmt"    => $bmt_pembiayaan->id,
                    "status"    => "Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];

                if($this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                )
                {
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_tujuan_angsuran->id;
                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_angsuran + $jumlah_bayar_margin;
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                    
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_piutang_mrb->id;
                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_piutang_mrb->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_piutang_mrb->saldo + $jumlah_bayar_margin;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                    
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                    
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $dataToPenyimpananBMT['id_bmt'] = $bmt_pendapatan_mrb->id;
                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_pendapatan_mrb->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_pendapatan_mrb->saldo + $jumlah_bayar_margin;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                    $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                    $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                    $updatePiutangMRB = BMT::where('id_rekening', 101)->update([ "saldo" => $bmt_piutang_mrb->saldo + $jumlah_bayar_margin ]);
                    $updatePendapatanMRB = BMT::where('id_rekening', 130)->update([ "saldo" => $bmt_pendapatan_mrb->saldo + $jumlah_bayar_margin ]);

                    if($kekurangan_pembayaran_angsuran <= 0)
                    {
                        $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1)->format('yy-m-d');
                    }
                    else
                    {
                        $tempo_pembayaran = $pembiayaan->tempo;
                    }

                    $updatePembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                        'tempo'     => $tempo_pembayaran,
                        'detail'    => json_encode($detailToUpdatePembiayaan),
                        'angsuran_ke' => $pembiayaan->angsuran_ke + 1
                    ]);

                    // Update margin yang sudah dibayarkan user
                    if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                    {
                        $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                    }
                    else
                    {
                        $total_margin_anggota = floatval($jumlah_bayar_margin);
                    }
                    $user_pembiayaan->wajib_pokok = json_encode([
                        "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                        "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                        "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                        "margin" => $total_margin_anggota
                    ]);
                    $user_pembiayaan->save();

                    if($sisa_pinjaman <= 10)
                    {
                        $detailToUpdatePembiayaan['sisa_angsuran'] = 0;
                        $detailToUpdatePembiayaan['sisa_margin'] = 0;
                        $detailToUpdatePembiayaan['sisa_pinjaman'] = 0;

                        $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                            'detail'    => json_encode($detailToUpdatePembiayaan),
                            'status'    => 'lunas'
                        ]);
                    }

                    DB::commit();
                    $response = array("type" => "success", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
                }
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
        }
        return $response;
    }

    /** 
     * Konfirm pelunasan pembiayaan  anggota
     * @return Response
    */
    public function confirmPelunasan($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_);
            $pembiayaan = Pembiayaan::where('id_pembiayaan', json_decode($pengajuan->detail)->id_pembiayaan)->first();
            $user_pembiayaan = User::where('id', $pembiayaan->id_user)->first();

            $id_rekening_pembiayaan = $pembiayaan->id_rekening;
            $nama_rekening_pembiayaan = $pembiayaan->jenis_pembiayaan;
            
            $sisa_angsuran = json_decode($pengajuan->detail)->sisa_ang;
            $sisa_margin = json_decode($pengajuan->detail)->sisa_mar;
            $jumlah_bayar_angsuran = json_decode($pengajuan->detail)->bayar_ang;
            $jumlah_bayar_margin = json_decode($pengajuan->detail)->bayar_mar;

            if($id_rekening_pembiayaan == 100)
            {
                $id_rekening_pendapatan = 130;
            }
            if($id_rekening_pembiayaan == 99)
            {
                $id_rekening_pendapatan = 129;
            }
            if($id_rekening_pembiayaan == 102)
            {
                $id_rekening_pendapatan = 131;
            }

            if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
            {
                $id_tabungan = json_decode($pengajuan->detail)->bank;
                $tabungan = Tabungan::where('id', $id_tabungan)->first();
                $rekening_tabungan = Rekening::where('id', $tabungan->id_rekening)->first();
                $id_tujuan_pelunasan = $tabungan->id_rekening;
            }
            if(json_decode($pengajuan->detail)->angsuran == "Transfer")
            {
                $id_tujuan_pelunasan = json_decode($pengajuan->detail)->bank;
            }
            if(json_decode($pengajuan->detail)->angsuran == "Tunai")
            {
                $id_tujuan_pelunasan = json_decode(Auth::user()->detail)->id_rekening;
            }
            
            $bmt_rekening_pendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->first();
            $bmt_pembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->first();
            $bmt_tujuan_pelunasan = BMT::where('id_rekening', $id_tujuan_pelunasan)->first();
            $bmt_piutang_yang_ditangguhkan = BMT::where('id_rekening', 101)->first();
            $bmt_shu_berjalan = BMT::where('id_rekening', 122)->first();

            $saldo_awal_tujuan_pelunasan = $bmt_tujuan_pelunasan->saldo;
            $saldo_akhir_tujuan_pelunasan = $bmt_tujuan_pelunasan->saldo + ($jumlah_bayar_angsuran + $jumlah_bayar_margin);
            
            if($id_rekening_pembiayaan == 100)
            {
                $jumlah_sisa_angsuran = $sisa_angsuran + $sisa_margin;
                $saldo_awal_pembiayaan = $bmt_pembiayaan->saldo;
                $saldo_akhir_pembiayaan = $bmt_pembiayaan->saldo - ($sisa_angsuran + $sisa_margin);
            }
            else
            {
                $jumlah_sisa_angsuran = $sisa_angsuran;
                $saldo_awal_pembiayaan = $bmt_pembiayaan->saldo;
                $saldo_akhir_pembiayaan = $bmt_pembiayaan->saldo - $sisa_angsuran;
            }

            if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
            {
                $saldo_awal_tujuan_pelunasan = json_decode($tabungan->detail)->saldo;
                $saldo_akhir_tujuan_pelunasan = json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin);

                $detailToPenyimpananTabungan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $tabungan->jenis_tabungan,
                    "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                    "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                    "saldo_awal"        => json_decode($tabungan->detail)->saldo,
                    "saldo_akhir"       => json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin)
                ];
                $dataToPenyimpananTabungan = [
                    "id_user"           => $tabungan->id_user,
                    "id_tabungan"       => $tabungan->id,
                    "status"            => "Pelunasan " . $pembiayaan->jenis_pembiayaan,
                    "transaksi"         => $detailToPenyimpananTabungan,
                    "teller"            => Auth::user()->id
                ];

                $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
            }

            $detailToPenyimpananPembiayaan = [
                "teller"            => Auth::user()->id,
                "dari_rekening"     => $bmt_pembiayaan->nama,
                "untuk_rekening"    => $bmt_tujuan_pelunasan->nama,
                "angsuran_pokok"    => json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan,
                "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
                "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
                "margin"            => json_decode($pembiayaan->detail)->margin,
                "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
                "tagihan"           => json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan + json_decode($pembiayaan->detail)->jumlah_margin_bulanan,
                "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
                "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
                "sisa_pinjaman"     => $id_rekening_pembiayaan == 100 ? json_decode($pembiayaan->detail)->sisa_pinjaman - ($jumlah_bayar_angsuran + $jumlah_bayar_margin) : json_decode($pembiayaan->detail)->sisa_pinjaman - $jumlah_bayar_angsuran,
                "bayar_angsuran"    => $jumlah_bayar_angsuran,
                "bayar_margin"      => $jumlah_bayar_margin,
                "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
            ];
            $dataToPenyimpananPembiayaan = [
                "id_user"       => $pembiayaan->id_user,
                "id_pembiayaan" => $pembiayaan->id,
                "status"        => "Pelunasan Pembiayaan",
                "transaksi"     => $detailToPenyimpananPembiayaan,
                "teller"        => Auth::user()->id
            ];

            $this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan);

            $detailToPenyimpananBMT = [
                "jumlah"            => json_decode($pengajuan->detail)->angsuran == "Tabungan" ? -$sisa_angsuran-$jumlah_bayar_margin : $sisa_angsuran + $jumlah_bayar_margin,
                "saldo_awal"        => $saldo_awal_tujuan_pelunasan,
                "saldo_akhir"       => $saldo_akhir_tujuan_pelunasan,
                "id_pengajuan"      => $pengajuan->id
            ];
            $dataToPenyimpananBMT = [
                "id_user"           => $pengajuan->id_user,
                "id_bmt"            => $bmt_tujuan_pelunasan->id,
                "status"            => "Pelunasan " . $bmt_pembiayaan->nama,
                "transaksi"         => $detailToPenyimpananBMT,
                "teller"            => Auth::user()->id
            ];

            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
            
            if($id_rekening_pembiayaan == 100)
            {
                $detailToPenyimpananBMT['jumlah'] = $sisa_margin;
                $detailToPenyimpananBMT['saldo_awal'] = $bmt_piutang_yang_ditangguhkan->saldo;
                $detailToPenyimpananBMT['saldo_akhir'] = $bmt_piutang_yang_ditangguhkan->saldo + $sisa_margin;
                $dataToPenyimpananBMT['id_bmt'] = $bmt_piutang_yang_ditangguhkan->id;
                $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
            }

            $detailToPenyimpananBMT['jumlah'] = -$jumlah_sisa_angsuran;
            $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pembiayaan;
            $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pembiayaan;
            $dataToPenyimpananBMT['id_bmt'] = $bmt_pembiayaan->id;
            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
            $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
            $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
            $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
            $detailToPenyimpananBMT['saldo_awal'] = $bmt_rekening_pendapatan->saldo;
            $detailToPenyimpananBMT['saldo_akhir'] = $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin;
            $dataToPenyimpananBMT['id_bmt'] = $bmt_rekening_pendapatan->id;
            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
            {
                if(json_decode($tabungan->detail)->saldo < json_decode($rekening_tabungan->detail)->saldo_min)
                {
                    $update_tabungan = "error";
                }
                else
                {
                    $dataToUpdateTabungan = [
                        "saldo" => $saldo_akhir_tujuan_pelunasan,
                        "id_pengajuan"  => $pengajuan->id
                    ];
                    
                    $tabungan->detail = json_encode($dataToUpdateTabungan);
                    $tabungan->save();
                    $update_tabungan = "success";
                }
            }

            $pembiayaan->detail = json_encode([
                "pinjaman" => json_decode($pembiayaan->detail)->pinjaman,
                "margin" => json_decode($pembiayaan->detail)->margin,
                "nisbah" => json_decode($pembiayaan->detail)->nisbah,
                "total_pinjaman" => json_decode($pembiayaan->detail)->total_pinjaman,
                "sisa_angsuran" => $sisa_angsuran - $jumlah_bayar_angsuran,
                "sisa_margin" => $sisa_margin - $jumlah_bayar_margin,
                "sisa_pinjaman" => $id_rekening_pembiayaan == 100 ? json_decode($pembiayaan->detail)->sisa_pinjaman - ($jumlah_bayar_angsuran + $jumlah_bayar_margin) : json_decode($pembiayaan->detail)->sisa_pinjaman - $jumlah_bayar_angsuran,
                "angsuran_pokok" => json_decode($pembiayaan->detail)->angsuran_pokok,
                "lama_angsuran" => json_decode($pembiayaan->detail)->lama_angsuran,
                "angsuran_ke" => json_decode($pembiayaan->detail)->angsuran_ke + 1,
                "tagihan_bulanan" => json_decode($pembiayaan->detail)->tagihan_bulanan,
                "jumlah_angsuran_bulanan" => json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan,
                "jumlah_margin_bulanan" => json_decode($pembiayaan->detail)->jumlah_margin_bulanan,
                "sisa_ang_bln" => 0,
                "sisa_mar_bln" => 0,
                "kelebihan_angsuran_bulanan" => 0,
                "kelebihan_margin_bulanan" => 0,
                "id_pengajuan" => $pengajuan->id,
            ]);
            $pembiayaan->tempo = Carbon::parse($pembiayaan->tempo)->addMonth(1);
            $pembiayaan->angsuran_ke = $pembiayaan->angsuran_ke + 1;
            $pembiayaan->status = "lunas";

            $bmt_tujuan_pelunasan->saldo = $saldo_akhir_tujuan_pelunasan;
            $bmt_pembiayaan->saldo = $saldo_akhir_pembiayaan;
            $bmt_rekening_pendapatan->saldo = $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin;
            $bmt_shu_berjalan->saldo = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
            if($id_rekening_pembiayaan == 100)
            {
                $bmt_piutang_yang_ditangguhkan->saldo = $bmt_piutang_yang_ditangguhkan->saldo + $sisa_margin;
                $bmt_piutang_yang_ditangguhkan->save();
            }
            
            if(json_decode($pengajuan->detail)->angsuran == "Tabungan")
            {
                if($update_tabungan == "success")
                {
                    if(
                        $bmt_tujuan_pelunasan->save() && $bmt_pembiayaan->save() && $bmt_rekening_pendapatan->save() &&
                        $bmt_shu_berjalan->save() && $pembiayaan->save()
                    )
                    {
                        $pengajuan->status = "Sudah Dikonfirmasi"; $pengajuan->teller = Auth::user()->id; $pengajuan->save();
                        
                        // Update margin yang sudah dibayarkan user
                        if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                        {
                            $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                        }
                        else
                        {
                            $total_margin_anggota = floatval($jumlah_bayar_margin);
                        }
                        $user_pembiayaan->wajib_pokok = json_encode([
                            "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                            "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                            "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                            "margin" => $total_margin_anggota
                        ]);
                        $user_pembiayaan->save();

                        DB::commit();
                        $response = array("type" => "success", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Terjadi kesalahan.");
                    }
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Tabungan Anda Melampaui Limit Transaksi.");
                }
            }
            else
            {
                if(
                    $bmt_tujuan_pelunasan->save() && $bmt_pembiayaan->save() && $bmt_rekening_pendapatan->save() &&
                    $bmt_shu_berjalan->save() && $pembiayaan->save()
                )
                {
                    $pengajuan->status = "Sudah Dikonfirmasi"; $pengajuan->teller = Auth::user()->id; $pengajuan->save();
                    
                    // Update margin yang sudah dibayarkan user
                    if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                    {
                        $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                    }
                    else
                    {
                        $total_margin_anggota = floatval($jumlah_bayar_margin);
                    }
                    $user_pembiayaan->wajib_pokok = json_encode([
                        "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                        "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                        "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                        "margin" => $total_margin_anggota
                    ]);
                    $user_pembiayaan->save();

                    DB::commit();
                    $response = array("type" => "success", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Terjadi kesalahan.");
                }
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi");
        }

        return $response;
    }

    /** 
     * Export perjanjian pembiayaan
     * @return Response
    */
    public function exportPerjanjian($dataPembiayaan, $dataForm, $dataJaminan="")
    {
        $user_pembiayaan = User::where('id', $dataPembiayaan['id_user'])->first();
        $data_pengajuan = Pengajuan::where('id', $dataPembiayaan['id_pengajuan'])->first();
        if($dataJaminan !== "")
        {
            $detail_jaminan = $dataJaminan['transaksi']['field'];
        }
        else
        {
            $data_jaminan = PenyimpananJaminan::where('id_pengajuan', $dataPembiayaan['id_pengajuan'])->first();
            $detail_jaminan = json_decode($data_jaminan->transaksi)->field;
        }
        
        $data_template_row = array();
        foreach ($detail_jaminan as $key => $value) {
            array_push(
                $data_template_row, array('barang_titipan_desc_title' => $key, 'barang_titipan_desc_content' => $value)
            );
        }

        $export_data = array(
            "user"                              => $user_pembiayaan->nama,
            "id"                                => $dataPembiayaan['id'],
            "data_template"                     => array(
                "hari_perjanjian"               => $this->helperRepository->getDayName(),
                "tanggal_perjanjian"            => Carbon::now()->format("d") . " " . $this->helperRepository->getMonthName() . " " . Carbon::now()->format("Y"),
                "tempat_perjanjian"             => "Surabaya",
                "pemberi_perjanjian"            => "HA. SUNOYO HASYIM S.Sos, Apr",
                "jabatan_pemberi_perjanjian"    => "MANAGER BMT-MUDA SURABAYA",
                "alamat_cabang"                 => "Jl. Kedinding  Surabaya",
                "peminjam_pihak_1"              => strtoupper($user_pembiayaan->nama),
                "alamat_peminjam_pihak_1"       => strtoupper($user_pembiayaan->alamat),
                "nik_peminjam_pihak_1"          => $user_pembiayaan->no_ktp,
                "peminjam_pihak_2"              => strtoupper($dataForm->saksi1),
                "alamat_peminjam_pihak_2"       => strtoupper($dataForm->alamat2),
                "nik_peminjam_pihak_2"          => $dataForm->ktp2,
                "jumlah_pinjaman"               => number_format($dataPembiayaan['detail']['pinjaman'],0,",","."),
                "jumlah_pinjaman_text"          => strtoupper($this->helperRepository->getMoneyInverse($dataPembiayaan['detail']['pinjaman'])) . " RUPIAH",
                "lama_angsuran"                 => $dataPembiayaan['detail']['lama_angsuran'],
                "batas_akhir_angsuran"          => Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran'])->format("d") . " " . $this->helperRepository->getMonthName( Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran']) ) . " " . Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran'])->format("Y"),
                "angsuran_bulanan"              => number_format($dataPembiayaan['detail']['jumlah_angsuran_bulanan'],0,",","."),
                "angsuran_pertama"              => Carbon::now()->addMonth(1)->format("d") . " " . $this->helperRepository->getMonthName( Carbon::now()->addMonth(1) ) . " " . Carbon::now()->addMonth(1)->format("Y"),
                "saksi"                         => $dataForm->saksi2,
                "pekerjaan_peminjam_pihak_1"    => "",
                "pekerjaan_peminjam_pihak_2"    => "",
                "jumlah_margin"                 => $dataPembiayaan['detail']['margin'],
                "no_ac_peminjam_pihak_1"        => "001.75.000375.04",
                "barang_titipan"                => isset($data_pengajuan->detail) ? strtoupper(json_decode($data_pengajuan->detail)->jaminan) : strtoupper(explode(".", $dataForm)[3])
            ),
            "data_template_row"                 => $data_template_row,  
            "data_template_row_title"           => "barang_titipan_desc_title",
            "template_path"                     => public_path('template/perjanjian_pembiayaan.docx')
        );

        $export = $this->exportRepository->exportWord("perjanjian_pembiayaan", $export_data);
        return $export_data;
    }

    /** 
     * Insert penyimpanan jaminan
     * @return Response
    */
    public function insertPenyimpananJaminan($data)
    {
        $penyimpanan = new PenyimpananJaminan();
        $penyimpanan->id_jaminan = $data['id_jaminan'];
        $penyimpanan->id_user = $data['id_user'];
        $penyimpanan->id_pengajuan = $data['id_pengajuan'];
        $penyimpanan->transaksi = json_encode($data['transaksi']);

        if($penyimpanan->save()) {
            return "success";
        }
        else {
            return "error";
        }
    }

    /** 
     * Pelunasan pembiayaan anggota
     * @return Response
    */
    public function pelunasanPembiayaan($data)
    {
        $pembiayaan = Pembiayaan::where('id_pembiayaan', explode(" ", $data->idRek)[6])->first();
        $user_pembiayaan = User::where('id', $pembiayaan->id_user)->first();

        $id_rekening_pembiayaan = $pembiayaan->id_rekening;
        $nama_rekening_pembiayaan = $pembiayaan->jenis_pembiayaan;
        
        $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_pinjaman;
        $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin;
        $jumlah_bayar_angsuran = explode(" ", $data->idRek)[0];
        $jumlah_bayar_margin = explode(" ", $data->idRek)[1];

        if($id_rekening_pembiayaan == 100)
        {
            $id_rekening_pendapatan = 130;
        }
        if($id_rekening_pembiayaan == 99)
        {
            $id_rekening_pendapatan = 129;
        }
        if($id_rekening_pembiayaan == 102)
        {
            $id_rekening_pendapatan = 131;
        }

        if($data->debit == 2)
        {
            $id_tabungan = $data->tabungan;
            $tabungan = Tabungan::where('id', $id_tabungan)->first();
            $rekening_tabungan = Rekening::where('id', $tabungan->id_rekening)->first();
            $id_tujuan_pelunasan = $tabungan->id_rekening;
        }
        if($data->debit == 1)
        {
            $id_tujuan_pelunasan = json_decode($pengajuan->detail)->bank;
        }
        if($data->debit == 0)
        {
            $id_tujuan_pelunasan = json_decode(Auth::user()->detail)->id_rekening;
        }
        
        $bmt_rekening_pendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->first();
        $bmt_pembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->first();
        $bmt_tujuan_pelunasan = BMT::where('id_rekening', $id_tujuan_pelunasan)->first();
        $bmt_piutang_yang_ditangguhkan = BMT::where('id_rekening', 101)->first();
        $bmt_shu_berjalan = BMT::where('id_rekening', 122)->first();

        $saldo_awal_tujuan_pelunasan = $bmt_tujuan_pelunasan->saldo;
        $saldo_akhir_tujuan_pelunasan = $bmt_tujuan_pelunasan->saldo + ($jumlah_bayar_angsuran + $jumlah_bayar_margin);
        
        if($id_rekening_pembiayaan == 100)
        {
            $jumlah_sisa_angsuran = $sisa_angsuran + $sisa_margin;
            $saldo_awal_pembiayaan = $bmt_pembiayaan->saldo;
            $saldo_akhir_pembiayaan = $bmt_pembiayaan->saldo - ($sisa_angsuran + $sisa_margin);
        }
        else
        {
            $jumlah_sisa_angsuran = $sisa_angsuran;
            $saldo_awal_pembiayaan = $bmt_pembiayaan->saldo;
            $saldo_akhir_pembiayaan = $bmt_pembiayaan->saldo - $sisa_angsuran;
        }

        if($data->debit == 2)
        {
            $saldo_awal_tujuan_pelunasan = json_decode($tabungan->detail)->saldo;
            $saldo_akhir_tujuan_pelunasan = json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin);

            $detailToPenyimpananTabungan = [
                "teller"            => Auth::user()->id,
                "dari_rekening"     => $tabungan->jenis_tabungan,
                "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                "saldo_awal"        => json_decode($tabungan->detail)->saldo,
                "saldo_akhir"       => json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin)
            ];
            $dataToPenyimpananTabungan = [
                "id_user"           => $tabungan->id_user,
                "id_tabungan"       => $tabungan->id,
                "status"            => "Pelunasan " . $pembiayaan->jenis_pembiayaan,
                "transaksi"         => $detailToPenyimpananTabungan,
                "teller"            => Auth::user()->id
            ];

            $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
        }

        $detailToPenyimpananPembiayaan = [
            "teller"            => Auth::user()->id,
            "dari_rekening"     => $bmt_pembiayaan->nama,
            "untuk_rekening"    => $bmt_tujuan_pelunasan->nama,
            "angsuran_pokok"    => json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan,
            "angsuran_ke"       => $pembiayaan->angsuran_ke + 1,
            "nisbah"            => json_decode($pembiayaan->detail)->nisbah,
            "margin"            => json_decode($pembiayaan->detail)->margin,
            "jumlah"            => json_decode($pembiayaan->detail)->pinjaman,
            "tagihan"           => json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan + json_decode($pembiayaan->detail)->jumlah_margin_bulanan,
            "sisa_angsuran"     => $sisa_angsuran - $jumlah_bayar_angsuran,
            "sisa_margin"       => $sisa_margin - $jumlah_bayar_margin,
            "sisa_pinjaman"     => $id_rekening_pembiayaan == 100 ? json_decode($pembiayaan->detail)->sisa_pinjaman - ($jumlah_bayar_angsuran + $jumlah_bayar_margin) : json_decode($pembiayaan->detail)->sisa_pinjaman - $jumlah_bayar_angsuran,
            "bayar_angsuran"    => $jumlah_bayar_angsuran,
            "bayar_margin"      => $jumlah_bayar_margin,
            "jumlah_bayar"      => $jumlah_bayar_angsuran + $jumlah_bayar_margin
        ];
        $dataToPenyimpananPembiayaan = [
            "id_user"       => $pembiayaan->id_user,
            "id_pembiayaan" => $pembiayaan->id,
            "status"        => "Pelunasan Pembiayaan",
            "transaksi"     => $detailToPenyimpananPembiayaan,
            "teller"        => Auth::user()->id
        ];

        $this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan);

        $detailToPenyimpananBMT = [
            "jumlah"            => $data->debit == 1 ? -$sisa_angsuran-$jumlah_bayar_margin : $sisa_angsuran + $jumlah_bayar_margin,
            "saldo_awal"        => $saldo_awal_tujuan_pelunasan,
            "saldo_akhir"       => $saldo_akhir_tujuan_pelunasan,
            "id_pengajuan"      => null
        ];
        $dataToPenyimpananBMT = [
            "id_user"           => $user_pembiayaan->id,
            "id_bmt"            => $bmt_tujuan_pelunasan->id,
            "status"            => "Pelunasan " . $bmt_pembiayaan->nama,
            "transaksi"         => $detailToPenyimpananBMT,
            "teller"            => Auth::user()->id
        ];

        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
            
        if($id_rekening_pembiayaan == 100)
        {
            $detailToPenyimpananBMT['jumlah'] = $sisa_margin;
            $detailToPenyimpananBMT['saldo_awal'] = $bmt_piutang_yang_ditangguhkan->saldo;
            $detailToPenyimpananBMT['saldo_akhir'] = $bmt_piutang_yang_ditangguhkan->saldo + $sisa_margin;
            $dataToPenyimpananBMT['id_bmt'] = $bmt_piutang_yang_ditangguhkan->id;
            $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
        }

        $detailToPenyimpananBMT['jumlah'] = -$jumlah_sisa_angsuran;
        $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pembiayaan;
        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pembiayaan;
        $dataToPenyimpananBMT['id_bmt'] = $bmt_pembiayaan->id;
        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
        $detailToPenyimpananBMT['saldo_awal'] = $bmt_shu_berjalan->saldo;
        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
        $dataToPenyimpananBMT['id_bmt'] = $bmt_shu_berjalan->id;
        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
        $detailToPenyimpananBMT['saldo_awal'] = $bmt_rekening_pendapatan->saldo;
        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin;
        $dataToPenyimpananBMT['id_bmt'] = $bmt_rekening_pendapatan->id;
        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

        if($data->debit == 2)
        {
            $dataToUpdateTabungan = [
                "saldo" => $saldo_akhir_tujuan_pelunasan,
                "id_pengajuan"  => null
            ];

            $tabungan->detail = json_encode($dataToUpdateTabungan);
            $tabungan->save();
        }

        $pembiayaan->detail = json_encode([
            "pinjaman" => json_decode($pembiayaan->detail)->pinjaman,
            "margin" => json_decode($pembiayaan->detail)->margin,
            "nisbah" => json_decode($pembiayaan->detail)->nisbah,
            "total_pinjaman" => json_decode($pembiayaan->detail)->total_pinjaman,
            "sisa_angsuran" => $sisa_angsuran - $jumlah_bayar_angsuran,
            "sisa_margin" => $sisa_margin - $jumlah_bayar_margin,
            "sisa_pinjaman" => $id_rekening_pembiayaan == 100 ? json_decode($pembiayaan->detail)->sisa_pinjaman - ($jumlah_bayar_angsuran + $jumlah_bayar_margin) : json_decode($pembiayaan->detail)->sisa_pinjaman - $jumlah_bayar_angsuran,
            "angsuran_pokok" => json_decode($pembiayaan->detail)->angsuran_pokok,
            "lama_angsuran" => json_decode($pembiayaan->detail)->lama_angsuran,
            "angsuran_ke" => json_decode($pembiayaan->detail)->angsuran_ke + 1,
            "tagihan_bulanan" => json_decode($pembiayaan->detail)->tagihan_bulanan,
            "jumlah_angsuran_bulanan" => json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan,
            "jumlah_margin_bulanan" => json_decode($pembiayaan->detail)->jumlah_margin_bulanan,
            "sisa_ang_bln" => 0,
            "sisa_mar_bln" => 0,
            "kelebihan_angsuran_bulanan" => 0,
            "kelebihan_margin_bulanan" => 0,
            "id_pengajuan" => null,
        ]);
        $pembiayaan->tempo = Carbon::parse($pembiayaan->tempo)->addMonth(1);
        $pembiayaan->angsuran_ke = $pembiayaan->angsuran_ke + 1;
        $pembiayaan->status = "lunas";

        $bmt_tujuan_pelunasan->saldo = $saldo_akhir_tujuan_pelunasan;
        $bmt_pembiayaan->saldo = $saldo_akhir_pembiayaan;
        $bmt_rekening_pendapatan->saldo = $bmt_rekening_pendapatan->saldo + $jumlah_bayar_margin;
        $bmt_shu_berjalan->saldo = $bmt_shu_berjalan->saldo + $jumlah_bayar_margin;
        if($id_rekening_pembiayaan == 100)
        {
            $bmt_piutang_yang_ditangguhkan->saldo = $bmt_piutang_yang_ditangguhkan->saldo + $sisa_margin;
            $bmt_piutang_yang_ditangguhkan->save();
        }
        
        if($data->debit == 2)
        {
            if(json_decode($tabungan->detail)->saldo < json_decode($rekening_tabungan->detail)->saldo_min)
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Tabungan Anda Melampaui Batas Transaksi.");
            }
            else
            {
                if(
                    $bmt_tujuan_pelunasan->save() && $bmt_pembiayaan->save() && $bmt_rekening_pendapatan->save() &&
                    $bmt_shu_berjalan->save() && $pembiayaan->save()
                )
                {   
                    // Update margin yang sudah dibayarkan user
                    if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                    {
                        $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                    }
                    else
                    {
                        $total_margin_anggota = floatval($jumlah_bayar_margin);
                    }
                    $user_pembiayaan->wajib_pokok = json_encode([
                        "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                        "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                        "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                        "margin" => $total_margin_anggota
                    ]);
                    $user_pembiayaan->save();
        
                    DB::commit();
                    $response = array("type" => "success", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Terjadi kesalahan.");
                }
            }
        }
        else
        {
            if(
                $bmt_tujuan_pelunasan->save() && $bmt_pembiayaan->save() && $bmt_rekening_pendapatan->save() &&
                $bmt_shu_berjalan->save() && $pembiayaan->save()
            )
            {   
                // Update margin yang sudah dibayarkan user
                if(isset(json_decode($user_pembiayaan->wajib_pokok)->margin))
                {
                    $total_margin_anggota = floatval(json_decode($user_pembiayaan->wajib_pokok)->margin + $jumlah_bayar_margin);
                }
                else
                {
                    $total_margin_anggota = floatval($jumlah_bayar_margin);
                }
                $user_pembiayaan->wajib_pokok = json_encode([
                    "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                    "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                    "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                    "margin" => $total_margin_anggota
                ]);
                $user_pembiayaan->save();

                DB::commit();
                $response = array("type" => "success", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Berhasil Dikonfirmasi");
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Pelunasan " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi. Terjadi kesalahan.");
            }  
        }

        return $response;
    }

}
