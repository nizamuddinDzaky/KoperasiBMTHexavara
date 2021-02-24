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
use App\Repositories\TabunganReporsitories;
use App\BMT;
use App\Tabungan;

class SimpananReporsitory {

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
     * Get pengajuan data with kategory Simpanan from specific user
     * @return Array
    */
    public function getUserPengajuanSimpananFromSpecificUser()
    {
        $simpanan = Pengajuan::where([ ['kategori', 'Simpanan Wajib'], ['id_user', Auth::user()->id] ])
                                ->orWhere([ ['kategori', 'Simpanan Pokok'], ['id_user', Auth::user()->id] ])
                                ->orWhere([ ['kategori', 'Simpanan Khusus'], ['id_user', Auth::user()->id] ])->get();

        return $simpanan;
    }

    /** 
     * Get pengajuan data with kategory Simpanan
     * @return Array
    */
    public function getUserPengajuanSimpanan()
    {
        $simpanan = Pengajuan::where('kategori', 'Simpanan Wajib')
                                ->orWhere('kategori', 'Simpanan Pokok')
                                ->orWhere('kategori', 'Simpanan Khusus')->get();

        return $simpanan;
    }

    /** 
     * Get simpanan wajib dan pokok in user data
     * @return Array
    */
    public function getSimwaAndSimpok($custom_id="")
    {
        $simwaAndSimpok = User::where('id', Auth::user()->id)->first();
        if($custom_id != "")
        {
            $simwaAndSimpok = User::where('id', $custom_id)->first();
        }
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
                $tabungan = Tabungan::where('id_tabungan', $bank_tujuan_transfer)->first();
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


            if(preg_match("/[a-z.]/i", $data->nominal)){
                DB::rollback();
                return array("type" => "error", "message" => "Pengajuan simpanan gagal dibuat. Gunakan format angka yang tepat.");
            }

            $data->nominal = preg_replace('/[^\d]/', '', $data->nominal);
            $data->nominal = ltrim($data->nominal, "0");

            $detailToPengajuan = [
                "daribank"      => $daribank,
                "no_bank"       => $nobank,
                "jenis"         => $jenis,
                "id"            => Auth::user()->id,
                "nama"          => Auth::user()->nama,
                "atasnama"      => $atasnama,
                "bank_tujuan_transfer" => $bank_tujuan_transfer,
                "path_bukti"    => $path_bukti,
                "jumlah"        => floatval(preg_replace('/[^\d.]/', '', $data->nominal))
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


            if($data->debit == 2)
            {
                if(floatval(json_decode($tabungan->detail)->saldo) > floatval(preg_replace('/[^\d.]/', '', $data->nominal)))
                {
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
                else
                {
                    DB::rollback();
                    return array("type" => "error", "message" => "Pengajuan simpanan gagal dibuat. Saldo " . $tabungan->jenis_tabungan . " tidak cukup.");
                }
            }
            else
            {
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
            $pengajuan = $this->pengajuanReporsitory->findPengajuan($data->id_pengajuan); //tarik data pengajuan sesuai dengan id pengajuan
            $user_simpanan = User::where('id', $pengajuan->id_user)->first(); // abil data user sesuai dengan id user di pengajuan
            if(json_decode($pengajuan->detail)->jenis == "Transfer")
            {
                $bmt_simpanan = BMT::where('id_rekening', $data->id_rekening_simpanan)->first();
                $bmt_bank_pengirim = BMT::where('id_rekening', json_decode($pengajuan->detail)->bank_tujuan_transfer)->first(); 

                $id_bmt_simpanan = $bmt_simpanan->id;
                $id_bmt_bank_pengirim = $bmt_bank_pengirim->id;
            }
            if(json_decode($pengajuan->detail)->jenis == "Tabungan")
            {
                $bmt_simpanan = BMT::where('id_rekening', $data->id_rekening_simpanan)->first(); // mengambil data bmt untuk id 120
                $tabungan_pengirim = Tabungan::where('id_tabungan', json_decode($pengajuan->detail)->bank_tujuan_transfer)->first(); // mendapatkan tabungan asal
                $bmt_bank_pengirim = BMT::where('id_rekening', $tabungan_pengirim->id_rekening)->first(); // mendapatkan rekening bmt tabungan

                $id_bmt_simpanan = $bmt_simpanan->id; // id bmt simpanan khusus
                $id_bmt_bank_pengirim = $bmt_bank_pengirim->id; // id rekening simpanan asal
            }

            $bmt_simpanan = BMT::where('id_rekening', $data->id_rekening_simpanan)->first();
            $saldo_awal_bmt_simpanan = $bmt_simpanan->saldo; // saldo awal simpanan khusus
            $saldo_akhir_bmt_simpanan = $saldo_awal_bmt_simpanan + floatval(json_decode($pengajuan->detail)->jumlah); // saldo awal simpanan khusus + jumlah pengajuan

            if($data->id_rekening_simpanan == 117)
            {
                $nama_rekening = "Simpanan Pokok";

                $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->pokok;
                $saldo_awal_pengirim = $bmt_bank_pengirim->saldo;

                if(isset(json_decode($user_simpanan->wajib_pokok)->margin))
                {
                    if(isset(json_decode($user_simpanan->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok) + json_decode($pengajuan->detail)->jumlah,
                            "khusus"     => floatval(json_decode($user_simpanan->wajib_pokok)->khusus),
                            "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok) + json_decode($pengajuan->detail)->jumlah,
                            "khusus"     => 0,
                            "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                        ];
                    }
                }
                else
                {
                    if(isset(json_decode($user_simpanan->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok) + json_decode($pengajuan->detail)->jumlah,
                            "khusus"     => floatval(json_decode($user_simpanan->wajib_pokok)->khusus),
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok) + json_decode($pengajuan->detail)->jumlah,
                            "khusus"     => 0,
                        ];
                    }
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
                    if(isset(json_decode($user_simpanan->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib) + json_decode($pengajuan->detail)->jumlah,
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($user_simpanan->wajib_pokok)->khusus),
                            "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib) + json_decode($pengajuan->detail)->jumlah,
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                            "khusus"     => 0,
                            "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                        ];
                    }
                }
                else
                {
                    if(isset(json_decode($user_simpanan->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib) + json_decode($pengajuan->detail)->jumlah,
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($user_simpanan->wajib_pokok)->khusus),
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib) + json_decode($pengajuan->detail)->jumlah,
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                            "khusus"     => 0,
                        ];
                    }
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
                    $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->khusus; //dapet saldo awal khusus user
                }
                else
                {
                    $saldo_awal_simpanan = 0;
                }
                $saldo_awal_pengirim = $bmt_bank_pengirim->saldo; // saldo awal tabungan bmt

                if(isset(json_decode($user_simpanan->wajib_pokok)->margin))
                {
                    if(isset(json_decode($user_simpanan->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($saldo_awal_simpanan))  + json_decode($pengajuan->detail)->jumlah, //data untuk update user
                            "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($saldo_awal_simpanan))  + json_decode($pengajuan->detail)->jumlah,
                            "margin"    => floatval(json_decode($user_simpanan->wajib_pokok)->margin)
                        ];
                    }
                }
                else
                {
                    if(isset(json_decode($user_simpanan->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($saldo_awal_simpanan))  + json_decode($pengajuan->detail)->jumlah,
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user_simpanan->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user_simpanan->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($saldo_awal_simpanan))  + json_decode($pengajuan->detail)->jumlah,
                        ];
                    }
                }

                if(json_decode($pengajuan->detail)->jenis == "Tabungan")
                {
                    $dataToUpdateBMTPengirim = floatval($bmt_bank_pengirim->saldo) - floatval(json_decode($pengajuan->detail)->jumlah);// kurangi saldo bmt tabungan dengan jumlah pengajuan
                    $saldo_akhir_pengirim = $saldo_awal_pengirim - floatval(json_decode($pengajuan->detail)->jumlah); // saldo awal tabungan user dikurangi jumlah pengajuan
                    $saldo_akhir_simpanan = $saldo_awal_simpanan + floatval(json_decode($pengajuan->detail)->jumlah); // saldo akhir dari user khusus
                }
                if(json_decode($pengajuan->detail)->jenis == "Transfer")
                {
                    $dataToUpdateBMTPengirim = floatval($bmt_bank_pengirim->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_pengirim = $saldo_awal_pengirim + floatval(json_decode($pengajuan->detail)->jumlah);
                    $saldo_akhir_simpanan = $saldo_awal_simpanan + floatval(json_decode($pengajuan->detail)->jumlah);
                }
                $dataToUpdateBMTSimpanan = floatval($bmt_simpanan->saldo) + floatval(json_decode($pengajuan->detail)->jumlah); // bmt simpanan khusus ditambah jumlah pengajuan
            }

            $detailToPenyimpananBMT = [
                "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal"    => $saldo_awal_pengirim, //saldo tabungan
                "saldo_akhir"   => $saldo_akhir_pengirim,
                "id_pengajuan"  => $pengajuan->id
            ];
            $dataToPenyimpananBMT = [
                "id_user"   => $pengajuan->id_user,
                "id_bmt"    => $id_bmt_bank_pengirim,
                "status"    => "Pembayaran " . $nama_rekening,
                "transaksi" => $detailToPenyimpananBMT,
                "teller"    => Auth::user()->id
            ];

            // Insert record for bank pengirim
            $insertIntoPenyimpananBMTPengirim = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT); //masuk data tabungan pengirim yang sudah berkurang
            if($insertIntoPenyimpananBMTPengirim == "success")
            {

                $detailToPenyimpananBMT['saldo_awal'] = floatval($saldo_awal_bmt_simpanan);
                $detailToPenyimpananBMT['saldo_akhir'] = floatval($saldo_akhir_bmt_simpanan);
                $detailToPenyimpananBMT['jumlah'] = json_decode($pengajuan->detail)->jumlah;
                $dataToPenyimpananBMT['id_bmt'] = $id_bmt_simpanan;
                $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                // Insert record for simpanan
                $insertIntoPenyimpananBMTSimpanan = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT); // masuk data simpanan khusus

                if($insertIntoPenyimpananBMTSimpanan == "success")
                {
                    $detailToPenyimpananWajibPokok = [
                        "teller"        => Auth::user()->id,
                        "dari_rekening" => $bmt_bank_pengirim->nama,
                        "untuk_rekening"=> $bmt_simpanan->nama,
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
                }

                $insertToPenyimpananWajibPokok = $this->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok); //masuk ke history penyimpanan wajib pokok

                if($insertToPenyimpananWajibPokok == "success")
                {
                    if(json_decode($pengajuan->detail)->jenis == "Tabungan")
                    {
                        if(json_decode($tabungan_pengirim->detail)->saldo > json_decode($pengajuan->detail)->jumlah)
                        {
                            $updateBMTPengirim = BMT::where('id', $id_bmt_bank_pengirim)->update([ "saldo" => $dataToUpdateBMTPengirim ]); // update saldo tabungan alias dikurangi
                            $updateBMTSimpanan = BMT::where('id', $id_bmt_simpanan)->update([ "saldo" => $dataToUpdateBMTSimpanan ]); // tambah saldo simpanan khusus
                            $updateUser = User::where('id', $pengajuan->id_user)->update([ "wajib_pokok" => json_encode($dataToUpdateUsers) ]); // update saldo user
                            $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([
                                "status"    => "Sudah Dikonfirmasi",
                                "teller"    => Auth::user()->id
                            ]); // update pengajuan

                            $dataToUpdateTabungan = [
                                "saldo" => floatval(json_decode($tabungan_pengirim->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah),
                                "id_pengajuan" => $pengajuan->id
                            ]; // update saldo tabungan pribadi

                            $tabungan = Tabungan::where('id_tabungan', json_decode($pengajuan->detail)->bank_tujuan_transfer)->update([
                                "detail"    => json_encode($dataToUpdateTabungan)
                            ]);

                            DB::commit();
                            $response = array("type" => "success", "message" => "Pengajuan " . $nama_rekening . " Berhasil Dikonfirmasi.");
                        }
                        else
                        {
                            DB::rollback();
                            $response = array("type" => "error", "message" => "Pengajuan " . $nama_rekening . " Gagal Dikonfirmasi.");
                        }

                        $detailToPenyimpananTabungan = [
                            "teller"        => Auth::user()->id,
                            "dari_rekening"    => $bmt_bank_pengirim->nama,
                            "untuk_rekening"   => $bmt_simpanan->nama,
                            "jumlah"  => json_decode($pengajuan->detail)->jumlah,
                            "saldo_awal"  => $saldo_awal_pengirim,
                            "saldo_akhir"  => $saldo_akhir_pengirim
                        ];
                        $dataToPenyimpananTabungan = [
                            "id_user"   => $pengajuan->id_user,
                            "id_tabungan"    => $tabungan_pengirim->id,
                            "status"    => "Pembayaran " . $nama_rekening,
                            "transaksi" => $detailToPenyimpananTabungan,
                            "teller"    => Auth::user()->id
                        ];

                        $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);

                    }
                    else
                    {
                        $updateBMTPengirim = BMT::where('id', $id_bmt_bank_pengirim)->update([ "saldo" => $dataToUpdateBMTPengirim ]);
                        $updateBMTSimpanan = BMT::where('id', $id_bmt_simpanan)->update([ "saldo" => $dataToUpdateBMTSimpanan ]);
                        $updateUser = User::where('id', $pengajuan->id_user)->update([ "wajib_pokok" => json_encode($dataToUpdateUsers) ]);
                        $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([
                            "status"    => "Sudah Dikonfirmasi",
                            "teller"    => Auth::user()->id
                        ]);
                        DB::commit();
                        $response = array("type" => "success", "message" => "Pengajuan " . $nama_rekening . " Berhasil Dikonfirmasi.");
                    }
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan " . $nama_rekening . " Gagal Dikonfirmasi.");
                }
            }
            // if($data->id_rekening_simpanan == 117)
            // {
            //     $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->pokok;
            // }
            // if($data->id_rekening_simpanan == 119)
            // {
            //     $saldo_awal_simpanan = json_decode($user_simpanan->wajib_pokok)->wajib;
            // }
                    

