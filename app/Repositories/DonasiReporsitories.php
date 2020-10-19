<?php

namespace App\Repositories;

use App\BMT;
use App\Http\Controllers\HomeController;
use App\PenyimpananWakaf;
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
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Maal;
use App\Wakaf;
use App\PenyimpananMaal;
use App\PenyimpananBMT;
use App\User;
use Carbon\Carbon;

class DonasiReporsitories {

    public function __construct(
                                PengajuanReporsitories $pengajuanReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                RekeningReporsitories $rekeningReporsitory
                                )
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
    }

    /** 
     * Mengirim pengajuan donasi
     * @return Response 
    */
    public function sendDonasi($data)
    {
        DB::beginTransaction();
        try
        {
            // Get metode pembayaran (Transfer or tabungan)
            if($data->debit == 1) {
                $file = $data->file->getClientOriginalName();
                $file_name = preg_replace('/\s+/', '_', $file);
                $fileToUpload = time() . "-" . $file_name;
                $data->file('file')->storeAs(
                    'transfer', $fileToUpload
                );

                $debit = "Transfer"; 
                $path_bukti = $fileToUpload;
                $rekening = null;
                $atasnama = $data->atas_nama;
                $namabank = $data->nama_bank;
                $norek = $data->nomor_rekening;
                $bank_tujuan_transfer = $data->bank_tujuan;
            } else {
                $debit = "Tabungan";
                $path_bukti = null;
                $rekening = $data->rekening;
                $atasnama = null;
                $namabank = null;
                $norek = null;
                $bank_tujuan_transfer = null;
            }

            // Get jenis donasi (donasi kegiatan/maal, zis, wakaf)
            if($data->jenis_donasi == 'donasi kegiatan')
            {
                $jenis_pengajuan = "Donasi Kegiatan";
                $id_rekening = 113;
            }
            if($data->jenis_donasi == 'zis')
            {
                $jenis_pengajuan = "ZIS";
                $id_rekening = 112;
            }
            if($data->jenis_donasi == 'wakaf')
            {
                $jenis_pengajuan = "Wakaf";
                $id_rekening = 114;
            }

            if(Auth::check())
            {
                $id = Auth::user()->id;
                $nama = Auth::user()->nama;
                $email = "";
            }
            else
            {
                $user = User::where('no_ktp', 9999999999999999)->first();
                $id = $user->id;
                $nama = $data->namaPendonasi;
                $email = $data->email;
            }


                $detail = [
                    'id_maal'   => $data->id_donasi,
                    'jenis_donasi'    => $data->jenis_donasi,
                    'id'        => $id,
                    'nama'      => $nama,
                    'debit'     => $debit,
                    'path_bukti'=> $path_bukti,
                    'jumlah'    => preg_replace('/[^\d.]/', '', $data->nominal),
                    'rekening'  => $rekening,
                    'atasnama'  => $atasnama,
                    'bank'      => $namabank,
                    'no_bank'   => $norek,
                    'bank_tujuan_transfer' => $bank_tujuan_transfer,
                    'email' =>  $email
                ];

                $dataToSave = [
                    'id_user'           => $id,
                    'id_rekening'       => $id_rekening,
                    'jenis_pengajuan'   => $jenis_pengajuan,
                    'status'            => 'Menunggu Konfirmasi',
                    'kategori'          => 'Donasi',
                    'detail'            => $detail,
                    'teller'            => 0
                ];



            
            $pengajuan = $this->pengajuanReporsitory->createPengajuan($dataToSave);
            if($pengajuan['type'] == "success")
            {
                DB::commit();
                $response = array("type" => "success", "message" => "Pengajuan Donasi berhasil dibuat");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Donasi gagal dibuat");
        }

        return $response;
    }

    /** 
     * Get all pengajuan donasi
     * @return Response
    */
    public function getPengajuanDonasi($type="", $user="")
    {
        $pengajuanDonasi = Pengajuan::where('jenis_pengajuan', 'Donasi Kegiatan')
                                    ->orWhere('jenis_pengajuan', 'ZIS')
                                    ->orWhere('jenis_pengajuan', 'Wakaf')
                                    ->get();

        if($type != "" && $user != "")
        {
            $pengajuanDonasi = Pengajuan::where([ ['kategori', 'Donasi'], ['jenis_pengajuan', $type], ['id_user', $user] ])->get();
        }
        if($type != "")
        {
            $pengajuanDonasi = Pengajuan::where([ ['kategori', 'Donasi'], ['jenis_pengajuan', $type] ])->get();
        }



        return $pengajuanDonasi;
    }

    /** 
     * Confirm pengajuan donasi
     * @return Response
    */
    public function confirmDonasi($data)
    {
        DB::beginTransaction();
        try {
            $pengajuan = Pengajuan::where('id', $data->id_)->first();
            $bmt_donasi = BMT::where('id_rekening', $pengajuan->id_rekening)->select(['id', 'saldo', 'nama'])->first();
            $saldo_awal_donasi = $bmt_donasi->saldo;
            $saldo_akhir_donasi = $bmt_donasi->saldo + json_decode($pengajuan->detail)->jumlah; // checker

            if(json_decode($pengajuan->detail)->id_maal != null) {
                $kegiatan_maal = Maal::where('id', json_decode($pengajuan->detail)->id_maal)->first();
                $saldo_awal_kegiatan = json_decode($kegiatan_maal->detail)->terkumpul;
                $saldo_awal_kegiatan_penyimpanan_maal = json_decode($kegiatan_maal->detail)->sisa;
                $saldo_akhir_kegiatan_penyimpanan_maal = $saldo_awal_kegiatan_penyimpanan_maal + json_decode($pengajuan->detail)->jumlah;
                $sisa_awal = json_decode($kegiatan_maal->detail)->sisa;
                $saldo_akhir_kegiatan = $saldo_awal_kegiatan + json_decode($pengajuan->detail)->jumlah;
                $jumlah_donasi = json_decode($pengajuan->detail)->jumlah;

                $detail_maal_update = [
                    "detail"    => json_decode($kegiatan_maal->detail)->detail,
                    "dana"      => json_decode($kegiatan_maal->detail)->dana,
                    "terkumpul" => $saldo_awal_kegiatan + $jumlah_donasi,
                    "sisa" => $sisa_awal + $jumlah_donasi,
                    "path_poster" => json_decode($kegiatan_maal->detail)->path_poster
                ];
            }
            else
            {
                $saldo_awal_kegiatan = $bmt_donasi->saldo;
                $saldo_akhir_kegiatan = $bmt_donasi->saldo + json_decode($pengajuan->detail)->jumlah;
            }

            // Update saldo rekening pengirim di bmt
            // Execute for pengajuan via tabungan
            if(json_decode($pengajuan->detail)->debit == "Tabungan") {
                $tabungan_pengirim = Tabungan::where('id_tabungan', json_decode($pengajuan->detail)->rekening)->first();
                $rekening_pengirim = Rekening::where([ ['nama_rekening', $tabungan_pengirim->jenis_tabungan], ['tipe_rekening', 'detail'] ])->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                $saldo_awal_pengirim = $bmt_pengirim->saldo;
                $saldo_akhir_pengirim = $bmt_pengirim->saldo - json_decode($pengajuan->detail)->jumlah;

                $detailToPenyimpananTabungan = [
                    "teller"    => Auth::user()->id,
                    "dari_rekening" => $tabungan_pengirim->jenis_tabungan,
                    "untuk_rekening" => $bmt_donasi->nama,
                    "jumlah" => json_decode($pengajuan->detail)->jumlah,
                    "saldo_awal" => json_decode($tabungan_pengirim->detail)->saldo,
                    "saldo_akhir" => floatval(json_decode($tabungan_pengirim->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah)
                ];
                $dataToPenyimpananTabungan = [
                    "id_user"       => json_decode($pengajuan->detail)->id,
                    "id_tabungan"   => $tabungan_pengirim->id,
                    "status"        => "Pembayaran " . $bmt_donasi->nama,
                    "transaksi"     => $detailToPenyimpananTabungan,
                    "teller"        => Auth::user()->id
                ];

                    
                if(floatval($bmt_pengirim->saldo) > floatval(json_decode($pengajuan->detail)->jumlah))
                {
                    $saldo_bmt_pengirim = floatval($bmt_pengirim->saldo) - floatval(json_decode($pengajuan->detail)->jumlah);
                    $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_bmt_pengirim]);
                        
                    $update_saldo_pengirim_response = "success";
                    $saveToPenyimpananTabungan = $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                }
                else
                {
                    $update_saldo_pengirim_response = "error";
                }
            }
            
            // Update saldo rekening pengirim di bmt
            // Execute for pengajuan via transfer
            if(json_decode($pengajuan->detail)->bank_tujuan_transfer != null) {
                $rekening_pengirim = Rekening::where('id', json_decode($pengajuan->detail)->bank_tujuan_transfer)->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                $saldo_bmt_pengirim = floatval($bmt_pengirim->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_bmt_pengirim]);

                $saldo_awal_pengirim = $bmt_pengirim->saldo;
                $saldo_akhir_pengirim = $bmt_pengirim->saldo + json_decode($pengajuan->detail)->jumlah;

                $update_saldo_pengirim_response = "success";
            }

            if(json_decode($pengajuan->detail)->id_maal != null)
            {
                $detailToPenyimpananMaal = [
                    'id_maal'   => json_decode($pengajuan->detail)->id_maal,
                    'jenis_donasi'  => json_decode($pengajuan->detail)->jenis_donasi,
                    'id'        => json_decode($pengajuan->detail)->id,
                    'nama'      => json_decode($pengajuan->detail)->nama,
                    'email'     =>json_decode($pengajuan->detail)->email,
                    'debit'     => json_decode($pengajuan->detail)->debit,
                    'path_bukti'=> json_decode($pengajuan->detail)->path_bukti,
                    'jumlah'    => json_decode($pengajuan->detail)->jumlah,
                    'rekening'  => json_decode($pengajuan->detail)->rekening,
                    'atasnama'  => json_decode($pengajuan->detail)->atasnama,
                    'bank'      => json_decode($pengajuan->detail)->bank,
                    'no_bank'   => json_decode($pengajuan->detail)->no_bank,
                    'bank_tujuan_transfer' => json_decode($pengajuan->detail)->bank_tujuan_transfer,
                    'saldo_awal' => $saldo_awal_kegiatan_penyimpanan_maal,
                    'saldo_akhir'   => $saldo_akhir_kegiatan_penyimpanan_maal,
                ];
            }
            else
            {
                $detailToPenyimpananMaal = [
                    'id_maal'   => json_decode($pengajuan->detail)->id_maal,
                    'jenis_donasi'  => json_decode($pengajuan->detail)->jenis_donasi,
                    'id'        => json_decode($pengajuan->detail)->id,
                    'nama'      => json_decode($pengajuan->detail)->nama,
                    'email'     =>json_decode($pengajuan->detail)->email,
                    'debit'     => json_decode($pengajuan->detail)->debit,
                    'path_bukti'=> json_decode($pengajuan->detail)->path_bukti,
                    'jumlah'    => json_decode($pengajuan->detail)->jumlah,
                    'rekening'  => json_decode($pengajuan->detail)->rekening,
                    'atasnama'  => json_decode($pengajuan->detail)->atasnama,
                    'bank'      => json_decode($pengajuan->detail)->bank,
                    'no_bank'   => json_decode($pengajuan->detail)->no_bank,
                    'bank_tujuan_transfer' => json_decode($pengajuan->detail)->bank_tujuan_transfer,
                    'saldo_awal' => $saldo_awal_kegiatan,
                    'saldo_akhir'   => $saldo_akhir_kegiatan,
                ];
            }


            
            if(json_decode($pengajuan->detail)->id_maal != null) {
                $maal = Maal::where('id', json_decode($pengajuan->detail)->id_maal)->first();
                $dana_terkumpul = json_decode($maal->detail)->terkumpul;
                $detailToPenyimpananMaal['dana_terkumpul_awal'] = $saldo_awal_kegiatan;
                $detailToPenyimpananMaal['dana_terkumpul_akhir'] = $saldo_akhir_kegiatan;
                $detailToPenyimpananMaal['dari_rekening'] = $bmt_pengirim->nama;
                $detailToPenyimpananMaal['untuk_rekening'] = $kegiatan_maal->nama_kegiatan;
            }

            $dataToInsertIntoPenyimpananMaal = [
                'id_donatur'    => $pengajuan->id_user,
                'id_maal'       => json_decode($pengajuan->detail)->id_maal,
                'status'        => json_decode($pengajuan->detail)->debit,
                'transaksi'     => $detailToPenyimpananMaal,
                'teller'        => Auth::user()->id
            ];

            $detailToPenyimpananBMT = [
                "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal"    => $saldo_awal_donasi,
                "saldo_akhir"   => $saldo_akhir_donasi,
                "id_pengajuan"  => $pengajuan->id
            ];
            $dataToInsertIntoPenyimpananBMT = [
                'id_user'       => $pengajuan->id_user,
                'id_bmt'        => $bmt_donasi->id,
                'status'        => json_decode($pengajuan->detail)->jenis_donasi,
                'transaksi'     => $detailToPenyimpananBMT,
                'teller'        => Auth::user()->id
            ];

            // This is for donasi kegiatan action 
            if($update_saldo_pengirim_response == "success")
            {
                if(json_decode($pengajuan->detail)->id_maal != null) {
                    
                    $penyimpananMaal = $this->insertPenyimpananMaal($dataToInsertIntoPenyimpananMaal);

                    if($penyimpananMaal['type'] == 'success') {
                        
                        $penyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);
                        
                        $dataToInsertIntoPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                        $detailToPenyimpananBMT['jumlah'] = json_decode($pengajuan->detail)->jumlah;
                        $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                        $dataToInsertIntoPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $penyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                        $detail_pengajuan = json_decode($pengajuan->detail);

                        // Update donasi maal dana terkumpul
                        $update_dana_terkumpul = Maal::where('id', json_decode($pengajuan->detail)->id_maal)->update([ 'detail' => json_encode($detail_maal_update) ]);

                        // update saldo in bmt table
                        $update_saldo_bmt = BMT::where('id', $bmt_donasi->id)->update([ 'saldo' => floatval($bmt_donasi->saldo) + floatval($detail_pengajuan->jumlah) ]);

                        if($detail_pengajuan->debit == "Tabungan") {
                            $dataToCheckInTabungan = [
                                'id_tabungan' => $detail_pengajuan->rekening,
                                'jumlah'      => $detail_pengajuan->jumlah,
                                'id_pengajuan' => $data->id_
                            ];
                            $this->updateSaldoTabungan($dataToCheckInTabungan);
                        }
                    }
                }

                // This is for other donasi action
                else {
                    $penyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                    $dataToInsertIntoPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                    $detailToPenyimpananBMT['jumlah'] = json_decode($pengajuan->detail)->jumlah;
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                    $dataToInsertIntoPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $penyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                    $detail_pengajuan = json_decode($pengajuan->detail);
                    
                    // update saldo in bmt table
                    $update_saldo_bmt = BMT::where('id', $bmt_donasi->id)->update([ 'saldo' => floatval($bmt_donasi->saldo) + floatval($detail_pengajuan->jumlah) ]);

                    if($detail_pengajuan->debit == "Tabungan") {
                        $dataToCheckInTabungan = [
                            'id_tabungan' => $detail_pengajuan->rekening,
                            'jumlah'      => $detail_pengajuan->jumlah,
                            'id_pengajuan' => $data->id_
                        ];
                        $this->updateSaldoTabungan($dataToCheckInTabungan);
                    }
                }

                // Update data pengajuan
                $update_pengajuan = Pengajuan::where('id', $data->id_)->update([
                    "status"        => "Sudah Dikonfirmasi",
                    "teller"        => Auth::user()->id
                ]);

                DB::commit();

                $response = array("type" => "success", "message" => "Pengajuan Donasi Maal Berhasil Dikonfirmasi");
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Donasi Maal Gagal Dikonfirmasi. Saldo Tabungan Tidak Cukup");
            }
        }
        catch(Exception $e) {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Donasi Maal Gagal Dikonfirmasi");
        }

        return $response;
    }



    public function confirmDonasiWakaf($data)
    {
        DB::beginTransaction();
        try {
            $pengajuan = Pengajuan::where('id', $data->id_)->first();
            $bmt_donasi = BMT::where('id_rekening', $pengajuan->id_rekening)->select(['id', 'saldo', 'nama'])->first();
            $saldo_awal_donasi = $bmt_donasi->saldo;
            $saldo_akhir_donasi = $bmt_donasi->saldo + json_decode($pengajuan->detail)->jumlah; // checker

            if(json_decode($pengajuan->detail)->id_wakaf != null) {
                $kegiatan_wakaf = Wakaf::where('id', json_decode($pengajuan->detail)->id_wakaf)->first();
                $saldo_awal_kegiatan = json_decode($kegiatan_wakaf->detail)->terkumpul;
                $saldo_awal_kegiatan_penyimpanan_wakaf= json_decode($kegiatan_wakaf->detail)->sisa;
                $saldo_akhir_kegiatan_penyimpanan_wakaf = $saldo_awal_kegiatan_penyimpanan_wakaf + json_decode($pengajuan->detail)->jumlah;
                $sisa_awal = json_decode($kegiatan_wakaf->detail)->sisa;
                $saldo_akhir_kegiatan = $saldo_awal_kegiatan + json_decode($pengajuan->detail)->jumlah;
                $jumlah_donasi = json_decode($pengajuan->detail)->jumlah;

                $detail_wakaf_update = [
                    "detail"    => json_decode($kegiatan_wakaf->detail)->detail,
                    "dana"      => json_decode($kegiatan_wakaf->detail)->dana,
                    "terkumpul" => $saldo_awal_kegiatan + $jumlah_donasi,
                    "sisa" => $sisa_awal + $jumlah_donasi,
                    "path_poster" => json_decode($kegiatan_wakaf->detail)->path_poster
                ];
            }
            else
            {
                $saldo_awal_kegiatan = $bmt_donasi->saldo;
                $saldo_akhir_kegiatan = $bmt_donasi->saldo + json_decode($pengajuan->detail)->jumlah;
            }

            // Update saldo rekening pengirim di bmt
            // Execute for pengajuan via tabungan
            if(json_decode($pengajuan->detail)->debit == "Tabungan") {
                $tabungan_pengirim = Tabungan::where('id_tabungan', json_decode($pengajuan->detail)->rekening)->first();
                $rekening_pengirim = Rekening::where([ ['nama_rekening', $tabungan_pengirim->jenis_tabungan], ['tipe_rekening', 'detail'] ])->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                $saldo_awal_pengirim = $bmt_pengirim->saldo;
                $saldo_akhir_pengirim = $bmt_pengirim->saldo - json_decode($pengajuan->detail)->jumlah;

                $detailToPenyimpananTabungan = [
                    "teller"    => Auth::user()->id,
                    "dari_rekening" => $tabungan_pengirim->jenis_tabungan,
                    "untuk_rekening" => $bmt_donasi->nama,
                    "jumlah" => json_decode($pengajuan->detail)->jumlah,
                    "saldo_awal" => json_decode($tabungan_pengirim->detail)->saldo,
                    "saldo_akhir" => floatval(json_decode($tabungan_pengirim->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah)
                ];
                $dataToPenyimpananTabungan = [
                    "id_user"       => json_decode($pengajuan->detail)->id,
                    "id_tabungan"   => $tabungan_pengirim->id,
                    "status"        => "Pembayaran " . $bmt_donasi->nama,
                    "transaksi"     => $detailToPenyimpananTabungan,
                    "teller"        => Auth::user()->id
                ];


                if(floatval($bmt_pengirim->saldo) > floatval(json_decode($pengajuan->detail)->jumlah))
                {
                    $saldo_bmt_pengirim = floatval($bmt_pengirim->saldo) - floatval(json_decode($pengajuan->detail)->jumlah);
                    $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_bmt_pengirim]);

                    $update_saldo_pengirim_response = "success";
                    $saveToPenyimpananTabungan = $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                }
                else
                {
                    $update_saldo_pengirim_response = "error";
                }
            }

            // Update saldo rekening pengirim di bmt
            // Execute for pengajuan via transfer
            if(json_decode($pengajuan->detail)->bank_tujuan_transfer != null) {
                $rekening_pengirim = Rekening::where('id', json_decode($pengajuan->detail)->bank_tujuan_transfer)->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                $saldo_bmt_pengirim = floatval($bmt_pengirim->saldo) + floatval(json_decode($pengajuan->detail)->jumlah);
                $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_bmt_pengirim]);

                $saldo_awal_pengirim = $bmt_pengirim->saldo;
                $saldo_akhir_pengirim = $bmt_pengirim->saldo + json_decode($pengajuan->detail)->jumlah;

                $update_saldo_pengirim_response = "success";
            }

            $detailToPenyimpananWakaf = [
                'id_wakaf'   => json_decode($pengajuan->detail)->id_wakaf,
                'jenis_donasi'  => json_decode($pengajuan->detail)->jenis_donasi,
                'id'        => json_decode($pengajuan->detail)->id,
                'nama'      => json_decode($pengajuan->detail)->nama,
                'email'     =>json_decode($pengajuan->detail)->email,
                'debit'     => json_decode($pengajuan->detail)->debit,
                'path_bukti'=> json_decode($pengajuan->detail)->path_bukti,
                'jumlah'    => json_decode($pengajuan->detail)->jumlah,
                'rekening'  => json_decode($pengajuan->detail)->rekening,
                'atasnama'  => json_decode($pengajuan->detail)->atasnama,
                'bank'      => json_decode($pengajuan->detail)->bank,
                'no_bank'   => json_decode($pengajuan->detail)->no_bank,
                'bank_tujuan_transfer' => json_decode($pengajuan->detail)->bank_tujuan_transfer,
                'saldo_awal' => $saldo_awal_kegiatan_penyimpanan_wakaf,
                'saldo_akhir'   => $saldo_akhir_kegiatan_penyimpanan_wakaf,
            ];

            if(json_decode($pengajuan->detail)->id_wakaf != null) {
                $wakaf = Wakaf::where('id', json_decode($pengajuan->detail)->id_wakaf)->first();
                $dana_terkumpul = json_decode($wakaf->detail)->terkumpul;
                $detailToPenyimpananWakaf['dana_terkumpul_awal'] = $saldo_awal_kegiatan;
                $detailToPenyimpananWakaf['dana_terkumpul_akhir'] = $saldo_akhir_kegiatan;
                $detailToPenyimpananWakaf['dari_rekening'] = $bmt_pengirim->nama;
                $detailToPenyimpananWakaf['untuk_rekening'] = $kegiatan_wakaf->nama_kegiatan;
            }

            $dataToInsertIntoPenyimpananWakaf = [
                'id_donatur'    => $pengajuan->id_user,
                'id_wakaf'       => json_decode($pengajuan->detail)->id_wakaf,
                'status'        => json_decode($pengajuan->detail)->debit,
                'transaksi'     => $detailToPenyimpananWakaf,
                'teller'        => Auth::user()->id
            ];

            $detailToPenyimpananBMT = [
                "jumlah"        => json_decode($pengajuan->detail)->jumlah,
                "saldo_awal"    => $saldo_awal_donasi,
                "saldo_akhir"   => $saldo_akhir_donasi,
                "id_pengajuan"  => $pengajuan->id
            ];
            $dataToInsertIntoPenyimpananBMT = [
                'id_user'       => $pengajuan->id_user,
                'id_bmt'        => $bmt_donasi->id,
                'status'        => json_decode($pengajuan->detail)->jenis_donasi,
                'transaksi'     => $detailToPenyimpananBMT,
                'teller'        => Auth::user()->id
            ];

            // This is for donasi kegiatan action
            if($update_saldo_pengirim_response == "success")
            {
                if(json_decode($pengajuan->detail)->id_wakaf != null) {

                    $penyimpananWakaf = $this->insertPenyimpananWakaf($dataToInsertIntoPenyimpananWakaf); // check here

                    if($penyimpananWakaf['type'] == 'success') {

                        $penyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                        $dataToInsertIntoPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                        $detailToPenyimpananBMT['jumlah'] = json_decode($pengajuan->detail)->jumlah;
                        $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                        $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                        $dataToInsertIntoPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                        $penyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                        $detail_pengajuan = json_decode($pengajuan->detail);

                        // Update donasi maal dana terkumpul
                        $update_dana_terkumpul = Wakaf::where('id', json_decode($pengajuan->detail)->id_wakaf)->update([ 'detail' => json_encode($detail_wakaf_update) ]);

                        // update saldo in bmt table
                        $update_saldo_bmt = BMT::where('id', $bmt_donasi->id)->update([ 'saldo' => floatval($bmt_donasi->saldo) + floatval($detail_pengajuan->jumlah) ]);

                        if($detail_pengajuan->debit == "Tabungan") {
                            $dataToCheckInTabungan = [
                                'id_tabungan' => $detail_pengajuan->rekening,
                                'jumlah'      => $detail_pengajuan->jumlah,
                                'id_pengajuan' => $data->id_
                            ];
                            $this->updateSaldoTabungan($dataToCheckInTabungan);
                        }
                    }
                }

                // This is for other donasi action
                else {
                    $penyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                    $dataToInsertIntoPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                    $detailToPenyimpananBMT['jumlah'] = json_decode($pengajuan->detail)->jumlah;
                    $detailToPenyimpananBMT['saldo_awal'] = $saldo_awal_pengirim;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_pengirim;
                    $dataToInsertIntoPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $penyimpananBMT = $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                    $detail_pengajuan = json_decode($pengajuan->detail);

                    // update saldo in bmt table
                    $update_saldo_bmt = BMT::where('id', $bmt_donasi->id)->update([ 'saldo' => floatval($bmt_donasi->saldo) + floatval($detail_pengajuan->jumlah) ]);

                    if($detail_pengajuan->debit == "Tabungan") {
                        $dataToCheckInTabungan = [
                            'id_tabungan' => $detail_pengajuan->rekening,
                            'jumlah'      => $detail_pengajuan->jumlah,
                            'id_pengajuan' => $data->id_
                        ];
                        $this->updateSaldoTabungan($dataToCheckInTabungan);
                    }
                }

                // Update data pengajuan
                $update_pengajuan = Pengajuan::where('id', $data->id_)->update([
                    "status"        => "Sudah Dikonfirmasi",
                    "teller"        => Auth::user()->id
                ]);

                DB::commit();

                $response = array("type" => "success", "message" => "Pengajuan Donasi Wakaf Berhasil Dikonfirmasi");
            }
            else
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Donasi Wakaf Gagal Dikonfirmasi. Saldo Tabungan Tidak Cukup");
            }
        }
        catch(Exception $e) {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Donasi Wakaf Gagal Dikonfirmasi");
        }

        return $response;
    }

    /** 
     * Insert data to penyimpanan maal for history
     * @return Response
    */
    public function insertPenyimpananMaal($data)
    {
        $penyimpanan = new PenyimpananMaal();
        $penyimpanan->id_donatur = $data['id_donatur'];
        $penyimpanan->id_maal = $data['id_maal'];
        $penyimpanan->status = $data['status'];
        $penyimpanan->transaksi = json_encode($data['transaksi']);
        $penyimpanan->teller = $data['teller'];
        
        if($penyimpanan->save())
        {
            $response = array("type" => "success", "message" => "Pengajuan Donasi Maal Berhasil Dikonfirmasi");
        }
        else
        {
            $response = array("type" => "error", "message" => "Pengajuan Donasi Maal Gagal Dikonfirmasi");
        }

        return $response;
    }

    //Wakaf
    public function insertPenyimpananWakaf($data)
    {
        $penyimpanan = new PenyimpananWakaf();
        $penyimpanan->id_donatur = $data['id_donatur'];
        $penyimpanan->id_wakaf = $data['id_wakaf'];
        $penyimpanan->status = $data['status'];
        $penyimpanan->transaksi = json_encode($data['transaksi']);
        $penyimpanan->teller = $data['teller'];

        if($penyimpanan->save())
        {
            $response = array("type" => "success", "message" => "Pengajuan Donasi Wakaf Berhasil Dikonfirmasi");
        }
        else
        {
            $response = array("type" => "error", "message" => "Pengajuan Donasi Wakaf Gagal Dikonfirmasi");
        }

        return $response;
    }
    public function sendDonasiWakaf($data)
    {
        DB::beginTransaction();
        try
        {
            // Get metode pembayaran (Transfer or tabungan)
            if($data->debit == 1) {
                $file = $data->file->getClientOriginalName();
                $file_name = preg_replace('/\s+/', '_', $file);
                $fileToUpload = time() . "-" . $file_name;
                $data->file('file')->storeAs(
                    'transfer', $fileToUpload
                );

                $debit = "Transfer";
                $path_bukti = $fileToUpload;
                $rekening = null;
                $atasnama = $data->atas_nama;
                $namabank = $data->nama_bank;
                $norek = $data->nomor_rekening;
                $bank_tujuan_transfer = $data->bank_tujuan;
            } else {
                $debit = "Tabungan";
                $path_bukti = null;
                $rekening = $data->rekening;
                $atasnama = null;
                $namabank = null;
                $norek = null;
                $bank_tujuan_transfer = null;
            }

                $jenis_pengajuan = "Wakaf";
                $id_rekening = 114;

                if(Auth::check())
                {
                    $id = Auth::user()->id;
                    $nama = Auth::user()->nama;
                    $email = "";
                }
                else
                {
                    $user = User::where('no_ktp', 9999999999999999)->first();
                    $id = $user->id;
                    $nama = $data->namaPendonasi;
                    $email = $data->email;
                }


                $detail = [
                    'id_wakaf'   => $data->id_donasi_wakaf,
                    'jenis_donasi'    => $data->jenis_donasi_wakaf,
                    'id'        => $id,
                    'nama'      => $nama,
                    'debit'     => $debit,
                    'path_bukti'=> $path_bukti,
                    'jumlah'    => preg_replace('/[^\d.]/', '', $data->nominal),
                    'rekening'  => $rekening,
                    'atasnama'  => $atasnama,
                    'bank'      => $namabank,
                    'no_bank'   => $norek,
                    'bank_tujuan_transfer' => $bank_tujuan_transfer,
                    'email' =>  $email
                ];

                $dataToSave = [
                    'id_user'           => $id,
                    'id_rekening'       => $id_rekening,
                    'jenis_pengajuan'   => $jenis_pengajuan,
                    'status'            => 'Menunggu Konfirmasi',
                    'kategori'          => 'Donasi',
                    'detail'            => $detail,
                    'teller'            => 0
                ];


            $pengajuan = $this->pengajuanReporsitory->createPengajuan($dataToSave);
            if($pengajuan['type'] == "success")
            {
                DB::commit();
                $response = array("type" => "success", "message" => "Pengajuan Wakaf berhasil dibuat");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Wakaf gagal dibuat");
        }

        return $response;
    }

    /** 
     * Update saldo in tabungan
     * Execute it when jenis pembayaran donasi is tabungan
     * @return Null
    */
    public function updateSaldoTabungan($data)
    {
        $tabungan = Tabungan::where('id_tabungan', $data['id_tabungan'])->first();
        $saldo = json_decode($tabungan->detail)->saldo;

        $dataToUpdateTabungan = [
            "saldo" => $saldo - $data['jumlah'],
            "id_pengajuan" => $data['id_pengajuan']
        ];

        $update_tabungan = Tabungan::where('id_tabungan', $data['id_tabungan'])->update([ 'detail' => json_encode($dataToUpdateTabungan) ]);
        
    }

    /** 
     * Get all donasi from specific user
     * @return Response
    */
    public function getUserDonasi($user_id, $jenis_donasi="")
    {
        $donasi = PenyimpananMaal::where('id_donatur', $user_id)->get();

        if($jenis_donasi != "" && $jenis_donasi == 'donasi kegiatan') {
            $donasi = PenyimpananMaal::where([ ['id_donatur', $user_id], ['status', $jenis_donasi] ])->get();
        }
        else {
            $donasi = Pengajuan::where([ ['id_user', $user_id], ['jenis_pengajuan', $jenis_donasi] ])->get();
        }
        
        return $donasi;
    }

    /** 
     * Pay donasi from teller page
     * @return Response
    */
    public function payDonasi($data)
    {

        DB::beginTransaction();
        try 
        {
            $statement = DB::select("SHOW TABLE STATUS LIKE 'pengajuan'");
            $nextId = $statement[0]->Auto_increment;

            $donatur = User::where('id', $data->donatur)->first();

            if($data->debit == 1) {
                $file = $data->file->getClientOriginalName();
                $file_name = preg_replace('/\s+/', '_', $file);
                $fileToUpload = time() . "-" . $file_name;
                $data->file('file')->storeAs(
                    'transfer', $fileToUpload
                );

                $debit = "Transfer"; 
                $path_bukti = $fileToUpload;
                $rekening = null;
                $atasnama = $data->atas_nama;
                $namabank = $data->nama_bank;
                $norek = $data->nomor_rekening;
                $bank_tujuan_transfer = $data->bank_tujuan;
            }

            if($data->debit == 2) {
                $debit = "Tabungan";
                $path_bukti = null;
                $rekening = $data->rekening;
                $atasnama = null;
                $namabank = null;
                $norek = null;
                $bank_tujuan_transfer = null;

                $tabungan_pengirim = Tabungan::where('id_tabungan', $data->rekening)->first();
            }

            if($data->debit == 0) {
                $debit = "Tunai";
                $path_bukti = null;
                $rekening = $data->rekening;
                $atasnama = null;
                $namabank = null;
                $norek = null;
                $bank_tujuan_transfer = null;
            }


            // Get jenis donasi (donasi kegiatan/maal, zis, wakaf)
            if($data->jenis_donasi == 'donasi kegiatan')
            {
                $jenis_pengajuan = "Donasi Kegiatan";
                $id_rekening = 113;
            }
            if($data->jenis_donasi == 'zis')
            {
                $jenis_pengajuan = "ZIS";
                $id_rekening = 112;
            }
            if($data->jenis_donasi == 'wakaf')
            {
                $jenis_pengajuan = "Wakaf";
                $id_rekening = 114;
            }

            $bmt_donasi = BMT::where('id_rekening', $id_rekening)->first();
            $saldo_awal_donasi = $bmt_donasi->saldo;
            $saldo_akhir_donasi = $bmt_donasi->saldo + preg_replace('/[^\d.]/', '', $data->nominal);

            if($data->debit == 2) {     // Pembayaran VIA Tabungan                
                $tabungan_pengirim = Tabungan::where('id_tabungan', $data->rekening)->first();
                $rekening_pengirim = Rekening::where([ ['nama_rekening', $tabungan_pengirim->jenis_tabungan], ['tipe_rekening', 'detail'] ])->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
            }
            
            if($bank_tujuan_transfer != null) { // Pembayaran VIA Transfer
                $rekening_pengirim = Rekening::where('id', $data->bank_tujuan)->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
            }

            if($data->debit == 0) { // Pembayaran VIA Tunai
                $rekening_pengirim = Rekening::where('id', json_decode(Auth::user()->detail)->id_rekening)->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
            }

            $detail = [
                'id_maal'   => $data->id_donasi,
                'jenis_donasi'    => $data->jenis_donasi,
                'id'        => $donatur->id,
                'nama'      => $donatur->nama,
                'debit'     => $debit,
                'path_bukti'=> $path_bukti,
                'jumlah'    => preg_replace('/[^\d.]/', '', $data->nominal),
                'rekening'  => $rekening,
                'atasnama'  => $atasnama,
                'bank'      => $namabank,
                'no_bank'   => $norek,
                'bank_tujuan_transfer' => $bank_tujuan_transfer,
                'saldo_awal' => $saldo_awal_donasi,
                'saldo_akhir'   => $saldo_akhir_donasi,
                'dari_rekening' => $bmt_pengirim->nama,
                'untuk_rekening' => $bmt_donasi->nama
            ];

            if($data->jenis_donasi == 'donasi kegiatan')
            {
                $kegiatan_maal_didonasi = Maal::where('id', $data->id_donasi)->first();
                $detail['dana_terkumpul_awal'] = floatval(json_decode($kegiatan_maal_didonasi->detail)->terkumpul);
                $detail['dana_terkumpul_akhir'] = floatval(json_decode($kegiatan_maal_didonasi->detail)->terkumpul) + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                $detail['dari_rekening'] = $bmt_pengirim->nama;
                $detail['untuk_rekening'] = $kegiatan_maal_didonasi->nama_kegiatan;
                $detail['saldo_awal'] = floatval(json_decode($kegiatan_maal_didonasi->detail)->sisa);
                $detail['saldo_akhir'] = floatval(json_decode($kegiatan_maal_didonasi->detail)->sisa) + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
            }

            $dataToSave = [
                'id'                => $nextId,
                'id_user'           => $donatur->id,
                'id_rekening'       => $id_rekening,
                'jenis_pengajuan'   => $jenis_pengajuan,
                'status'            => 'Sudah Dikonfirmasi',
                'kategori'          => 'Donasi',
                'detail'            => $detail,
                'teller'            => Auth::user()->id
            ];

            $pengajuan = $this->pengajuanReporsitory->createPengajuan($dataToSave);

            $dataToInsertIntoPenyimpananMaal = [
                'id_donatur'    => $donatur->id,
                'id_maal'       => $data->id_donasi,
                'status'        => $debit,
                'transaksi'     => $detail,
                'teller'        => Auth::user()->id
            ];

            $detailToPenyimpananBMT = [
                "jumlah"        => preg_replace('/[^\d.]/', '', $data->nominal),
                "saldo_awal"    => $saldo_awal_donasi,
                "saldo_akhir"   => $saldo_akhir_donasi,
                "id_pengajuan"  => $nextId
            ];

            $dataToInsertIntoPenyimpananBMT = [
                'id_user'       => $donatur->id,
                'id_bmt'        => $bmt_donasi->id,
                'status'        => $data->jenis_donasi,
                'transaksi'     => $detailToPenyimpananBMT,
                'teller'        => Auth::user()->id
            ];
            
            if($pengajuan['type'] == "success")
            {
                if($data->debit == 2) {     // Pembayaran VIA Tabungan                
                    $tabungan_pengirim = Tabungan::where('id_tabungan', $data->rekening)->first();
                    $rekening_pengirim = Rekening::where([ ['nama_rekening', $tabungan_pengirim->jenis_tabungan], ['tipe_rekening', 'detail'] ])->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                    $detailToPenyimpananTabungan = [
                        "teller"    => Auth::user()->id,
                        "dari_rekening" => $tabungan_pengirim->jenis_tabungan,
                        "untuk_rekening" => $bmt_donasi->nama,
                        "jumlah" => floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                        "saldo_awal" => json_decode($tabungan_pengirim->detail)->saldo,
                        "saldo_akhir" => floatval(json_decode($tabungan_pengirim->detail)->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->nominal))
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => $donatur->id,
                        "id_tabungan"   => $tabungan_pengirim->id,
                        "status"        => "Pembayaran " . $bmt_donasi->nama,
                        "transaksi"     => $detailToPenyimpananTabungan,
                        "teller"        => Auth::user()->id
                    ];

                    if(floatval($bmt_pengirim->saldo) > floatval(preg_replace('/[^\d.]/', '', $data->nominal)))
                    {
                        $saldo_akhir_bmt_pengirim = floatval($bmt_pengirim->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                        $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_akhir_bmt_pengirim]);

                        $update_saldo_pengirim_response = "success";

                        $saveToPenyimpananTabungan = $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                    }
                    else
                    {
                        $update_saldo_pengirim_response = "error";
                    }
                }
                
                if($bank_tujuan_transfer != null) { // Pembayaran VIA Transfer
                    $rekening_pengirim = Rekening::where('id', $data->bank_tujuan)->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                    $saldo_akhir_bmt_pengirim = floatval($bmt_pengirim->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                    $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_akhir_bmt_pengirim]);

                    $update_saldo_pengirim_response = "success";
                }

                if($data->debit == 0) { // Pembayaran VIA Tunai
                    $rekening_pengirim = Rekening::where('id', json_decode(Auth::user()->detail)->id_rekening)->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
                    $saldo_akhir_bmt_pengirim = floatval($bmt_pengirim->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                    $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_akhir_bmt_pengirim]);

                    $update_saldo_pengirim_response = "success";
                }

                if($update_saldo_pengirim_response == "success")
                {
                    if($data->jenis_donasi == "donasi kegiatan")
                    {
                        $penyimpananMaal = $this->insertPenyimpananMaal($dataToInsertIntoPenyimpananMaal);
                    }

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);
                    
                    $dataToInsertIntoPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                    $detailToPenyimpananBMT['jumlah'] = preg_replace('/[^\d.]/', '', $data->nominal);
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_pengirim->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_bmt_pengirim;
                    $dataToInsertIntoPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                    if($data->jenis_donasi == "donasi kegiatan")
                    {
                        $kegiatan_maal_didonasi = Maal::where('id', $data->id_donasi)->first();
                        
                        $detail_maal_update = [
                            "detail"    => json_decode($kegiatan_maal_didonasi->detail)->detail,
                            "dana"      => json_decode($kegiatan_maal_didonasi->detail)->dana,
                            "terkumpul" => floatval(json_decode($kegiatan_maal_didonasi->detail)->terkumpul) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "sisa" => floatval(json_decode($kegiatan_maal_didonasi->detail)->sisa) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "path_poster" => json_decode($kegiatan_maal_didonasi->detail)->path_poster
                        ];

                        $update_dana_terkumpul = Maal::where('id', $data->id_donasi)->update([ 'detail' => json_encode($detail_maal_update) ]);
                    }

                    $update_saldo_bmt_jenis_donasi = BMT::where('id', $bmt_donasi->id)->update([ 'saldo' => floatval($bmt_donasi->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)) ]);

                    if($data->debit == 2) {
                        $dataToCheckInTabungan = [
                            'id_tabungan' => $data->rekening,
                            'jumlah'      => preg_replace('/[^\d.]/', '', $data->nominal),
                            'id_pengajuan' => $nextId
                        ];
                        $this->updateSaldoTabungan($dataToCheckInTabungan);
                    }

                    DB::commit();
                    $response = array('type' => 'success', 'message' => 'Pembayaran donasi berhasil dilakukan');
                }
                else
                {
                    DB::rollback();
                    $response = array('type' => 'error', 'message' => 'Pembayaran donasi gagal dilakukan. Saldo rekening tidak cukup');
                }
            }
            else
            {
                DB::rollback();
                $response = array('type' => 'error', 'message' => 'Pembayaran donasi gagal dilakukan');
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array('type' => 'error', 'message' => 'Pembayaran donasi gagal dilakukan');
        }

        return $response;
    }

    /**
     * Pay donasi wakaf from teller page
     * @return Response
     */
    public function payDonasiWakaf($data)
    {

        DB::beginTransaction();
        try
        {
            $statement = DB::select("SHOW TABLE STATUS LIKE 'pengajuan'");
            $nextId = $statement[0]->Auto_increment;

            $donatur = User::where('id', $data->donatur)->first();

            if($data->debit == 1) {
                $file = $data->file->getClientOriginalName();
                $file_name = preg_replace('/\s+/', '_', $file);
                $fileToUpload = time() . "-" . $file_name;
                $data->file('file')->storeAs(
                    'transfer', $fileToUpload
                );

                $debit = "Transfer";
                $path_bukti = $fileToUpload;
                $rekening = null;
                $atasnama = $data->atas_nama;
                $namabank = $data->nama_bank;
                $norek = $data->nomor_rekening;
                $bank_tujuan_transfer = $data->bank_tujuan;
            }

            if($data->debit == 2) {
                $debit = "Tabungan";
                $path_bukti = null;
                $rekening = $data->rekening;
                $atasnama = null;
                $namabank = null;
                $norek = null;
                $bank_tujuan_transfer = null;

                $tabungan_pengirim = Tabungan::where('id_tabungan', $data->rekening)->first();
            }

            if($data->debit == 0) {
                $debit = "Tunai";
                $path_bukti = null;
                $rekening = $data->rekening;
                $atasnama = null;
                $namabank = null;
                $norek = null;
                $bank_tujuan_transfer = null;
            }

            $jenis_pengajuan = "Wakaf";
            $id_rekening = 114;


            $bmt_donasi = BMT::where('id_rekening', $id_rekening)->first();
            $saldo_awal_donasi = $bmt_donasi->saldo;
            $saldo_akhir_donasi = $bmt_donasi->saldo + preg_replace('/[^\d.]/', '', $data->nominal);

            if($data->debit == 2) {     // Pembayaran VIA Tabungan
                $tabungan_pengirim = Tabungan::where('id_tabungan', $data->rekening)->first();
                $rekening_pengirim = Rekening::where([ ['nama_rekening', $tabungan_pengirim->jenis_tabungan], ['tipe_rekening', 'detail'] ])->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
            }

            if($bank_tujuan_transfer != null) { // Pembayaran V IA Transfer
                $rekening_pengirim = Rekening::where('id', $data->bank_tujuan)->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
            }

            if($data->debit == 0) { // Pembayaran VIA Tunai
                $rekening_pengirim = Rekening::where('id', json_decode(Auth::user()->detail)->id_rekening)->select('id')->first();
                $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
            }

            $detail = [
                'id_wakaf'   => $data->id_donasi,
                'jenis_donasi'    => $data->jenis_donasi,
                'id'        => $donatur->id,
                'nama'      => $donatur->nama,
                'debit'     => $debit,
                'path_bukti'=> $path_bukti,
                'jumlah'    => preg_replace('/[^\d.]/', '', $data->nominal),
                'rekening'  => $rekening,
                'atasnama'  => $atasnama,
                'bank'      => $namabank,
                'no_bank'   => $norek,
                'bank_tujuan_transfer' => $bank_tujuan_transfer,
                'saldo_awal' => $saldo_awal_donasi,
                'saldo_akhir'   => $saldo_akhir_donasi,
                'dari_rekening' => $bmt_pengirim->nama,
                'untuk_rekening' => $bmt_donasi->nama
            ];

            if($data->jenis_donasi == 'donasi kegiatan wakaf')
            {
                $kegiatan_wakaf_didonasi = Wakaf::where('id', $data->id_donasi)->first();
                $detail['dana_terkumpul_awal'] = floatval(json_decode($kegiatan_wakaf_didonasi->detail)->terkumpul);
                $detail['dana_terkumpul_akhir'] = floatval(json_decode($kegiatan_wakaf_didonasi->detail)->terkumpul) + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                $detail['dari_rekening'] = $bmt_pengirim->nama;
                $detail['untuk_rekening'] = $kegiatan_wakaf_didonasi->nama_kegiatan;
                $detail['saldo_awal'] = floatval(json_decode($kegiatan_wakaf_didonasi->detail)->sisa);
                $detail['saldo_akhir'] = floatval(json_decode($kegiatan_wakaf_didonasi->detail)->sisa) + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
            }

            $dataToSave = [
                'id'                => $nextId,
                'id_user'           => $donatur->id,
                'id_rekening'       => $id_rekening,
                'jenis_pengajuan'   => $jenis_pengajuan,
                'status'            => 'Sudah Dikonfirmasi',
                'kategori'          => 'Donasi',
                'detail'            => $detail,
                'teller'            => Auth::user()->id
            ];

            $pengajuan = $this->pengajuanReporsitory->createPengajuan($dataToSave);

            $dataToInsertIntoPenyimpananWakaf = [
                'id_donatur'    => $donatur->id,
                'id_wakaf'       => $data->id_donasi,
                'status'        => $debit,
                'transaksi'     => $detail,
                'teller'        => Auth::user()->id
            ];

            $detailToPenyimpananBMT = [
                "jumlah"        => preg_replace('/[^\d.]/', '', $data->nominal),
                "saldo_awal"    => $saldo_awal_donasi,
                "saldo_akhir"   => $saldo_akhir_donasi,
                "id_pengajuan"  => $nextId
            ];

            $dataToInsertIntoPenyimpananBMT = [
                'id_user'       => $donatur->id,
                'id_bmt'        => $bmt_donasi->id,
                'status'        => $data->jenis_donasi,
                'transaksi'     => $detailToPenyimpananBMT,
                'teller'        => Auth::user()->id
            ];

            if($pengajuan['type'] == "success")
            {
                if($data->debit == 2) {     // Pembayaran VIA Tabungan
                    $tabungan_pengirim = Tabungan::where('id_tabungan', $data->rekening)->first();
                    $rekening_pengirim = Rekening::where([ ['nama_rekening', $tabungan_pengirim->jenis_tabungan], ['tipe_rekening', 'detail'] ])->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                    $detailToPenyimpananTabungan = [
                        "teller"    => Auth::user()->id,
                        "dari_rekening" => $tabungan_pengirim->jenis_tabungan,
                        "untuk_rekening" => $bmt_donasi->nama,
                        "jumlah" => floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                        "saldo_awal" => json_decode($tabungan_pengirim->detail)->saldo,
                        "saldo_akhir" => floatval(json_decode($tabungan_pengirim->detail)->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->nominal))
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => $donatur->id,
                        "id_tabungan"   => $tabungan_pengirim->id,
                        "status"        => "Pembayaran " . $bmt_donasi->nama,
                        "transaksi"     => $detailToPenyimpananTabungan,
                        "teller"        => Auth::user()->id
                    ];

                    if(floatval($bmt_pengirim->saldo) > floatval(preg_replace('/[^\d.]/', '', $data->nominal)))
                    {
                        $saldo_akhir_bmt_pengirim = floatval($bmt_pengirim->saldo) - floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                        $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_akhir_bmt_pengirim]);

                        $update_saldo_pengirim_response = "success";

                        $saveToPenyimpananTabungan = $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                    }
                    else
                    {
                        $update_saldo_pengirim_response = "error";
                    }
                }

                if($bank_tujuan_transfer != null) { // Pembayaran VIA Transfer
                    $rekening_pengirim = Rekening::where('id', $data->bank_tujuan)->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                    $saldo_akhir_bmt_pengirim = floatval($bmt_pengirim->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                    $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_akhir_bmt_pengirim]);

                    $update_saldo_pengirim_response = "success";
                }

                if($data->debit == 0) { // Pembayaran VIA Tunai
                    $rekening_pengirim = Rekening::where('id', json_decode(Auth::user()->detail)->id_rekening)->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
                    $saldo_akhir_bmt_pengirim = floatval($bmt_pengirim->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->nominal));
                    $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_akhir_bmt_pengirim]);

                    $update_saldo_pengirim_response = "success";
                }

                if($update_saldo_pengirim_response == "success")
                {

                    if($data->jenis_donasi == "donasi kegiatan wakaf")
                    {
                        $penyimpananWakaf = $this->insertPenyimpananWakaf($dataToInsertIntoPenyimpananWakaf);
                    }

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                    $dataToInsertIntoPenyimpananBMT['id_bmt'] = $bmt_pengirim->id;
                    $detailToPenyimpananBMT['jumlah'] = preg_replace('/[^\d.]/', '', $data->nominal);
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_pengirim->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $saldo_akhir_bmt_pengirim;
                    $dataToInsertIntoPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                    if($data->jenis_donasi == "donasi kegiatan wakaf")
                    {
                        $kegiatan_wakaf_didonasi = Wakaf::where('id', $data->id_donasi)->first();

                        $detail_wakaf_update = [
                            "detail"    => json_decode($kegiatan_wakaf_didonasi->detail)->detail,
                            "dana"      => json_decode($kegiatan_wakaf_didonasi->detail)->dana,
                            "terkumpul" => floatval(json_decode($kegiatan_wakaf_didonasi->detail)->terkumpul) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "sisa"      => floatval(json_decode($kegiatan_wakaf_didonasi->detail)->sisa) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)),
                            "path_poster" => json_decode($kegiatan_wakaf_didonasi->detail)->path_poster
                        ];

                        $update_dana_terkumpul = Wakaf::where('id', $data->id_donasi)->update([ 'detail' => json_encode($detail_wakaf_update) ]);
                    }

                    $update_saldo_bmt_jenis_donasi = BMT::where('id', $bmt_donasi->id)->update([ 'saldo' => floatval($bmt_donasi->saldo) + floatval(preg_replace('/[^\d.]/', '', $data->nominal)) ]);

                    if($data->debit == 2) {
                        $dataToCheckInTabungan = [
                            'id_tabungan' => $data->rekening,
                            'jumlah'      => preg_replace('/[^\d.]/', '', $data->nominal),
                            'id_pengajuan' => $nextId
                        ];
                        $this->updateSaldoTabungan($dataToCheckInTabungan);
                    }

                    DB::commit();
                    $response = array('type' => 'success', 'message' => 'Pembayaran donasi berhasil dilakukan');
                }
                else
                {
                    DB::rollback();
                    $response = array('type' => 'error', 'message' => 'Pembayaran donasi gagal dilakukan. Saldo rekening tidak cukup');
                }
            }
            else
            {
                DB::rollback();
                $response = array('type' => 'error', 'message' => 'Pembayaran donasi gagal dilakukan');
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array('type' => 'error', 'message' => 'Pembayaran donasi gagal dilakukan');
        }

        return $response;
    }

    /** 
     * Create new kegiatan maal
     * @return Response
    */
    public function createNewKegiatan($data)
    {
        DB::beginTransaction();
        try
        {
            $data_maal = Maal::take('1')->orderBy('id_maal', 'desc')->first();
            if(isset($data_maal->id_maal))
            {
                $last_id = $data_maal->id_maal;
            }
            else
            {
                $last_id = 0;
            }
            $bmt_maal = BMT::where('nama', 'DANA SOSIAL')->first();

            $file = $data->file->getClientOriginalName();
            $file_name = preg_replace('/\s+/', '_', $file);
            $fileToUpload = time() . "-" . $file_name;

            $detail = array(
                "detail"        => $data->detail,
                "dana"          => preg_replace('/[^\d.]/', '', $data->jumlah),
                "terkumpul"     => 0,
                "sisa"          => 0 ,
                "path_poster"   => $fileToUpload
            );

            $maal = new Maal();
            $maal->id = $last_id + 1;
            $maal->id_maal = $last_id + 1;
            $maal->id_rekening = $bmt_maal->id_rekening;
            $maal->nama_kegiatan = $data->kegiatan;
            $maal->tanggal_pelaksaaan = Carbon::parse($data->tgl)->format('Y-m-d');
            $maal->status = "active";
            $maal->detail = json_encode($detail);
            $maal->teller = Auth::user()->id;

            if($maal->save())
            {
                $data->file('file')->storeAs(
                    'public/maal', $fileToUpload
                );
                
                DB::commit();
                $response = array("type" => "success", "message" => "Kegiatan maal baru berhasil dibuat.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Kegiatan maal gagal dibuat.");
        }

        return $response;
    }

    /**
     * Create new kegiatan Wakaf
     * @return Response
     */
    public function createNewKegiatanWakaf($data)
    {
        DB::beginTransaction();
        try
        {
            $data_wakaf = Wakaf::take('1')->orderBy('id_wakaf', 'desc')->first();
            if(isset($data_wakaf->id_wakaf))
            {
                $last_id = $data_wakaf->id_wakaf;
            }
            else
            {
                $last_id = 0;
            }
            $bmt_wakaf = BMT::where('nama', 'WAKAF UANG')->first();

            $file = $data->file->getClientOriginalName();
            $file_name = preg_replace('/\s+/', '_', $file);
            $fileToUpload = time() . "-" . $file_name;

            $detail = array(
                "detail"        => $data->detail,
                "dana"          => preg_replace('/[^\d.]/', '', $data->jumlah),
                "terkumpul"     => 0,
                "sisa"          => 0,
                "path_poster"   => $fileToUpload
            );

            $wakaf = new Wakaf();
            $wakaf->id = $last_id + 1;
            $wakaf->id_wakaf = $last_id + 1;
            $wakaf->id_rekening = $bmt_wakaf->id_rekening;
            $wakaf->nama_kegiatan = $data->kegiatan;
            $wakaf->tanggal_pelaksaaan = Carbon::parse($data->tgl)->format('Y-m-d');
            $wakaf->status = "active";
            $wakaf->detail = json_encode($detail);
            $wakaf->teller = Auth::user()->id;

            if($wakaf->save())
            {
                $data->file('file')->storeAs(
                    'public/wakaf', $fileToUpload
                );

                DB::commit();
                $response = array("type" => "success", "message" => "Kegiatan wakaf baru berhasil dibuat.");
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Kegiatan maal gagal dibuat.");
        }

        return $response;
    }

}

?>