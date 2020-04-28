<?php

namespace App\Repositories;

use App\Http\Controllers\HomeController;
use App\Pembiayaan;
use App\PenyimpananPembiayaan;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\TabunganReporsitories;
use Illuminate\Support\Facades\DB;
use App\BMT;
use Carbon\Carbon;
use App\Pengajuan;
use App\Tabungan;

class PembiayaanReporsitory {

    public function __construct(PengajuanReporsitories $pengajuanReporsitory,
                                RekeningReporsitories $rekeningReporsitory,
                                TabunganReporsitories $tabunganReporsitory
    )
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
    }

    /** 
     * Ambil data pembiayaan
     * @return Array
    */
    public function getPembiayaan()
    {
        $pembiayaan = Pembiayaan::All();

        return $pembiayaan;
    }

    /** 
     * Ambil data pembiayaan specific user
     * @return Array
    */
    public function getPembiayaanSpecificUser()
    {
        $pembiayaan = Pembiayaan::where("id_user", Auth::user()->id)->get();

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
    public function confirmPembiayaanMDA($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_);

            $jenis_pembiayaan = "PEMBIAYAAN MDA";
            $id_rekening_pembiayaan = 99;

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

            if($bmt_pengirim->saldo > $pinjaman)
            {
                if( $this->pengajuanReporsitory->createPengajuan($dataToPengajuan)["type"] == "success" &&
                    $this->insertPembiayaan($dataToPembiayaan) == "success" &&
                    $this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
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
    public function openPembiayaanMDA($data)
    {
        DB::beginTransaction();

        try
        {
            $user_pembiayaan = User::where('no_ktp', $data->nama_nasabah)->first();

            $id_pengajuan = DB::select("SHOW TABLE STATUS LIKE 'pengajuan'");
            $nextIdPengajuan = $id_pengajuan[0]->Auto_increment;

            $jenis_pembiayaan = "PEMBIAYAAN MDA";
            $id_rekening_pembiayaan = 99;

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

            if($bmt_pengirim->saldo > $pinjaman)
            {
                if( $this->pengajuanReporsitory->createPengajuan($dataToPengajuan)["type"] == "success" &&
                    $this->insertPembiayaan($dataToPembiayaan) == "success" &&
                    $this->insertPenyimpananPembiayaan($dataToPenyimpananPembiayaan) == "success" &&
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
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
                $bmt_aktiva = BMT::where('id_rekening', 1)->first();

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

                            $dataToPenyimpananBMT['id_bmt'] = $bmt_aktiva->id;
                            $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                            $detailToPenyimpananBMT['saldo_awal'] = $bmt_aktiva->saldo;
                            $detailToPenyimpananBMT['saldo_akhir'] = $bmt_aktiva->saldo + $jumlah_bayar_margin;
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

                            if($sisa_pinjaman <= 50)
                            {
                                $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
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

                        $dataToPenyimpananBMT['id_bmt'] = $bmt_aktiva->id;
                        $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_margin;
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_aktiva->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_aktiva->saldo + $jumlah_bayar_margin;
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

                        if($sisa_pinjaman <= 50)
                        {
                            $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
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
    public function confirmAngsuranMDA($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_);
            $pembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->first();
            $penyimpananPembiayaan = PenyimpananPembiayaan::where('id_pembiayaan', $pembiayaan->id)->orderBy('created_at', 'desc')->take(1)->get();
            
            if($pembiayaan->status == "active")
            {
                $jenis_pembiayaan = "PEMBIAYAAN MDA";
                $id_rekening_pembiayaan = 99;
                
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

                    $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                    $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                    $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);

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

                    if($sisa_pinjaman <= 50)
                    {
                        $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->idtab)->update([
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
    public function angsuranMDA($data)
    {
        DB::beginTransaction();
        try
        {
            $pembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->first();
            $penyimpananPembiayaan = PenyimpananPembiayaan::where('id_pembiayaan', $pembiayaan->id)->orderBy('created_at', 'desc')->take(1)->get();
            
            $jenis_pembiayaan = "PEMBIAYAAN MDA";
            $id_rekening_pembiayaan = 99;
            
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
            
            $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
            $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) + (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));
            
            $saldo_awal_pembiayaan = floatval($bmt_pembiayaan->saldo);
            $saldo_akhir_pembiayaan = floatval($bmt_pembiayaan->saldo) - floatval($jumlah_bayar_angsuran);

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

                $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);

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

                if($sisa_pinjaman <= 50)
                {
                    $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
                        'status'    => 'not active'
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
                $bmt_tabungan = BMT::where('id_rekening', $tabungan->id_rekening)->first();
                $bank_tujuan_angsuran = $tabungan->id_rekening;
                $dari_bank = "[" . $tabungan->id_tabungan . "] " . $tabungan->jenis_tabungan;
            }

            $tagihan_pokok_angsuran = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
            $jumlah_bayar_angsuran = $data->bayar_ang;
            $tagihan_pokok_margin = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;
            $jumlah_bayar_margin = $data->bayar_mar;
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

                        $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                        $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                        $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                        $updatePiutangMRB = BMT::where('id_rekening', 101)->update([ "saldo" => $bmt_piutang_mrb->saldo + $jumlah_bayar_margin ]);

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

                        if($sisa_pinjaman <= 50)
                        {
                            $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
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

                    $updateBMTPengirim = BMT::where('id_rekening',  $bank_tujuan_angsuran)->update([ 'saldo' => $saldo_akhir_pengirim ]);
                    $updateBMTPembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->update([ "saldo" => $saldo_akhir_pembiayaan ]);
                    $updateSHUBerjalan = BMT::where('id_rekening', 122)->update([ "saldo" => $bmt_shu_berjalan->saldo + $jumlah_bayar_margin ]);
                    $updatePiutangMRB = BMT::where('id_rekening', 101)->update([ "saldo" => $bmt_piutang_mrb->saldo + $jumlah_bayar_margin ]);

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

                    if($sisa_pinjaman <= 50)
                    {
                        $updateStatusPembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->update([
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

}