            // $response = $dataToPenyimpananWajibPokok;
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

    /** 
     * Bayar simpanan anggota
     * @return Response
    */
    public function paySimpanan($data)
    {
        DB::beginTransaction();
        try
        {
            $statement = DB::select("SHOW TABLE STATUS LIKE 'pengajuan'");
            $nextId = $statement[0]->Auto_increment;

            $user = User::where('id', $data->user)->first();

            if($data->debit == 0)
            {
                $debit = "Tunai";
                $daribank = null;
                $nobank = null;
                $atasnama = null;
                $pathbukti = null;
                $bank_tujuan_transfer = json_decode(Auth::user()->detail)->id_rekening;

                $bmt_bank_pengirim = BMT::where('id_rekening', $bank_tujuan_transfer)->first();
                $saldo_bmt_pengirim = $bmt_bank_pengirim->saldo;
                $id_bmt_bank_pengirim = $bmt_bank_pengirim->id;
            }
            if($data->debit == 1)
            {
                $file_name = $data->file->getClientOriginalName();
                $fileToUpload = time() . "-" . $file_name;
                $data->file('file')->storeAs(
                    'transfer/', $fileToUpload
                );
                $path_bukti_file = "storage/transfer/" . $fileToUpload;

                $debit = "Transfer";
                $daribank = $data->daribank;
                $nobank = $data->nobank;
                $atasnama = $data->atasnama;
                $pathbukti = $path_bukti_file;
                $bank_tujuan_transfer = $data->bank;

                $bmt_bank_pengirim = BMT::where('id_rekening', $bank_tujuan_transfer)->first();
                $saldo_bmt_pengirim = $bmt_bank_pengirim->saldo;
                $id_bmt_bank_pengirim = $bmt_bank_pengirim->id;
            }
            if($data->debit == 2)
            {
                $debit = "Tabungan";
                $daribank = null;
                $nobank = null;
                $atasnama = null;
                $pathbukti = null;
                $bank_tujuan_transfer = $data->dari_tabungan;

                $rekening_tabungan = Tabungan::where('id_tabungan', $bank_tujuan_transfer)->first();
                $saldo_tabungan_pengirim = json_decode($rekening_tabungan->detail)->saldo;
                $bmt_bank_pengirim = BMT::where('id_rekening', $rekening_tabungan->id_rekening)->first();
                $saldo_bmt_pengirim = $bmt_bank_pengirim->saldo;
                $id_bmt_bank_pengirim = $bmt_bank_pengirim->id;

                $dataToUpdateTabungan = [
                    "saldo" => $saldo_tabungan_pengirim - floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                    "id_pengajuan" => $nextId
                ];
            }

            if($data->id_rekening_simpanan == 119)
            {
                $nama_rekening = "Simpanan Wajib";
                $bmt_simpanan = BMT::where('id_rekening', 119)->first();
                $saldo_bmt_simpanan = $bmt_simpanan->saldo;
                $id_bmt_simpanan = $bmt_simpanan->id;
                $saldo_awal_simpanan_user = json_decode($user->wajib_pokok)->wajib;

                if(isset(json_decode($user->wajib_pokok)->margin))
                {
                    if(isset(json_decode($user->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($user->wajib_pokok)->khusus),
                            "margin"    => floatval(json_decode($user->wajib_pokok)->margin)
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok),
                            "khusus"     => 0,
                            "margin"    => floatval(json_decode($user->wajib_pokok)->margin)
                        ];
                    }
                }
                else
                {
                    if(isset(json_decode($user->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($user->wajib_pokok)->khusus),

                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok),
                            "khusus"     => 0
                        ];
                    }
                }
            }

