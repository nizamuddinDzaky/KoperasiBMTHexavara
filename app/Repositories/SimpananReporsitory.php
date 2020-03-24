<?php

namespace App\Repositories;

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\DB;
use App\Pengajuan;
use App\PenyimpananWajibPokok;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\RekeningReporsitories;
use App\BMT;
use App\Tabungan;

class SimpananReporsitory {

    public function __construct(PengajuanReporsitories $pengajuanReporsitory,
                                RekeningReporsitories $rekeningReporsitory
                                ) 
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
    }

    /** 
     * Get pengajuan data with kategory Simpanan from specific user
     * @return Array
    */
    public function getUserPengajuanSimpanan()
    {
        $simpanan = Pengajuan::where('kategori', 'Simpanan Wajib')->orWhere('kategori', 'Simpanan Pokok')->orWhere('kategori', 'Simpanan Khusus')->get();

        return $simpanan;
    }

    /** 
     * Get simpanan wajib dan pokok in user data
     * @return Array
    */
    public function getSimwaAndSimpok()
    {
        $simwaAndSimpok = User::where('id', Auth::user()->id)->select(['wajib_pokok', 'created_at'])->get();

        return $simwaAndSimpok;
    }

    /** 
     * Pengajuan simpanan pokok and wajib
     * @return Response
    */
    public function pengajuanSimpanan($data)
    {
        DB::beginTransaction();
        try
        {
            if($data->debit == 0)
            {
                $jenis = "Tunai";
                $daribank = null;
                $nobank = null;
                $atasnama = null;
                $bank_tujuan_transfer = json_decode(Auth::user()->detail)->id_rekening;
                $path_bukti = null;
            }
            if($data->debit == 1)
            {
                $file_name = $data->file->getClientOriginalName();
                $fileToUpload = time() . "-" . $file_name;
                $data->file('file')->storeAs(
                    'transfer/', $fileToUpload
                );
                $path_bukti_file = "storage/transfer/" . $fileToUpload;

                $jenis = "Transfer";
                $daribank = $data->daribank;
                $nobank = $data->nobank;
                $atasnama = $data->atasnama;
                $bank_tujuan_transfer = $data->bank;
                $path_bukti = $path_bukti_file;

            }
            if($data->debit == 2)
            {
                $jenis = "Tabungan";
                $daribank = null;
                $nobank = null;
                $atasnama = null;
                $bank_tujuan_transfer = $data->dari_tabungan;
                $path_bukti = null;
            }

            if($data->id_rekening_simpanan == "119")
            {
                $jenis_simpanan = "Simpanan Wajib";
            }
            if($data->id_rekening_simpanan == "120")
            {
                $jenis_simpanan = "Simpanan Khusus";
            }
            if($data->id_rekening_simpanan == "117")
            {
                $jenis_simpanan = "Simpanan Pokok";
            }
            $detailToPengajuan = [
                "daribank"      => $daribank,
                "no_bank"       => $nobank,
                "jenis"         => $jenis,
                "id"            => Auth::user()->id,
                "nama"          => Auth::user()->nama,
                "atasnama"      => $atasnama,
                "bank_tujuan_transfer" => $bank_tujuan_transfer,
                "path_bukti"    => $path_bukti,
                "jumlah"        => $data->nominal
            ];
            $dataToPengajuan = [
                "id_user"       => Auth::user()->id,
                "id_rekening"   => $data->id_rekening_simpanan,
                "jenis_pengajuan" => $jenis_simpanan,
                "status"        => "Menunggu Konfirmasi",
                "kategori"      => $jenis_simpanan,
                "detail"        => $detailToPengajuan,
                "teller"        => 0
            ];

            if($this->pengajuanReporsitory->createPengajuan($dataToPengajuan)['type'] == "success")
            {
                DB::commit();
                return array("type" => "success", "message" => "Pengajuan simpanan berhasil dibuat.");
            }
            else
            {
                DB::rollback();
                return array("type" => "error", "message" => "Pengajuan simpanan gagal dibuat.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            return array("type" => "error", "message" => "Pengajuan simpanan gagal dibuat.");
        }
    }

    /**
     * Konfirmasi pengajuan simpanan pokok dan wajib
     * @return Response
     */
    public function confirmPengajuanSimpanan($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_pengajuan);
            $user_simpanan = User::where('id', $pengajuan->id_user)->first();
            if(json_decode($pengajuan->detail)->jenis == "Transfer")
            {
                $bmt_simpanan = BMT::where('id_rekening', $data->id_rekening_simpanan)->first();
                $bmt_bank_pengirim = BMT::where('id_rekening', json_decode($pengajuan->detail)->bank_tujuan_transfer)->first(); 

                $id_bmt_simpanan = $bmt_simpanan->id;
                $id_bmt_bank_pengirim = $bmt_bank_pengirim->id;
            }
            if(json_decode($pengajuan->detail)->jenis == "Tabungan")
            {
                $bmt_simpanan = BMT::where('id_rekening', $data->id_rekening_simpanan)->first();
                $tabungan_pengirim = Tabungan::where('id_tabungan', json_decode($pengajuan->detail)->bank_tujuan_transfer)->first();
                $bmt_bank_pengirim = BMT::where('id_rekening', $tabungan_pengirim->id_rekening)->first();

                $id_bmt_simpanan = $bmt_simpanan->id;
                $id_bmt_bank_pengirim = $bmt_bank_pengirim->id;
            }

            if($data->id_rekening_simpanan == 117)
            {
                $nama_rekening = "Simpanan Pokok";

                $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->pokok;
                $saldo_awal_pengirim = $bmt_bank_pengirim->saldo;

                if(isset(json_decode($user_simpanan->wajib_pokok)->margin))
                {
                    $dataToUpdateUsers = [
                        "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                        "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok) + json_decode($pengajuan->detail)->jumlah,
                        "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                    ];
                }
                else
                {
                    $dataToUpdateUsers = [
                        "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                        "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok) + json_decode($pengajuan->detail)->jumlah,
                    ];
                }

                if(json_decode($pengajuan->detail)->jenis == "Tabungan")
                {
                    $dataToUpdateBMTPengirim = floatval($bmt_bank_pengirim->saldo) - floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_pengirim = $saldo_awal_pengirim - floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_simpanan = $saldo_awal_simpanan + floatval(json_decode($pengajuan->detail)->jumlah);
                }
                if(json_decode($pengajuan->detail)->jenis == "Transfer")
                {
                    $dataToUpdateBMTPengirim = floatval($bmt_bank_pengirim->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_pengirim = $saldo_awal_pengirim + floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_simpanan = $saldo_awal_simpanan + floatval(json_decode($pengajuan->detail)->jumlah);
                }
                $dataToUpdateBMTSimpanan = floatval($bmt_simpanan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
            }

            if($data->id_rekening_simpanan == 119)
            {
                $nama_rekening = "Simpanan Wajib";
            
                $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->wajib;
                $saldo_awal_pengirim = $bmt_bank_pengirim->saldo;

                if(isset(json_decode($user_simpanan->wajib_pokok)->margin))
                {
                    $dataToUpdateUsers = [
                        "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib) + json_decode($pengajuan->detail)->jumlah,
                        "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                        "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                    ];
                }
                else
                {
                    $dataToUpdateUsers = [
                        "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib) + json_decode($pengajuan->detail)->jumlah,
                        "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                    ];
                }

                if(json_decode($pengajuan->detail)->jenis == "Tabungan")
                {
                    $dataToUpdateBMTPengirim = floatval($bmt_bank_pengirim->saldo) - floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_pengirim = $saldo_awal_pengirim - floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_simpanan = $saldo_awal_simpanan + floatval(json_decode($pengajuan->detail)->jumlah);
                }
                if(json_decode($pengajuan->detail)->jenis == "Transfer")
                {
                    $dataToUpdateBMTPengirim = floatval($bmt_bank_pengirim->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_pengirim = $saldo_awal_pengirim + floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_simpanan = $saldo_awal_simpanan + floatval(json_decode($pengajuan->detail)->jumlah);
                }
                $dataToUpdateBMTSimpanan = floatval($bmt_simpanan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
            }

            if($data->id_rekening_simpanan == 120)
            {
                $nama_rekening = "Simpanan Khusus";
            
                if(isset(json_decode($user_simpanan->wajib_pokok)->khusus))
                {
                    $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->khusus;
                }
                else
                {
                    $saldo_awal_simpanan = 0;
                }
                $saldo_awal_pengirim = $bmt_bank_pengirim->saldo;

                if(isset(json_decode($user_simpanan->wajib_pokok)->margin))
                {
                    $dataToUpdateUsers = [
                        "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                        "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                        "khusus"     => floatval(json_decode($saldo_awal_simpanan))  + json_decode($pengajuan->detail)->jumlah,
                        "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                    ];
                }
                else
                {
                    $dataToUpdateUsers = [
                        "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib) + json_decode($pengajuan->detail)->jumlah,
                        "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                        "khusus"     => floatval(json_decode($saldo_awal_simpanan))  + json_decode($pengajuan->detail)->jumlah,
                    ];
                }

                if(json_decode($pengajuan->detail)->jenis == "Tabungan")
                {
                    $dataToUpdateBMTPengirim = floatval($bmt_bank_pengirim->saldo) - floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_pengirim = $saldo_awal_pengirim - floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_simpanan = $saldo_awal_simpanan + floatval(json_decode($pengajuan->detail)->jumlah);
                }
                if(json_decode($pengajuan->detail)->jenis == "Transfer")
                {
                    $dataToUpdateBMTPengirim = floatval($bmt_bank_pengirim->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_pengirim = $saldo_awal_pengirim + floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_simpanan = $saldo_awal_simpanan + floatval(json_decode($pengajuan->detail)->jumlah);
                }
                $dataToUpdateBMTSimpanan = floatval($bmt_simpanan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
            }

            $detailToPenyimpananBMT = [
                "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal"    => $saldo_awal_pengirim,
                "saldo_akhir"   => $saldo_akhir_pengirim,
                "id_pengajuan"  => $pengajuan->id
            ];
            $dataToPenyimpananBMT = [
                "id_user"   => $pengajuan->id_user,
                "id_bmt"    => $id_bmt_bank_pengirim,
                "status"    => $nama_rekening,
                "transaksi" => $detailToPenyimpananBMT,
                "teller"    => Auth::user()->id
            ];

            // Insert record for bank pengirim
            $insertIntoPenyimpananBMTPengirim = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
            if($insertIntoPenyimpananBMTPengirim == "success")
            {
                $detailToPenyimpananBMT['saldo_awal'] = floatval($saldo_awal_simpanan);
                $detailToPenyimpananBMT['saldo_akhir'] = floatval($saldo_akhir_simpanan);
                $dataToPenyimpananBMT['id_bmt'] = $id_bmt_simpanan;
                $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                
                // Insert record for simpanan
                $insertIntoPenyimpananBMTSimpanan = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                if($insertIntoPenyimpananBMTSimpanan == "success")
                {
                    if($data->id_rekening_simpanan == 117)
                    {
                        $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->pokok;
                    }
                    if($data->id_rekening_simpanan == 119)
                    {
                        $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->wajib;
                    }
                    $detailToPenyimpananWajibPokok = [
                        "teller"        => Auth::user()->id,
                        "dari_rekening" => json_decode($pengajuan->detail)->bank_tujuan_transfer,
                        "untuk_rekening"=> $data->id_rekening_simpanan,
                        "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                        "saldo_awal"    => $saldo_awal_simpanan,
                        "saldo_akhir"   => floatval($saldo_awal_simpanan) + json_decode($pengajuan->detail)->jumlah,
                    ];
                    $dataToPenyimpananWajibPokok = [
                        "id_user"       => $pengajuan->id_user,
                        "id_rekening"   => $data->id_rekening_simpanan,
                        "status"        => $nama_rekening,
                        "transaksi"     => $detailToPenyimpananWajibPokok,
                        "teller"        => Auth::user()->id
                    ];
                    
                    $insertToPenyimpananWajibPokok = $this->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);

                    if($insertToPenyimpananWajibPokok == "success")
                    {
                        $updateBMTPengirim = BMT::where('id', $id_bmt_bank_pengirim)->update([ "saldo" => $dataToUpdateBMTPengirim ]);
                        $updateBMTSimpanan = BMT::where('id', $id_bmt_simpanan)->update([ "saldo" => $dataToUpdateBMTSimpanan ]);
                        $updateUser = User::where('id', $pengajuan->id_user)->update([ "wajib_pokok" => json_encode($dataToUpdateUsers) ]);
                        $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([
                            "status"    => "Sudah Dikonfirmasi",
                            "teller"    => Auth::user()->id
                        ]);

                        if(json_decode($pengajuan->detail)->jenis == "Tabungan")
                        {
                            $dataToUpdateTabungan = [
                                "saldo" => floatval(json_decode($tabungan_pengirim->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah),
                                "id_pengajuan" => $pengajuan->id
                            ];

                            $tabungan = Tabungan::where('id_tabungan', json_decode($pengajuan->detail)->bank_tujuan_transfer)->update([
                                "detail"    => json_encode($dataToUpdateTabungan)
                            ]);
                        }

                        DB::commit();
                        $response = array("type" => "success", "message" => "Pengajuan " . $nama_rekening . " Berhasil Dikonfirmasi.");
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pengajuan " . $nama_rekening . " Gagal Dikonfirmasi.");
                    }
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan " . $nama_rekening . " Gagal Dikonfirmasi.");
                }

            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan " . $nama_rekening . " Gagal Dikonfirmasi.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan " . $nama_rekening . " Gagal Dikonfirmasi.");
        }

        return $response;
    }

    /**
     * Insert record to penyimpanan wajib pokok
     * @return Response 
    */
    public function insertPenyimpananWajibPokok($data)
    {
        $simpanan = new PenyimpananWajibPokok();
        $simpanan->id_user = $data['id_user'];
        $simpanan->id_rekening = $data['id_rekening'];
        $simpanan->status = $data['status'];
        $simpanan->transaksi = json_encode($data['transaksi']);
        $simpanan->teller = $data['teller'];
        
        if($simpanan->save())
        {
            return "success";
        }
        else
        {
            return "error";
        }
    }
}