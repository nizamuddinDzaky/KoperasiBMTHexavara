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
use App\Repositories\InformationRepository;
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
                                ExportRepositories $exportRepository,
    InformationRepository $informationRepository
    )
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->helperRepository = $helperRepository;
        $this->exportRepository = $exportRepository;
        $this->informationRepository = $informationRepository;
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
                    "id_pengajuan"      => $pengajuan->id,
                    "jenis_tempo"       => $jenis_tempo,
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
                    
                    $this->exportPerjanjianMRB($dataToPembiayaan, $data);

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
            $jenis = Rekening::where('id', $pengajuan->id_rekening)->select('nama_rekening')->first();

            $jenis_pembiayaan = $jenis->nama_rekening;
            $id_rekening_pembiayaan = $pengajuan->id_rekening;


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
            //nisbah tanpa pembagian
            $nisbah_koperasi = $data->nisbah;
            $nisbah_anggota = 100.0 - $data->nisbah;
            $margin = $pinjaman * $nisbah * $tempo;
            $kegiatan_usaha = json_decode($pengajuan->detail)->usaha;

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
                    "id_pengajuan"      => $pengajuan->id,
                    "jenis_tempo"       => $jenis_tempo,
                    "kegiatan_usaha"    => $kegiatan_usaha,
                    "nisbah_anggota"   => $nisbah_anggota,
                    "nisbah_koperasi"   => $nisbah_koperasi,
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
                "id_pengajuan"      => $nextIdPengajuan,
                "jenis_tempo"       => $data->ketWaktu,
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
                    
                    $this->exportPerjanjianMRB($dataToPembiayaan, $data, $dataToPenyimpananJaminan);

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
            $jenis = Rekening::where('id', $data->pembiayaan)->select('nama_rekening')->first();

            $jenis_pembiayaan = $jenis->nama_rekening;
            $id_rekening_pembiayaan = $data->pembiayaan;


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
            //nisbah tanpa pembagian
            $nisbah_koperasi = $data->nisbah;
            $nisbah_anggota = 100.0 - $data->nisbah;
            $margin = $pinjaman * $nisbah * $tempo;
            $kegiatan_usaha = $data->usaha;

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
                "id_pengajuan"      => $nextIdPengajuan,
                "jenis_tempo"       => $data->ketWaktu,
                "kegiatan_usaha"    => $kegiatan_usaha,
                "nisbah_anggota"   => $nisbah_anggota,
                "nisbah_koperasi"   => $nisbah_koperasi,
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

                if (preg_replace('/[^\d.]/', '', $data->bayar_ang) > json_decode($pembiayaan->detail)->sisa_pinjaman ){
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi Karena Pembayaran Lebih Besar Dari Sisa Pinjaman");
                    return $response;
                }



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

                $tagihan = $this->checkTagihanMRB($pembiayaan->id);
                $tagihan_margin = $tagihan[0];
                $tagihan_angsuran = $tagihan[1];


                $margin_kurang = 0.0; // untuk cek tagihan margin
                $angsuran_kurang = 0.0; // untuk cek tagihan angsuran

                $pembayaranSetelahDikurangiSisa = 0.0;
                if ($tagihan_margin > 0)
                {
                    if ($tagihan_margin > $jumlah_bayar_angsuran){
                        $pembayaranSetelahDikurangiSisa = $jumlah_bayar_angsuran - $jumlah_bayar_angsuran;
                        $margin_kurang = $jumlah_bayar_angsuran;
                    }else{
                        $pembayaranSetelahDikurangiSisa = $jumlah_bayar_angsuran - $tagihan_margin;
                    }

                }else{
                    $pembayaranSetelahDikurangiSisa = $jumlah_bayar_angsuran;
                }


                if ($tagihan_angsuran > 0)
                {
                    if ($tagihan_angsuran > $pembayaranSetelahDikurangiSisa){
                        $angsuran_kurang = $pembayaranSetelahDikurangiSisa;
                        $pembayaranSetelahDikurangiSisa = $pembayaranSetelahDikurangiSisa - $pembayaranSetelahDikurangiSisa;

                    }else{
                        $angsuran_kurang = $tagihan_angsuran;
                        $pembayaranSetelahDikurangiSisa = $pembayaranSetelahDikurangiSisa - $tagihan_angsuran;
                    }

                }




                $pemecahan = $this->pemecahanPembayaranMRB($pembiayaan->id, $pembayaranSetelahDikurangiSisa);

                if ($tagihan_margin > 0 && $tagihan_margin < $jumlah_bayar_angsuran)
                {
                    $jumlah_bayar_margin = $pemecahan[1] + $tagihan_margin;
                }elseif($tagihan_margin > 0 && $tagihan_margin > $jumlah_bayar_angsuran){
                    $jumlah_bayar_margin = $pemecahan[1] + $margin_kurang;
                }
                else {
                    $jumlah_bayar_margin = $pemecahan[1];
                }

                if ($tagihan_angsuran > 0 && $tagihan_angsuran < $pembayaranSetelahDikurangiSisa )
                {
                    $jumlah_bayar_angsuran = $pemecahan[0] + $tagihan_angsuran;
                }
                elseif($tagihan_angsuran > 0 && $tagihan_angsuran > $pembayaranSetelahDikurangiSisa ){
                    $jumlah_bayar_angsuran = $pemecahan[0] + $angsuran_kurang;
                }else{
                    $jumlah_bayar_angsuran = $pemecahan[0];
                }
                
                $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran;
                $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin;
                $total_pinjaman = json_decode($pembiayaan->detail)->pinjaman;
                $total_margin = json_decode($pembiayaan->detail)->margin;
                
                $tagihan = $tagihan_pokok_angsuran + $tagihan_pokok_margin;


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
                    "sisa_ang_bln"      => 0,
                    "sisa_mar_bln"      => 0,
                    "kelebihan_angsuran_bulanan" => 0,
                    "kelebihan_margin_bulanan" =>  0,
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

                            if($this->cekTambahTempoMRB($pembiayaan->id) == true)
                            {
                                $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1);
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

                        if($this->cekTambahTempoMRB($pembiayaan->id) == true)
                        {
                            $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1);
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
                $id_rekening_pembiayaan = $pembiayaan->id_rekening;
                $rekening_pendapatan = Rekening::where('id', $id_rekening_pembiayaan)->select('detail')->first();
                $id_rekening_pendapatan = Rekening::where('id_rekening', json_decode($rekening_pendapatan->detail)->rek_margin)->select('id')->first();
                $id_rekening_pendapatan = $id_rekening_pendapatan->id;
                
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
                    $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) - (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));
                    $saldo_awal_tabungan = floatval(json_decode($tabungan->detail)->saldo);
                    $saldo_akhir_tabungan = floatval(json_decode($tabungan->detail)->saldo) - floatval($jumlah_bayar_angsuran + $jumlah_bayar_margin);

                    $detailToPenyimpananTabungan = [
                        "teller"            => Auth::user()->id,
                        "dari_rekening"     => $tabungan->jenis_tabungan,
                        "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                        "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                        "saldo_awal"        => $saldo_awal_tabungan,
                        "saldo_akhir"       => $saldo_akhir_tabungan
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => $tabungan->id_user,
                        "id_tabungan"   => $tabungan->id,
                        "status"        => "Angsuran ".$pembiayaan->jenis_pembiayaan,
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

            $id_rekening_pembiayaan = $pembiayaan->id_rekening;
            $rekening_pendapatan = Rekening::where('id', $id_rekening_pembiayaan)->select('detail')->first();
            $id_rekening_pendapatan = Rekening::where('id_rekening', json_decode($rekening_pendapatan->detail)->rek_margin)->select('id')->first();
            $id_rekening_pendapatan = $id_rekening_pendapatan->id;
            
            if($data->debit == 0) // tunai
            {
                $bank_tujuan_angsuran = json_decode(Auth::user()->detail)->id_rekening;
                $dari_bank = "Tunai";
            }
            if($data->debit == 1) // bank
            {
                $bank_tujuan_angsuran = $data->bank;
                $dari_bank = "[" . $data->nobank . "] " . $data->daribank;
            }
            if($data->debit == 2) // tabungan
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
            $bmt_rekening_pendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->first();
            
            $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
            $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) + (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));
            
            if($data->debit == 2) // tabungan
            {
                $saldo_awal_pengirim = floatval($bmt_tujuan_angsuran->saldo);
                $saldo_akhir_pengirim = floatval($bmt_tujuan_angsuran->saldo) - (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));
                $saldo_awal_tabungan = floatval(json_decode($tabungan->detail)->saldo);
                $saldo_akhir_tabungan = floatval(json_decode($tabungan->detail)->saldo) - (floatval($jumlah_bayar_angsuran) + floatval($jumlah_bayar_margin));

                $detailToPenyimpananTabungan = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => $tabungan->jenis_tabungan,
                    "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                    "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                    "saldo_awal"        => $saldo_awal_tabungan,
                    "saldo_akhir"       => $saldo_akhir_tabungan
                ];
                $dataToPenyimpananTabungan = [
                    "id_user"       => $tabungan->id_user,
                    "id_tabungan"   => $tabungan->id,
                    "status"        => "Angsuran ".$pembiayaan->jenis_pembiayaan,
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


    public function cariSisaAngsuranMRB($id){
        $pembiayaan = Pembiayaan::where('id', $id)->select('detail', 'angsuran_ke', 'tempo','created_at')->first();
        $tagihan_pokok = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
        $penyimpanan_pembiayaan= PenyimpananPembiayaan::where('id_pembiayaan', $id)->where('status', 'Angsuran Pembiayaan [Pokok]')->get();
        $total_bayar_angsuran = 0.0;


        if ($penyimpanan_pembiayaan == null) {
            return false;
        }else{
            foreach($penyimpanan_pembiayaan as $data){
                $total_bayar_angsuran += json_decode($data->transaksi)->bayar_angsuran;
            }
        }

        $tanggalSekarang = Carbon::now()->startOfDay()->addDay(1);
        $tempoAwal = $pembiayaan->created_at;
        $tempoAwal = Carbon::createFromFormat('Y-m-d H:i:s', $tempoAwal);
        $tanggalSekarang = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalSekarang);

        $d1=new DateTime($tanggalSekarang);
        $d2=new DateTime($tempoAwal);
        $Months = $d2->diff($d1);
        $diff_in_months = (($Months->y) * 12) + ($Months->m);

        $sisa_pokok = ($diff_in_months  * $tagihan_pokok) - $total_bayar_angsuran;

        return $sisa_pokok;



    }

    public function cariSisaMarginMRB($id){
        $pembiayaan = Pembiayaan::where('id', $id)->select('detail', 'angsuran_ke', 'created_at')->first();
        $margin_pokok = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;
        $penyimpanan_pembiayaan= PenyimpananPembiayaan::where('id_pembiayaan', $id)->where('status', 'Angsuran Pembiayaan [Pokok]')->get();
        $total_bayar_margin = 0.0;
//        dd($penyimpanan_pembiayaan);

        if ($penyimpanan_pembiayaan == null) {
            return false;
        }else{
            foreach($penyimpanan_pembiayaan as $data){
                $total_bayar_margin += json_decode($data->transaksi)->bayar_margin;
            }
        }

        $tanggalSekarang = Carbon::now()->startOfDay()->addDay(1);
        $tempoAwal = $pembiayaan->created_at;
        $tempoAwal = Carbon::createFromFormat('Y-m-d H:i:s', $tempoAwal);
        $tanggalSekarang = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalSekarang);

        $d1=new DateTime($tanggalSekarang);
        $d2=new DateTime($tempoAwal);
        $Months = $d2->diff($d1);
        $diff_in_months = (($Months->y) * 12) + ($Months->m);


        $sisa_margin = ($diff_in_months * $margin_pokok) - $total_bayar_margin;

        return $sisa_margin;

    }


    public function pemecahanPembayaranMRB($id, $pembayaranSetelahDikurangiSisa){
        $pembiayaan = Pembiayaan::where('id', $id)->select('detail')->first();
        $tagihan_pokok_angsuran = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
        $tagihan_pokok_margin = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;
        $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin;
        $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran;
        $penampungAngsuran = 0.0;
        $penampungMargin = 0.0;
        while($pembayaranSetelahDikurangiSisa > 0.0)
        {
            if ($pembayaranSetelahDikurangiSisa - $tagihan_pokok_margin > 0.0 && $sisa_margin != 0.0  )
            {
                $pembayaranSetelahDikurangiSisa = $pembayaranSetelahDikurangiSisa - $tagihan_pokok_margin;
                $sisa_margin -= $tagihan_pokok_margin;
                $penampungMargin  += $tagihan_pokok_margin;
            }
            else if ($sisa_margin != 0.0){
                $sisa_margin -= $tagihan_pokok_margin;
                $penampungMargin += $pembayaranSetelahDikurangiSisa;
                $pembayaranSetelahDikurangiSisa = 0.0;
            }


            if ($pembayaranSetelahDikurangiSisa - $tagihan_pokok_angsuran > 0 &&  $sisa_angsuran != 0.0)
            {
                $pembayaranSetelahDikurangiSisa = $pembayaranSetelahDikurangiSisa - $tagihan_pokok_angsuran;
                $sisa_angsuran -= $tagihan_pokok_angsuran;
                $penampungAngsuran  += $tagihan_pokok_angsuran;
            }
            elseif($sisa_angsuran != 0.0){
                $penampungAngsuran += $pembayaranSetelahDikurangiSisa;
                $sisa_angsuran -= $tagihan_pokok_angsuran;
                $pembayaranSetelahDikurangiSisa = 0.0;
            }

        }

        return [$penampungAngsuran,$penampungMargin];
    }

    public function checkTagihanMRB($id){
        $angsuran = $this->cariSisaAngsuranMRB($id);
        $margin = $this->cariSisaMarginMRB($id);

        if ($angsuran < 0 )
        {
            $tagihan_pokok_angsuran = 0;
        }else{
            $tagihan_pokok_angsuran = $angsuran;
        }

        if($margin < 0 ){
            $tagihan_pokok_margin = 0;
        }else{
            $tagihan_pokok_margin = $margin;
        }

        return [$tagihan_pokok_margin, $tagihan_pokok_angsuran];
    }

    public function checkTagihanLain($id){
        $angsuran = $this->cariSisaAngsuranMRB($id);

        if ($angsuran < 0 )
        {
            $tagihan_pokok_angsuran = 0;
        }else{
            $tagihan_pokok_angsuran = $angsuran;
        }

        return $tagihan_pokok_angsuran;
    }


    public function cekTambahTempoMRB($id){
            $sisamargin = $this->cariSisaMarginMRB($id);
            $sisaangsuran = $this->cariSisaAngsuranMRB($id);
            $pembiayaan = Pembiayaan::where('id', $id)->select( 'tempo')->first();
            $tanggalSekarang = Carbon::now()->startOfDay();
            $tempoAwal = Carbon::createFromFormat('Y-m-d', $pembiayaan->tempo)->startOfDay();

            if ($sisaangsuran <= 0 && $sisamargin <= 0 && $tanggalSekarang->greaterThanOrEqualTo($tempoAwal)){
                return true;
            }else{
                return false;
            }
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
            // cari pembiayaan, cari history pembiayaan terakhir, cari user
            $pembiayaan = Pembiayaan::where('id_pembiayaan', $data->id_)->first();
            $penyimpananPembiayaan = PenyimpananPembiayaan::where('id_pembiayaan', $pembiayaan->id)->orderBy('created_at', 'desc')->take(1)->get();
            $user_pembiayaan = User::where('id', $pembiayaan->id_user)->first();



            if (preg_replace('/[^\d.]/', '', $data->bayar_ang) > json_decode($pembiayaan->detail)->sisa_pinjaman ){
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Angsuran " . $pembiayaan->jenis_pembiayaan . " Gagal Dikonfirmasi Karena Pembayaran Lebih Besar Dari Sisa Pinjaman");
                return $response;
            }


            $jenis_pembiayaan = "PEMBIAYAAN MRB";
            $id_rekening_pembiayaan = 100;
        
            if($data->debit == 0)
            {
                $bank_tujuan_angsuran = json_decode(Auth::user()->detail)->id_rekening; //teller
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
                $bmt_tabungan = BMT::where('id_rekening', $tabungan->id_rekening)->first(); // bmt tabungan
                $bank_tujuan_angsuran = $tabungan->id_rekening;
                $dari_bank = "[" . $tabungan->id_tabungan . "] " . $tabungan->jenis_tabungan;
            }

            $tagihan_pokok_angsuran = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
            $jumlah_bayar_angsuran = floatval(preg_replace('/[^\d.]/', '', $data->bayar_ang));
            $tagihan_pokok_margin = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;


            $tagihan = $this->checkTagihanMRB($pembiayaan->id);
            $tagihan_margin = $tagihan[0];
            $tagihan_angsuran = $tagihan[1];



            $margin_kurang = 0.0; // untuk cek tagihan margin
            $angsuran_kurang = 0.0; // untuk cek tagihan angsuran

            $pembayaranSetelahDikurangiSisa = 0.0;
            if ($tagihan_margin > 0)
            {
                if ($tagihan_margin > $jumlah_bayar_angsuran){
                    $pembayaranSetelahDikurangiSisa = $jumlah_bayar_angsuran - $jumlah_bayar_angsuran;
                    $margin_kurang = $jumlah_bayar_angsuran;
                }else{
                    $pembayaranSetelahDikurangiSisa = $jumlah_bayar_angsuran - $tagihan_margin;
                }

            }else{
                $pembayaranSetelahDikurangiSisa = $jumlah_bayar_angsuran;
            }


            if ($tagihan_angsuran > 0)
            {
                if ($tagihan_angsuran > $pembayaranSetelahDikurangiSisa){
                    $angsuran_kurang = $pembayaranSetelahDikurangiSisa;
                    $pembayaranSetelahDikurangiSisa = $pembayaranSetelahDikurangiSisa - $pembayaranSetelahDikurangiSisa;

                }else{
                    $angsuran_kurang = $tagihan_angsuran;
                    $pembayaranSetelahDikurangiSisa = $pembayaranSetelahDikurangiSisa - $tagihan_angsuran;
                }

            }




            $pemecahan = $this->pemecahanPembayaranMRB($pembiayaan->id, $pembayaranSetelahDikurangiSisa);

            if ($tagihan_margin > 0 && $tagihan_margin < $jumlah_bayar_angsuran)
            {
                $jumlah_bayar_margin = $pemecahan[1] + $tagihan_margin;
            }elseif($tagihan_margin > 0 && $tagihan_margin > $jumlah_bayar_angsuran){
                $jumlah_bayar_margin = $pemecahan[1] + $margin_kurang;
            }
            else {
                $jumlah_bayar_margin = $pemecahan[1];
            }

            if ($tagihan_angsuran > 0 && $tagihan_angsuran < $pembayaranSetelahDikurangiSisa )
            {
                $jumlah_bayar_angsuran = $pemecahan[0] + $tagihan_angsuran;
            }
            elseif($tagihan_angsuran > 0 && $tagihan_angsuran > $pembayaranSetelahDikurangiSisa ){
                $jumlah_bayar_angsuran = $pemecahan[0] + $angsuran_kurang;
            }else{
                $jumlah_bayar_angsuran = $pemecahan[0];
            }




            $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran;
            $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin;
            $total_pinjaman = json_decode($pembiayaan->detail)->pinjaman;
            $total_margin = json_decode($pembiayaan->detail)->margin;
//            $sisa_margin_bulanan = $kekurangan_pembayaran_margin;
//            $sisa_angsuran_bulanan = $kekurangan_pembayaran_angsuran;
            
            $tagihan = $tagihan_pokok_angsuran + $tagihan_pokok_margin;


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
                "sisa_ang_bln"      => 0,
                "sisa_mar_bln"      => 0,
                "kelebihan_angsuran_bulanan" => 0,
                "kelebihan_margin_bulanan" =>  0,
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

                        if($this->cekTambahTempoMRB($pembiayaan->id) == true)
                        {
                            $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1);
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

                         if($this->cekTambahTempoMRB($pembiayaan->id) == true)
                        {
                            $tempo_pembayaran = Carbon::parse($pembiayaan->tempo)->addMonth(1);
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

            $rekening_pendapatan = Rekening::where('id', $id_rekening_pembiayaan)->select('detail')->first();
            $id_rekening_pendapatan = Rekening::where('id_rekening', json_decode($rekening_pendapatan->detail)->rek_margin)->select('id')->first();
            $id_rekening_pendapatan = $id_rekening_pendapatan->id;

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
                $saldo_akhir_tujuan_pelunasan_tabungan = json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin);

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
                        "saldo" => $saldo_akhir_tujuan_pelunasan_tabungan,
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

        $bulanSekarang = Carbon::now('m')->month;
        $bulanRomawi = $this->numberToRoman($bulanSekarang);
        $tahunSekarang = Carbon::now('m')->year;
        $no_pembiayaan = "";
        $detailRekening = Rekening::where('id', $dataPembiayaan['id_rekening'])->select('detail','nama_rekening')->first();
        $fileName = json_decode($detailRekening->detail)->path_akad;
        $path_name = 'template'.$fileName;
        $namaRekening = explode(" ",$detailRekening->nama_rekening);

        $path = public_path($path_name);
        $no_pembiayaan = $dataPembiayaan['id']."/MUDA/".$namaRekening[1]."/".$bulanRomawi."/".$tahunSekarang;


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

        $beforeExplodePokok = explode(".",$dataPembiayaan['detail']['pinjaman']);
        $totalPokokHuruf = $this->uangToKalimat($beforeExplodePokok[0]);



        $export_data = array(
            "user"                              => preg_replace('/[^A-Za-z0-9_\.-]/', ' ', $user_pembiayaan->nama),
            "id"                                => $dataPembiayaan['id'],
            "data_template"                     => array(
                "id_pembiayaan"                 => $dataPembiayaan['id'],
                "bulan_romawi"                  => $bulanRomawi,
                "tahun"                         => $tahunSekarang,
                "total_pokok"                   => number_format($dataPembiayaan['detail']['pinjaman'],0,",","."),
                "jangka_waktu_angsuran"         => $dataPembiayaan['detail']['jenis_tempo'],
                "margin_bulanan"                => $dataPembiayaan['detail']['jumlah_margin_bulanan'],
                "saksi_1"                       => preg_replace('/[^A-Za-z0-9_\.-]/', ' ', $dataForm->saksi1),
                "saksi_2"                       => preg_replace('/[^A-Za-z0-9_\.-]/', ' ', $dataForm->saksi2),
                "kegiatan_usaha"                => $dataPembiayaan['detail']['kegiatan_usaha'],
                "nisbah_anggota"                 => $dataPembiayaan['detail']['nisbah_anggota'],
                "nisbah_koperasi"                => $dataPembiayaan['detail']['nisbah_koperasi'],
                "hari_perjanjian"               => $this->helperRepository->getDayName(),
                "tanggal_perjanjian"            => Carbon::now()->format("d") . " " . $this->helperRepository->getMonthName() . " " . Carbon::now()->format("Y"),
                "tempat_perjanjian"             => "Surabaya",
                "nama_teller"                   => Auth::user()->nama,
                "jabatan_pemberi_perjanjian"    => "MANAGER BMT-MUDA SURABAYA",
                "alamat_cabang"                 => "Jl. Kedinding  Surabaya",
                "peminjam_pihak_1"              => strtoupper($user_pembiayaan->nama),
                "alamat_peminjam_pihak_1"       => strtoupper($user_pembiayaan->alamat),
                "nik_peminjam_pihak_1"          => $user_pembiayaan->no_ktp,
                "peminjam_pihak_2"              => strtoupper($dataForm->saksi2),
                "alamat_peminjam_pihak_2"       => strtoupper($dataForm->alamat2),
                "nik_peminjam_pihak_2"          => $dataForm->ktp2,
                "jumlah_pinjaman"               => number_format($dataPembiayaan['detail']['pinjaman'],0,",","."),
                "jumlah_pinjaman_text"          => strtoupper($this->helperRepository->getMoneyInverse($dataPembiayaan['detail']['pinjaman'])) . " RUPIAH",
                "lama_angsuran"                 => $dataPembiayaan['detail']['lama_angsuran'],
                "batas_akhir_angsuran"          => Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran'])->format("d") . " " . $this->helperRepository->getMonthName( Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran']) ) . " " . Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran'])->format("Y"),
                "angsuran_bulanan"              => number_format($dataPembiayaan['detail']['jumlah_angsuran_bulanan'],0,",","."),
                "angsuran_pertama"              => Carbon::now()->addMonth(1)->format("d") . " " . $this->helperRepository->getMonthName( Carbon::now()->addMonth(1) ) . " " . Carbon::now()->addMonth(1)->format("Y"),
                "saksi"                         => $dataForm->saksi2,
                "pekerjaan_peminjam_pihak_1"    => ucwords(json_decode($user_pembiayaan->detail)->pekerjaan),
                "pekerjaan_peminjam_pihak_2"    => "",
                "jumlah_margin"                 => $dataPembiayaan['detail']['margin'],
                "no_ac_peminjam_pihak_1"        => "001.75.000375.04",
                "barang_titipan"                => isset($data_pengajuan->detail) ? strtoupper(json_decode($data_pengajuan->detail)->jaminan) : strtoupper(explode(".", $dataForm)[3]),
                "no_pembiayaan"                 => $no_pembiayaan,
                "bulan"                         => $bulanSekarang,
                "total_pokok_huruf"             =>  ucwords($totalPokokHuruf),
            ),
            "data_template_row"                 => $data_template_row,  
            "data_template_row_title"           => "barang_titipan_desc_title",
            "template_path"                     => $path
        );

        if ($namaRekening[1] == "RAHN"){
            $export = $this->exportRepository->exportWord("perjanjian_pembiayaan", $export_data, "rahn");
        }else{
            $export = $this->exportRepository->exportWord("perjanjian_pembiayaan", $export_data);
        }

        return $export_data;
    }
    public function exportPerjanjianMRB($dataPembiayaan, $dataForm, $dataJaminan="")
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

        $bulanSekarang = Carbon::now('m')->month;
        $bulanRomawi = $this->numberToRoman($bulanSekarang);
        $tahunSekarang = Carbon::now('m')->year;

        $no_pembiayaan = $dataPembiayaan['id']."/MUDA/MRB/".$bulanRomawi."/".$tahunSekarang;

        $detailRekening = Rekening::where('id', 100)->select('detail')->first();
        $fileName = json_decode($detailRekening->detail)->path_akad;
        $path_name = 'template/'.$fileName;

        $path = public_path($path_name);

        $export_data = array(
            "user"                              => preg_replace('/[^A-Za-z0-9_\.-]/', ' ', $user_pembiayaan->nama),
            "id"                                => $dataPembiayaan['id'],
            "data_template"                     => array(
                "id_pembiayaan"                 => $dataPembiayaan['id'],
                "bulan_romawi"                  => $bulanRomawi,
                "tahun"                         => $tahunSekarang,
                "total_pokok"                   => number_format($dataPembiayaan['detail']['pinjaman'],0,",","."),
                "total_margin"                  => number_format($dataPembiayaan['detail']['margin'],0,",","."),
                "total_pokok_margin"            => number_format($dataPembiayaan['detail']['total_pinjaman'],0,",","."),
                "jangka_waktu_angsuran"         => $dataPembiayaan['detail']['jenis_tempo'],
                "margin_bulanan"                => $dataPembiayaan['detail']['jumlah_margin_bulanan'],
                "saksi_1"                       => preg_replace('/[^A-Za-z0-9_\.-]/', ' ', $dataForm->saksi1),
                "saksi_2"                       => preg_replace('/[^A-Za-z0-9_\.-]/', ' ', $dataForm->saksi2),
                "hari_perjanjian"               => $this->helperRepository->getDayName(),
                "tanggal_perjanjian"            => Carbon::now()->format("d") . " " . $this->helperRepository->getMonthName() . " " . Carbon::now()->format("Y"),
                "tempat_perjanjian"             => "Surabaya",
                "nama_teller"                   => Auth::user()->nama,
                "jabatan_pemberi_perjanjian"    => "MANAGER BMT-MUDA SURABAYA",
                "alamat_cabang"                 => "Jl. Kedinding  Surabaya",
                "peminjam_pihak_1"              => strtoupper($user_pembiayaan->nama),
                "alamat_peminjam_pihak_1"       => strtoupper($user_pembiayaan->alamat),
                "nik_peminjam_pihak_1"          => $user_pembiayaan->no_ktp,
                "peminjam_pihak_2"              => strtoupper($dataForm->saksi2),
                "alamat_peminjam_pihak_2"       => strtoupper($dataForm->alamat2),
                "nik_peminjam_pihak_2"          => $dataForm->ktp2,
                "jumlah_pinjaman"               => number_format($dataPembiayaan['detail']['pinjaman'],0,",","."),
                "jumlah_pinjaman_text"          => strtoupper($this->helperRepository->getMoneyInverse($dataPembiayaan['detail']['pinjaman'])) . " RUPIAH",
                "lama_angsuran"                 => $dataPembiayaan['detail']['lama_angsuran'],
                "batas_akhir_angsuran"          => Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran'])->format("d") . " " . $this->helperRepository->getMonthName( Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran']) ) . " " . Carbon::now()->addMonth($dataPembiayaan['detail']['lama_angsuran'])->format("Y"),
                "angsuran_bulanan"              => number_format($dataPembiayaan['detail']['jumlah_angsuran_bulanan'],0,",","."),
                "angsuran_pertama"              => Carbon::now()->addMonth(1)->format("d") . " " . $this->helperRepository->getMonthName( Carbon::now()->addMonth(1) ) . " " . Carbon::now()->addMonth(1)->format("Y"),
                "saksi"                         => $dataForm->saksi2,
                "pekerjaan_peminjam_pihak_1"    => ucwords(json_decode($user_pembiayaan->detail)->pekerjaan),
                "pekerjaan_peminjam_pihak_2"    => "",
                "jumlah_margin"                 => $dataPembiayaan['detail']['margin'],
                "no_ac_peminjam_pihak_1"        => "001.75.000375.04",
                "barang_titipan"                => isset($data_pengajuan->detail) ? strtoupper(json_decode($data_pengajuan->detail)->jaminan) : strtoupper(explode(".", $dataForm)[3]),
                "no_pembiayaan"                 => $no_pembiayaan,
                "usaha"                         => json_decode($data_pengajuan->detail)->usaha
            ),
            "data_template_row"                 => $data_template_row,
            "data_template_row_title"           => "barang_titipan_desc_title",
            "template_path"                     => $path
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

        $pembiayaan = Pembiayaan::where('id_pembiayaan', explode(" ", $data->idRek)[6])->first(); // ambil pembiayaam
        $user_pembiayaan = User::where('id', $pembiayaan->id_user)->first(); // ambil user yang melakukan pelunasan

        $id_rekening_pembiayaan = $pembiayaan->id_rekening; // ambil id rekening aktiva pembiayaan
        $nama_rekening_pembiayaan = $pembiayaan->jenis_pembiayaan; // nama pembiayaan
        
        $sisa_angsuran = json_decode($pembiayaan->detail)->sisa_angsuran; // sisa angsuran
        $sisa_margin = json_decode($pembiayaan->detail)->sisa_margin; // sisa margin

        $jumlah_bayar_angsuran = explode(" ", $data->idRek)[0]; // jumlah bayar angsuran
        $jumlah_bayar_margin = str_replace(',',"",$data->bayar_mar); // jumlah bayar margin


        $rekening_pendapatan = Rekening::where('id', $id_rekening_pembiayaan)->select('detail')->first();
        $id_rekening_pendapatan = Rekening::where('id_rekening', json_decode($rekening_pendapatan->detail)->rek_margin)->select('id')->first();
        $id_rekening_pendapatan = $id_rekening_pendapatan->id;


        //id tujuan pelunasan untuk menambah saldo
        if($data->debit == 2)
        {
            //ini tabungan yang akan dikurangi
            $id_tabungan = $data->tabungan;
            $tabungan = Tabungan::where('id', $id_tabungan)->first();
            $rekening_tabungan = Rekening::where('id', $tabungan->id_rekening)->first();
            $id_tujuan_pelunasan = $tabungan->id_rekening;
        }
        if($data->debit == 1)
        {
            $id_tujuan_pelunasan = $data->bank;
        }
        if($data->debit == 0)
        {
            $id_tujuan_pelunasan = json_decode(Auth::user()->detail)->id_rekening; // id teller
        }
        
        $bmt_rekening_pendapatan = BMT::where('id_rekening', $id_rekening_pendapatan)->first();
        $bmt_pembiayaan = BMT::where('id_rekening', $id_rekening_pembiayaan)->first();
        $bmt_tujuan_pelunasan = BMT::where('id_rekening', $id_tujuan_pelunasan)->first();
        $bmt_piutang_yang_ditangguhkan = BMT::where('id_rekening', 101)->first();
        $bmt_shu_berjalan = BMT::where('id_rekening', 122)->first();

        $saldo_awal_tujuan_pelunasan = $bmt_tujuan_pelunasan->saldo;
        if ($data->debit == 2){
            $saldo_akhir_tujuan_pelunasan = $bmt_tujuan_pelunasan->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin);
        }
        else{
            $saldo_akhir_tujuan_pelunasan = $bmt_tujuan_pelunasan->saldo + ($jumlah_bayar_angsuran + $jumlah_bayar_margin);
        }

        
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
            $saldo_awal_tujuan_pelunasan_tabungan = json_decode($tabungan->detail)->saldo;
            $saldo_akhir_tujuan_pelunasan_tabungan = json_decode($tabungan->detail)->saldo - ($jumlah_bayar_angsuran + $jumlah_bayar_margin);

            $detailToPenyimpananTabungan = [
                "teller"            => Auth::user()->id,
                "dari_rekening"     => $tabungan->jenis_tabungan,
                "untuk_rekening"    => $pembiayaan->jenis_pembiayaan,
                "jumlah"            => $jumlah_bayar_angsuran + $jumlah_bayar_margin,
                "saldo_awal"        => $saldo_awal_tujuan_pelunasan_tabungan,
                "saldo_akhir"       => $saldo_akhir_tujuan_pelunasan_tabungan
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
                "saldo" => $saldo_akhir_tujuan_pelunasan_tabungan,
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

    public function cancel_angsuran_mrb($id_pembiayaan){
        DB::beginTransaction();
        try {
            $penyimpanan_pembiayaan = PenyimpananPembiayaan::where('id', $id_pembiayaan)->first();
            $id_user = $penyimpanan_pembiayaan->id_user;
            $bayar_angsuran = json_decode($penyimpanan_pembiayaan->transaksi)->bayar_angsuran;
            $bayar_margin = json_decode($penyimpanan_pembiayaan->transaksi)->bayar_margin;
            $total_bayar = $bayar_angsuran + $bayar_margin;
            $dari_rekening = json_decode($penyimpanan_pembiayaan->transaksi)->dari_rekening;
            $untuk_rekening = json_decode($penyimpanan_pembiayaan->transaksi)->untuk_rekening;
            $rekening_tujuan = Rekening::where('id', $untuk_rekening)->first();
            $nama_rekening = explode(' ', $rekening_tujuan->nama_rekening);
            $user_pembiayaan = User::where('id', $id_user)->first();
            $user_pembiayaan->wajib_pokok = json_encode([
                "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                "margin" => json_decode($user_pembiayaan->wajib_pokok)->margin - floatval($bayar_margin),
            ]);
            $user_pembiayaan->save();


            //update data selanjutnya
           $data = PenyimpananPembiayaan::select('penyimpanan_pembiayaan.*', 'pembiayaan.jenis_pembiayaan','pembiayaan.id_pembiayaan', 'pembiayaan.detail')
                ->join('pembiayaan', 'pembiayaan.id', '=', 'penyimpanan_pembiayaan.id_pembiayaan')
                ->where('penyimpanan_pembiayaan.id_pembiayaan',$penyimpanan_pembiayaan->id_pembiayaan)
                ->where('penyimpanan_pembiayaan.created_at', '>', $penyimpanan_pembiayaan->created_at)
               ->orderby('created_at','ASC')->LIMIT(100)->get();

           $angsuran_ke = 0;

           foreach ($data as $item){

               $data_transaksi = [
                   'teller' => json_decode($item->transaksi)->teller,
                   'dari_rekening' => json_decode($item->transaksi)->dari_rekening ,
                   'untuk_rekening' => json_decode($item->transaksi)->untuk_rekening,
                   'angsuran_pokok' => json_decode($item->transaksi)->angsuran_pokok,
                   'angsuran_ke' => json_decode($item->transaksi)->angsuran_ke - 1,
                   'nisbah' => json_decode($item->transaksi)->nisbah,
                   'margin' => json_decode($item->transaksi)->margin,
                   'jumlah' => json_decode($item->transaksi)->jumlah,
                   'tagihan' => json_decode($item->transaksi)->tagihan,
                   'sisa_angsuran' => json_decode($item->transaksi)->sisa_angsuran + floatval($bayar_angsuran),
                   'sisa_margin' => json_decode($item->transaksi)->sisa_margin + floatval($bayar_margin),
                   'sisa_pinjaman' => floatval(json_decode($item->transaksi)->sisa_pinjaman) + floatval($total_bayar),
                   'bayar_angsuran' => json_decode($item->transaksi)->bayar_angsuran,
                   'bayar_margin' => json_decode($item->transaksi)->bayar_margin,
                   'jumlah_bayar' => json_decode($item->transaksi)->jumlah_bayar,
               ];
               $angsuran_ke = json_decode($item->transaksi)->angsuran_ke - 1;
               $item->transaksi = json_encode($data_transaksi);
               $item->save();
           }

           if ($angsuran_ke == 0 ){
               $angsuran_ke = json_decode($penyimpanan_pembiayaan->transaksi)->angsuran_ke - 1;
           }

           //update data pembiayaan
            $pembiayaan = Pembiayaan::where('id', $penyimpanan_pembiayaan->id_pembiayaan)->first();

           $kelebihan_angsuran = 0;
           //cek kelebihan
            $tagihan_pokok = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
            if ($bayar_angsuran > $tagihan_pokok){
                $kelebihan_angsuran = $bayar_angsuran- $tagihan_pokok;
            }

           $data_pembiayaan = [
               'pinjaman' => json_decode($pembiayaan->detail)->pinjaman,
               'margin' =>json_decode($pembiayaan->detail)->margin,
               'nisbah' => json_decode($pembiayaan->detail)->nisbah,
               'total_pinjaman' => json_decode($pembiayaan->detail)->total_pinjaman,
               'sisa_angsuran' =>json_decode($pembiayaan->detail)->sisa_angsuran + $bayar_angsuran,
               'sisa_margin' =>json_decode($pembiayaan->detail)->sisa_margin + $bayar_margin,
               'sisa_pinjaman' => json_decode($pembiayaan->detail)->sisa_pinjaman +  $total_bayar,
               'angsuran_pokok' => json_decode($pembiayaan->detail)->angsuran_pokok,
               'lama_angsuran' => json_decode($pembiayaan->detail)->lama_angsuran,
               'angsuran_ke' => $angsuran_ke,
               'tagihan_bulanan' =>json_decode($pembiayaan->detail)->tagihan_bulanan,
               'jumlah_angsuran_bulanan' =>json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan,
               'jumlah_margin_bulanan' =>json_decode($pembiayaan->detail)->jumlah_margin_bulanan,
               'sisa_ang_bln' =>  json_decode($pembiayaan->detail)->sisa_ang_bln,
               'sisa_mar_bln' =>  json_decode($pembiayaan->detail)->sisa_mar_bln,
               'kelebihan_angsuran_bulanan' => json_decode($pembiayaan->detail)->kelebihan_angsuran_bulanan,
               'kelebihan_margin_bulanan' => json_decode($pembiayaan->detail)->kelebihan_margin_bulanan,
               'id_pengajuan' => json_decode($pembiayaan->detail)->id_pengajuan,
           ];
           $pembiayaan->detail = json_encode($data_pembiayaan);
           $pembiayaan->angsuran_ke = $angsuran_ke;
           $pembiayaan->status = "active";
           $pembiayaan->save();



            //update data rekening yang terlibat
            $bmt_mrb = BMT::where('id', 322)->first();
            $bmt_piutang = BMT::where('id', 323)->first();
            $bmt_pendapatan = BMT::where('id', 351)->first();
            $bmt_shu_berjalan = BMT::where('id', 344)->first();


                //bmt kas teller atau bank
                if ($nama_rekening[0] == "SIMPANAN")
                {
                    $bmt_tabungan = BMT::where('id_rekening', $untuk_rekening)->first();
                    $id_tabungan_user = explode('[',$dari_rekening);
                    $id_tabungan_user = explode(']', $id_tabungan_user[1]);
                    $id_tabungan_user = $id_tabungan_user[0];

                    $tabungan_user = Tabungan::where('id_tabungan', $id_tabungan_user)->first();
                }
                else{
                    $bmt_teller = BMT::where('id_rekening', $untuk_rekening)->first();
                }


                if ($nama_rekening[0] == "SIMPANAN")
                {
                    //rekening tabungan
                    $detailToPenyimpananBMT = [
                        "jumlah" => floatval($total_bayar),
                        "saldo_awal" => floatval($bmt_tabungan->saldo),
                        "saldo_akhir" => floatval($bmt_tabungan->saldo) + floatval($total_bayar),
                        "id_pengajuan" => null
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user" => $id_user,
                        "id_bmt" => $bmt_tabungan->id,
                        "status" => "Pembatalan Angsuran",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller" => Auth::user()->id
                    ];
                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);


                    //insert penyimpanan tabungan
                    $detailToPenyimpananTabungan = [
                        "teller"        => Auth::user()->id,
                        "dari_rekening" => "",
                        "untuk_rekening"=> "",
                        "jumlah"        => floatval($total_bayar),
                        "saldo_awal"    => floatval(json_decode($tabungan_user->detail)->saldo),
                        "saldo_akhir"   => floatval(json_decode($tabungan_user->detail)->saldo) + floatval($total_bayar),
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => $tabungan_user->id_user,
                        "id_tabungan"   => $tabungan_user->id,
                        "status"        => "Pembatalan Angsuran",
                        "transaksi"     => $detailToPenyimpananTabungan,
                        "teller"        => Auth::user()->id
                    ];

                    $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                    $data_saldo_tabungan = [
                        'saldo' => floatval(json_decode($tabungan_user->detail)->saldo) + floatval($total_bayar),
                        'id_pengajuan'=> json_decode($tabungan_user->detail)->id_pengajuan
                    ];
                    $tabungan_user->detail = json_encode($data_saldo_tabungan);
                    $tabungan_user->save();



                }else{
                    //kas teller atau bank
                    $detailToPenyimpananBMT = [
                        "jumlah" => -floatval($total_bayar),
                        "saldo_awal" => floatval($bmt_teller->saldo),
                        "saldo_akhir" => floatval($bmt_teller->saldo) - floatval($total_bayar),
                        "id_pengajuan" => null
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user" => $id_user,
                        "id_bmt" => $bmt_teller->id,
                        "status" => "Pembatalan Angsuran",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller" => Auth::user()->id
                    ];

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                }


                //rekening piutang
                $detailToPenyimpananBMT = [
                    "jumlah" => floatval($bayar_margin),
                    "saldo_awal" => floatval($bmt_piutang->saldo),
                    "saldo_akhir" => floatval($bmt_piutang->saldo) - floatval($bayar_margin),
                    "id_pengajuan" => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user" => $id_user,
                    "id_bmt" => $bmt_piutang->id,
                    "status" => "Pembatalan Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller" => Auth::user()->id
                ];
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                //rekening pembiayaan mrb
                $detailToPenyimpananBMT = [
                    "jumlah" => floatval($total_bayar),
                    "saldo_awal" => floatval($bmt_mrb->saldo),
                    "saldo_akhir" => floatval($bmt_mrb->saldo) + floatval($total_bayar),
                    "id_pengajuan" => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user" => $id_user,
                    "id_bmt" => $bmt_mrb->id,
                    "status" => "Pembatalan Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller" => Auth::user()->id
                ];
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                //rekening pendapatan
                $detailToPenyimpananBMT = [
                    "jumlah" => floatval($bayar_margin),
                    "saldo_awal" => floatval($bmt_pendapatan->saldo),
                    "saldo_akhir" => floatval($bmt_pendapatan->saldo) - floatval($bayar_margin),
                    "id_pengajuan" => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user" => $id_user,
                    "id_bmt" => $bmt_pendapatan->id,
                    "status" => "Pembatalan Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller" => Auth::user()->id
                ];
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                //shu berjalan
                $detailToPenyimpananBMT = [
                    "jumlah" => floatval($bayar_margin),
                    "saldo_awal" => floatval($bmt_shu_berjalan->saldo),
                    "saldo_akhir" => floatval($bmt_shu_berjalan->saldo) - floatval($bayar_margin),
                    "id_pengajuan" => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user" => $id_user,
                    "id_bmt" => $bmt_shu_berjalan->id,
                    "status" => "Pembatalan Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller" => Auth::user()->id
                ];
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                if ($nama_rekening[0] == "SIMPANAN")
                {
                    $bmt_tabungan->saldo =  floatval($bmt_tabungan->saldo) + floatval($total_bayar);
                    $bmt_tabungan->save();
                }else{
                    $bmt_teller->saldo =  floatval($bmt_teller->saldo) - floatval($total_bayar);
                    $bmt_teller->save();
                }

                $bmt_mrb->saldo =  floatval($bmt_mrb->saldo) + floatval($total_bayar);
                $bmt_piutang->saldo = floatval($bmt_piutang->saldo) - floatval($bayar_margin);
                $bmt_pendapatan->saldo =  floatval($bmt_pendapatan->saldo) - floatval($bayar_margin);
                $bmt_shu_berjalan->saldo =  floatval( $bmt_shu_berjalan->saldo) - floatval($bayar_margin);

                if($bmt_mrb->save() && $bmt_piutang->save() && $bmt_pendapatan->save() && $bmt_shu_berjalan->save())
                {
                    PenyimpananPembiayaan::where('id', $id_pembiayaan)->delete();
                    DB::commit();
                    $response = array('type' => 'success', 'message' => 'Angsuran pembiayaan berhasil dihapus.');

                }
                else{
                    DB::rollback();
                    $response = array('type' => 'error', 'message' => 'Angsuran pembiayaan gagal dihapus.');
                }

        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array('type' => 'error', 'message' => 'Angsuran pembiayaan gagal dihapus.');
        }
        return $response;

    }

    public function cancel_angsuran_lain($id_pembiayaan){
        DB::beginTransaction();
        try{
            $penyimpanan_pembiayaan = PenyimpananPembiayaan::where('id', $id_pembiayaan)->first();
            $id_user = $penyimpanan_pembiayaan->id_user;
            $bayar_angsuran = json_decode($penyimpanan_pembiayaan->transaksi)->bayar_angsuran;
            $bayar_margin = json_decode($penyimpanan_pembiayaan->transaksi)->bayar_margin;
            $total_bayar = $bayar_angsuran + $bayar_margin;
            $dari_rekening = json_decode($penyimpanan_pembiayaan->transaksi)->dari_rekening;
            $untuk_rekening = json_decode($penyimpanan_pembiayaan->transaksi)->untuk_rekening;
            $rekening_tujuan = Rekening::where('id', $untuk_rekening)->first();
            $nama_rekening = explode(' ', $rekening_tujuan->nama_rekening);
            $user_pembiayaan = User::where('id', $id_user)->first();
            $user_pembiayaan->wajib_pokok = json_encode([
                "wajib" => json_decode($user_pembiayaan->wajib_pokok)->wajib,
                "pokok" => json_decode($user_pembiayaan->wajib_pokok)->pokok,
                "khusus" => json_decode($user_pembiayaan->wajib_pokok)->khusus,
                "margin" => json_decode($user_pembiayaan->wajib_pokok)->margin - floatval($bayar_margin),
            ]);
            $user_pembiayaan->save();

            //update data selanjutnya
            $data = PenyimpananPembiayaan::select('penyimpanan_pembiayaan.*', 'pembiayaan.jenis_pembiayaan','pembiayaan.id_pembiayaan', 'pembiayaan.detail')
                ->join('pembiayaan', 'pembiayaan.id', '=', 'penyimpanan_pembiayaan.id_pembiayaan')
                ->where('penyimpanan_pembiayaan.id_pembiayaan',$penyimpanan_pembiayaan->id_pembiayaan)
                ->where('penyimpanan_pembiayaan.created_at', '>', $penyimpanan_pembiayaan->created_at)
                ->orderby('created_at','ASC')->LIMIT(100)->get();

            $angsuran_ke = 0;

            foreach ($data as $item){

                $data_transaksi = [
                    'teller' => json_decode($item->transaksi)->teller,
                    'dari_rekening' => json_decode($item->transaksi)->dari_rekening ,
                    'untuk_rekening' => json_decode($item->transaksi)->untuk_rekening,
                    'angsuran_pokok' => json_decode($item->transaksi)->angsuran_pokok,
                    'angsuran_ke' => json_decode($item->transaksi)->angsuran_ke - 1,
                    'nisbah' => json_decode($item->transaksi)->nisbah,
                    'margin' => json_decode($item->transaksi)->margin,
                    'jumlah' => json_decode($item->transaksi)->jumlah,
                    'tagihan' => json_decode($item->transaksi)->tagihan,
                    'sisa_angsuran' => json_decode($item->transaksi)->sisa_angsuran + floatval($bayar_angsuran),
                    'sisa_margin' => json_decode($item->transaksi)->sisa_margin + floatval($bayar_margin),
                    'sisa_pinjaman' => floatval(json_decode($item->transaksi)->sisa_pinjaman) + floatval($bayar_angsuran),
                    'bayar_angsuran' => json_decode($item->transaksi)->bayar_angsuran,
                    'bayar_margin' => json_decode($item->transaksi)->bayar_margin,
                    'jumlah_bayar' => json_decode($item->transaksi)->jumlah_bayar,
                ];
                $angsuran_ke = json_decode($item->transaksi)->angsuran_ke - 1;
                $item->transaksi = json_encode($data_transaksi);
                $item->save();
            }

            if ($angsuran_ke == 0 ){
                $angsuran_ke = json_decode($penyimpanan_pembiayaan->transaksi)->angsuran_ke - 1;
            }

            //update data pembiayaan
            $pembiayaan = Pembiayaan::where('id', $penyimpanan_pembiayaan->id_pembiayaan)->first();

            $kelebihan_angsuran = 0;
            $kelebihan_margin = 0;
            //cek kelebihan
            $tagihan_pokok = json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan;
            $margin_pokok = json_decode($pembiayaan->detail)->jumlah_margin_bulanan;
            if ($bayar_angsuran > $tagihan_pokok){
                $kelebihan_angsuran = $bayar_angsuran - $tagihan_pokok;
            }

            if ($bayar_margin > $margin_pokok){
                $kelebihan_margin = $bayar_margin - $margin_pokok;
            }


            $data_pembiayaan = [
                'pinjaman' => json_decode($pembiayaan->detail)->pinjaman,
                'margin' =>json_decode($pembiayaan->detail)->margin,
                'nisbah' => json_decode($pembiayaan->detail)->nisbah,
                'total_pinjaman' => json_decode($pembiayaan->detail)->total_pinjaman,
                'sisa_angsuran' =>json_decode($pembiayaan->detail)->sisa_angsuran + $bayar_angsuran,
                'sisa_margin' =>json_decode($pembiayaan->detail)->sisa_margin + $bayar_margin,
                'sisa_pinjaman' => json_decode($pembiayaan->detail)->sisa_pinjaman +  $bayar_angsuran,
                'angsuran_pokok' => json_decode($pembiayaan->detail)->angsuran_pokok,
                'lama_angsuran' => json_decode($pembiayaan->detail)->lama_angsuran,
                'angsuran_ke' => $angsuran_ke,
                'tagihan_bulanan' =>json_decode($pembiayaan->detail)->tagihan_bulanan,
                'jumlah_angsuran_bulanan' =>json_decode($pembiayaan->detail)->jumlah_angsuran_bulanan,
                'jumlah_margin_bulanan' =>json_decode($pembiayaan->detail)->jumlah_margin_bulanan,
                'sisa_ang_bln' =>  json_decode($pembiayaan->detail)->sisa_ang_bln,
                'sisa_mar_bln' =>  json_decode($pembiayaan->detail)->sisa_mar_bln,
                'kelebihan_angsuran_bulanan' => json_decode($pembiayaan->detail)->kelebihan_angsuran_bulanan,
                'kelebihan_margin_bulanan' => json_decode($pembiayaan->detail)->kelebihan_margin_bulanan,
                'id_pengajuan' => json_decode($pembiayaan->detail)->id_pengajuan,
            ];
            $pembiayaan->detail = json_encode($data_pembiayaan);
            $pembiayaan->angsuran_ke = $angsuran_ke;
            $pembiayaan->status = "active";
            $pembiayaan->save();


            //mendapatkan bmt pendapatan dan bmt pembiayaan
            $idbmtpembiayaan = Pembiayaan::where('id', $penyimpanan_pembiayaan->id_pembiayaan)->select('id_rekening')->first();
            $idbmtpembiayaan = Rekening::where('id', $idbmtpembiayaan->id_rekening)->first();

            $idbmtpendapatan = json_decode($idbmtpembiayaan->detail)->rek_margin;
            $idbmtpendapatan = Rekening::where('id_rekening', $idbmtpendapatan)->first();


            //update data rekening yang terlibat
            $bmt_pembiayaan = BMT::where('id_rekening', $idbmtpembiayaan->id)->first();
            $bmt_pendapatan = BMT::where('id_rekening', $idbmtpendapatan->id)->first();
            $bmt_shu_berjalan = BMT::where('id', 344)->first();


            //bmt kas teller atau bank
            if ($nama_rekening[0] == "SIMPANAN")
            {
                $bmt_tabungan = BMT::where('id_rekening', $untuk_rekening)->first();
                $id_tabungan_user = explode('[',$dari_rekening);
                $id_tabungan_user = explode(']', $id_tabungan_user[1]);
                $id_tabungan_user = $id_tabungan_user[0];

                $tabungan_user = Tabungan::where('id_tabungan', $id_tabungan_user)->first();
            }
            else{
                $bmt_teller = BMT::where('id_rekening', $untuk_rekening)->first();
            }

            if ($nama_rekening[0] == "SIMPANAN")
            {
                //rekening tabungan
                $detailToPenyimpananBMT = [
                    "jumlah" => floatval($total_bayar),
                    "saldo_awal" => floatval($bmt_tabungan->saldo),
                    "saldo_akhir" => floatval($bmt_tabungan->saldo) + floatval($total_bayar),
                    "id_pengajuan" => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user" => $id_user,
                    "id_bmt" => $bmt_tabungan->id,
                    "status" => "Pembatalan Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller" => Auth::user()->id
                ];
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);


                //insert penyimpanan tabungan
                $detailToPenyimpananTabungan = [
                    "teller"        => Auth::user()->id,
                    "dari_rekening" => "",
                    "untuk_rekening"=> "",
                    "jumlah"        => floatval($total_bayar),
                    "saldo_awal"    => floatval(json_decode($tabungan_user->detail)->saldo),
                    "saldo_akhir"   => floatval(json_decode($tabungan_user->detail)->saldo) + floatval($total_bayar),
                ];
                $dataToPenyimpananTabungan = [
                    "id_user"       => $tabungan_user->id_user,
                    "id_tabungan"   => $tabungan_user->id,
                    "status"        => "Pembatalan Angsuran",
                    "transaksi"     => $detailToPenyimpananTabungan,
                    "teller"        => Auth::user()->id
                ];

                $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                $data_saldo_tabungan = [
                    'saldo' => floatval(json_decode($tabungan_user->detail)->saldo) + floatval($total_bayar),
                    'id_pengajuan'=> json_decode($tabungan_user->detail)->id_pengajuan
                ];
                $tabungan_user->detail = json_encode($data_saldo_tabungan);
                $tabungan_user->save();



            }else{
                //kas teller atau bank
                $detailToPenyimpananBMT = [
                    "jumlah" => -floatval($total_bayar),
                    "saldo_awal" => floatval($bmt_teller->saldo),
                    "saldo_akhir" => floatval($bmt_teller->saldo) - floatval($total_bayar),
                    "id_pengajuan" => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user" => $id_user,
                    "id_bmt" => $bmt_teller->id,
                    "status" => "Pembatalan Angsuran",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller" => Auth::user()->id
                ];

                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
            }

            //rekening pembiayaan
            $detailToPenyimpananBMT = [
                "jumlah" => floatval($bayar_angsuran),
                "saldo_awal" => floatval($bmt_pembiayaan->saldo),
                "saldo_akhir" => floatval($bmt_pembiayaan->saldo) + floatval($bayar_angsuran),
                "id_pengajuan" => null
            ];
            $dataToPenyimpananBMT = [
                "id_user" => $id_user,
                "id_bmt" => $bmt_pembiayaan->id,
                "status" => "Pembatalan Angsuran",
                "transaksi" => $detailToPenyimpananBMT,
                "teller" => Auth::user()->id
            ];
            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            //rekening pendapatan
            $detailToPenyimpananBMT = [
                "jumlah" => floatval($bayar_margin),
                "saldo_awal" => floatval($bmt_pendapatan->saldo),
                "saldo_akhir" => floatval($bmt_pendapatan->saldo) - floatval($bayar_margin),
                "id_pengajuan" => null
            ];
            $dataToPenyimpananBMT = [
                "id_user" => $id_user,
                "id_bmt" => $bmt_pendapatan->id,
                "status" => "Pembatalan Angsuran",
                "transaksi" => $detailToPenyimpananBMT,
                "teller" => Auth::user()->id
            ];
            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            //shu berjalan
            $detailToPenyimpananBMT = [
                "jumlah" => floatval($bayar_margin),
                "saldo_awal" => floatval($bmt_shu_berjalan->saldo),
                "saldo_akhir" => floatval($bmt_shu_berjalan->saldo) - floatval($bayar_margin),
                "id_pengajuan" => null
            ];
            $dataToPenyimpananBMT = [
                "id_user" => $id_user,
                "id_bmt" => $bmt_shu_berjalan->id,
                "status" => "Pembatalan Angsuran",
                "transaksi" => $detailToPenyimpananBMT,
                "teller" => Auth::user()->id
            ];
            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            if ($nama_rekening[0] == "SIMPANAN")
            {
                $bmt_tabungan->saldo =  floatval($bmt_tabungan->saldo) + floatval($total_bayar);
                $bmt_tabungan->save();
            }else{
                $bmt_teller->saldo =  floatval($bmt_teller->saldo) - floatval($total_bayar);
                $bmt_teller->save();
            }

            $bmt_pembiayaan->saldo =  floatval($bmt_pembiayaan->saldo) + floatval($bayar_angsuran);
            $bmt_pendapatan->saldo =  floatval($bmt_pendapatan->saldo) - floatval($bayar_margin);
            $bmt_shu_berjalan->saldo =  floatval( $bmt_shu_berjalan->saldo) - floatval($bayar_margin);

            if($bmt_pembiayaan->save()  && $bmt_pendapatan->save() && $bmt_shu_berjalan->save())
            {
                PenyimpananPembiayaan::where('id', $id_pembiayaan)->delete();
                DB::commit();
                $response = array('type' => 'success', 'message' => 'Angsuran pembiayaan berhasil dihapus.');

            }
            else{
                DB::rollback();
                $response = array('type' => 'error', 'message' => 'Angsuran pembiayaan gagal dihapus.');
            }

        }catch(Exception $ex){
            DB::rollback();
            $response = array('type' => 'error', 'message' => 'Angsuran pembiayaan gagal dihapus.');
        }

        return $response;

    }


    public  function numberToRoman($num)
    {
        // Make sure that we only use the integer portion of the value
        $n = intval($num);
        $result = '';

        // Declare a lookup array that we will use to traverse the number:
        $lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);

        foreach ($lookup as $roman => $value)
        {
            // Determine the number of matches
            $matches = intval($n / $value);

            // Store that many characters
            $result .= str_repeat($roman, $matches);

            // Substract that from the number
            $n = $n % $value;
        }

        // The Roman numeral should be built, return it
        return $result;
    }

    public function uangToKalimat($bilangan){
        $angka = array('0','0','0','0','0','0','0','0','0','0',
            '0','0','0','0','0','0');
        $kata = array('','satu','dua','tiga','empat','lima',
            'enam','tujuh','delapan','sembilan');
        $tingkat = array('','ribu','juta','milyar','triliun');

        $panjang_bilangan = strlen($bilangan);

        /* pengujian panjang bilangan */
        if ($panjang_bilangan > 15) {
            $kalimat = "Diluar Batas";
            return $kalimat;
        }

        /* mengambil angka-angka yang ada dalam bilangan,
           dimasukkan ke dalam array */
        for ($i = 1; $i <= $panjang_bilangan; $i++) {
            $angka[$i] = substr($bilangan,-($i),1);
        }

        $i = 1;
        $j = 0;
        $kalimat = "";


        /* mulai proses iterasi terhadap array angka */
        while ($i <= $panjang_bilangan) {

            $subkalimat = "";
            $kata1 = "";
            $kata2 = "";
            $kata3 = "";

            /* untuk ratusan */
            if ($angka[$i+2] != "0") {
                if ($angka[$i+2] == "1") {
                    $kata1 = "seratus";
                } else {
                    $kata1 = $kata[$angka[$i+2]] . " ratus";
                }
            }

            /* untuk puluhan atau belasan */
            if ($angka[$i+1] != "0") {
                if ($angka[$i+1] == "1") {
                    if ($angka[$i] == "0") {
                        $kata2 = "sepuluh";
                    } elseif ($angka[$i] == "1") {
                        $kata2 = "sebelas";
                    } else {
                        $kata2 = $kata[$angka[$i]] . " belas";
                    }
                } else {
                    $kata2 = $kata[$angka[$i+1]] . " puluh";
                }
            }

            /* untuk satuan */
            if ($angka[$i] != "0") {
                if ($angka[$i+1] != "1") {
                    $kata3 = $kata[$angka[$i]];
                }
            }

            /* pengujian angka apakah tidak nol semua,
               lalu ditambahkan tingkat */
            if (($angka[$i] != "0") OR ($angka[$i+1] != "0") OR
                ($angka[$i+2] != "0")) {
                $subkalimat = "$kata1 $kata2 $kata3 " . $tingkat[$j] . " ";
            }

            /* gabungkan variabe sub kalimat (untuk satu blok 3 angka)
               ke variabel kalimat */
            $kalimat = $subkalimat . $kalimat;
            $i = $i + 3;
            $j = $j + 1;

        }

        /* mengganti satu ribu jadi seribu jika diperlukan */
        if (($angka[5] == "0") AND ($angka[6] == "0")) {
            $kalimat = str_replace("satu ribu","seribu",$kalimat);
        }

        return trim($kalimat);
    }

}