            if($data->id_rekening_simpanan == 117)
            {
                $nama_rekening = "Simpanan Pokok";

                $bmt_simpanan = BMT::where('id_rekening', 117)->first();
                $saldo_bmt_simpanan = $bmt_simpanan->saldo;
                $id_bmt_simpanan = $bmt_simpanan->id;
                $saldo_awal_simpanan_user = json_decode($user->wajib_pokok)->pokok;

                if(isset(json_decode($user->wajib_pokok)->margin))
                {
                    if(isset(json_decode($user->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "khusus"     => floatval(json_decode($user->wajib_pokok)->khusus),
                            "margin"    => floatval(json_decode($user->wajib_pokok)->margin)
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "khusus"     => 0,
                            "margin"    => floatval(json_decode($user->wajib_pokok)->margin)
                        ];
                    }
                }
                else
                {
                    if(isset(json_decode($user->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "khusus"     => floatval(json_decode($user->wajib_pokok)->khusus),
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "khusus"     => 0
                        ];
                    }
                }
            } 

            if($data->id_rekening_simpanan == 120)
            {
                $nama_rekening = "Simpanan Khusus";

                $bmt_simpanan = BMT::where('id_rekening', 120)->first();
                $saldo_bmt_simpanan = $bmt_simpanan->saldo;
                $id_bmt_simpanan = $bmt_simpanan->id;
                $saldo_awal_simpanan_user = json_decode($user->wajib_pokok)->khusus;

                if(isset(json_decode($user->wajib_pokok)->khusus))
                {
                    $saldo_awal_simpanan = json_decode($user->wajib_pokok)->khusus;
                }
                else
                {
                    $saldo_awal_simpanan = 0;
                }

                if(isset(json_decode($user->wajib_pokok)->margin))
                {
                    if(isset(json_decode($user->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($saldo_awal_simpanan))  + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "margin"    => floatval(json_decode($user->wajib_pokok)->margin)
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok),
                            "khusus"     => 0,
                            "margin"    => floatval(json_decode($user->wajib_pokok)->margin)
                        ];
                    }
                }
                else
                {
                    if(isset(json_decode($user->wajib_pokok)->khusus))
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok),
                            "khusus"     => floatval(json_decode($saldo_awal_simpanan))  + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                        ];
                    }
                    else
                    {
                        $dataToUpdateUsers = [
                            "wajib"     => floatval(json_decode($user->wajib_pokok)->wajib),
                            "pokok"     => floatval(json_decode($user->wajib_pokok)->pokok),
                            "khusus"     => 0
                        ];
                    }
                }
            }

            $detailToPengajuan = [
                "daribank"  => $daribank,
                "no_bank"    => $nobank,
                "jenis"     => $debit,
                "id"        => $user->id,
                "nama"      => $user->nama,
                "atasnama"  => $atasnama,
                "bank_tujuan_transfer" => $bank_tujuan_transfer,
                "path_bukti"    => $pathbukti,
                "jumlah"    => floatval(preg_replace('/[^\d.]/', '', $data->nominal))
            ];
            $dataToPengajuan = [
                "id"                => $nextId,
                "id_user"           => $user->id,
                "id_rekening"       => $data->id_rekening_simpanan,
                "jenis_pengajuan"   => $nama_rekening,
                "status"            => "Sudah Dikonfirmasi",
                "kategori"          => $nama_rekening,
                "detail"            => $detailToPengajuan,
                "teller"            => Auth::user()->id
            ];

            $bmt_simpanan = BMT::where('id_rekening', $data->id_rekening_simpanan)->first(); // simpanan khusus
            $saldo_awal_bmt_simpanan = $bmt_simpanan->saldo;

            if($this->pengajuanReporsitory->createPengajuan($dataToPengajuan)["type"] == "success")
            {
                $detailToPenyimpananBMT = [
                    "jumlah"    => floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                    "saldo_awal"=> floatval($saldo_awal_bmt_simpanan),
                    "saldo_akhir" => floatval($saldo_awal_bmt_simpanan) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                    "id_pengajuan" => $nextId
                ];

                $dataToPenyimpananBMT = [
                    "id_user"   => $user->id,
                    "id_bmt"    => $id_bmt_simpanan,
                    "status"    => $nama_rekening,
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];
                
                if($this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT) == "success")
                {

                    if ($data->debit == 2)
                    {
                        $detailToPenyimpananBMT['saldo_awal'] = $saldo_bmt_pengirim;
                        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_bmt_pengirim - floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        $dataToPenyimpananBMT['id_bmt'] = $id_bmt_bank_pengirim;

                        $simpananBMTPengirim = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

//                        $bmt_teller = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first(); // kas teller
//                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_teller->saldo;
//                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_teller->saldo + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
//                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
//                        $dataToPenyimpananBMT['id_bmt'] = $bmt_teller->id;
//
//                        $simpananBMTPengirim = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $detailToPenyimpananTabungan = [
                            "teller"        => Auth::user()->id,
                            "dari_rekening"    => $bmt_bank_pengirim->nama,
                            "untuk_rekening"   => $bmt_simpanan->nama,
                            "jumlah"  => floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "saldo_awal"  => $saldo_tabungan_pengirim,
                            "saldo_akhir"  =>  $saldo_tabungan_pengirim - floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                        ];
                        $dataToPenyimpananTabungan = [
                            "id_user"   => $data->user,
                            "id_tabungan"    => $rekening_tabungan->id,
                            "status"    => "Pembayaran " . $nama_rekening,
                            "transaksi" => $detailToPenyimpananTabungan,
                            "teller"    => Auth::user()->id
                        ];

                        $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);

                    }
                    if ($data->debit == 1)
                    {
                        $detailToPenyimpananBMT['saldo_awal'] = $saldo_bmt_pengirim;
                        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_bmt_pengirim + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        $dataToPenyimpananBMT['id_bmt'] = $id_bmt_bank_pengirim;

                        $simpananBMTPengirim = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $bmt_teller = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first(); // kas teller
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_teller->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_teller->saldo + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_teller->id;

                        $simpananBMTPengirim = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);


                    }


                    if($data->debit == 0)
                    {
                        $bmt_teller = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first(); // kas teller
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_teller->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_teller->saldo + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_teller->id;

                        $simpananBMTPengirim = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                    }

                    $detailToPenyimpananWajibPokok = [
                        "teller"    => Auth::user()->id,
                        "dari_rekening" => $bmt_bank_pengirim->nama,
                        "untuk_rekening" => $bmt_simpanan->nama,
                        "jumlah"    => floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                        "saldo_awal" => $saldo_awal_simpanan_user,
                        "saldo_akhir" => floatval($saldo_awal_simpanan_user) + floatval(preg_replace('/[^\d.]/', '', $data->nominal))
                    ];
                    $dataToPenyimpananWajibPokok = [
                        "id_user"       => $user->id,
                        "id_rekening"   => $data->id_rekening_simpanan,
                        "status"        => $nama_rekening,
                        "transaksi"     => $detailToPenyimpananWajibPokok,
                        "teller"        => Auth::user()->id
                    ];

                    $penyimpananWajibPokok = $this->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);
                    if($penyimpananWajibPokok == "success")
                    {
                        if($data->debit == 2)
                        {
                            if($saldo_bmt_pengirim > floatval(preg_replace('/[^\d.]/', '', $data->nominal)))
                            {
                                $updateUser = User::where('id', $data->user)->update([ 'wajib_pokok' => json_encode($dataToUpdateUsers) ]);
                                $updateTabungan = Tabungan::where('id_tabungan', $data->dari_tabungan)->update([
                                    'detail' => json_encode($dataToUpdateTabungan)
                                ]);
                                $updateBMTPengirim = BMT::where('id', $bmt_bank_pengirim->id)->update([
                                    'saldo' => $saldo_bmt_pengirim - floatval(preg_replace('/[^\d.]/', '', $data->nominal))
                                ]);
                                $updateBMTSimpanan = BMT::where('id_rekening', $data->id_rekening_simpanan)->update([
                                    'saldo' => $saldo_bmt_simpanan + floatval(preg_replace('/[^\d.]/', '', $data->nominal))
                                ]);

                                DB::commit();
                                $response = array("type" => "success", "message" => "Pembayaran ". $nama_rekening . " berhasil.");
                            }
                            else
                            {
                                DB::rollback();
                                $response = array("type" => "error", "message" => "Pembayaran " . $nama_rekening . " gagal.");
                            }
                        }
                        else
                        {
                            $updateUser = User::where('id', $data->user)->update([ 'wajib_pokok' => json_encode($dataToUpdateUsers) ]);
                            $updateBMTPengirim = BMT::where('id', $bmt_bank_pengirim->id)->update([
                                'saldo' => $saldo_bmt_pengirim + floatval(preg_replace('/[^\d.]/', '', $data->nominal))
                            ]);
                            $updateBMTSimpanan = BMT::where('id_rekening', $data->id_rekening_simpanan)->update([
                                'saldo' => $saldo_bmt_simpanan + floatval(preg_replace('/[^\d.]/', '', $data->nominal))
                            ]);

                            DB::commit();
                            $response = array("type" => "success", "message" => "Pembayaran " . $nama_rekening . " berhasil.");
                        }
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pembayaran " . $nama_rekening . " gagal.");
                    }
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pembayaran " . $nama_rekening . " gagal.");
                }
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pembayaran " . $nama_rekening . " gagal.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pembayaran " . $nama_rekening . " gagal dilakukan");
        }

        return $response;
        
    }

    /** 
     * Riwayat simpanan anggota
     * @return Response
    */
    public function detailSimpanan($data, $limit="")
    {
        if($data == "khusus")
        {
            $jenis = "Simpanan Khusus";
        }
        if($data == "wajib")
        {
            $jenis = "Simpanan Wajib";
        }
        if($data == "pokok")
        {
            $jenis = "Simpanan Pokok";
        }

        if($limit != "")
        {
            $penyimpanan = PenyimpananWajibPokok::where([ ['status', $jenis], ['id_user', Auth::user()->id] ])->take($limit)->get();
        }
        else
        {
            $penyimpanan = PenyimpananWajibPokok::where([ ['status', $jenis], ['id_user', Auth::user()->id] ])->get();
        }

        return $penyimpanan;
    }
}