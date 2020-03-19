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
use App\Pengajuan;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Maal;
use App\PenyimpananMaal;
use App\PenyimpananBMT;
use App\User;

class DonasiReporsitories {

    public function __construct(
                                PengajuanReporsitories $pengajuanReporsitory,
                                TabunganReporsitories $tabunganReporsitory
                                )
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
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
                $id_rekening = 179;
            }
            if($data->jenis_donasi == 'zis')
            {
                $jenis_pengajuan = "ZIS";
                $id_rekening = 112;
            }
            if($data->jenis_donasi == 'wakaf')
            {
                $jenis_pengajuan = "Wakaf";
                $id_rekening = 118;
            }

            $detail = [
                'id_maal'   => $data->id_donasi,
                'jenis_donasi'    => $data->jenis_donasi,
                'id'        => Auth::user()->id,
                'nama'      => Auth::user()->nama,
                'debit'     => $debit,
                'path_bukti'=> $path_bukti,
                'jumlah'    => $data->nominal,
                'rekening'  => $rekening,
                'atasnama'  => $atasnama,
                'bank'      => $namabank,
                'no_bank'   => $norek,
                'bank_tujuan_transfer' => $bank_tujuan_transfer
            ];

            $dataToSave = [
                'id_user'           => Auth::user()->id,
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
            $bmt = BMT::where('id_rekening', $pengajuan->id_rekening)->select(['id', 'saldo'])->first();
            if($data->rekDon != null) {
                $maal = Maal::where('id', $data->rekDon)->first();
                $dana_terkumpul = json_decode($maal->detail)->terkumpul;
                $jumlah_donasi = json_decode($pengajuan->detail)->jumlah;

                $detail_maal_update = [
                    "detail"    => json_decode($maal->detail)->detail,
                    "dana"      => json_decode($maal->detail)->dana,
                    "terkumpul" => $dana_terkumpul + $jumlah_donasi,
                    "path_poster" => json_decode($maal->detail)->path_poster
                ];
            }

            // Update saldo rekening pengirim di bmt
            // Execute for pengajuan via tabungan
            if(json_decode($pengajuan->detail)->debit == "Tabungan") {
                    $tabungan_pengirim = Tabungan::where('id_tabungan', json_decode($pengajuan->detail)->rekening)->first();
                    $rekening_pengirim = Rekening::where([ ['nama_rekening', $tabungan_pengirim->jenis_tabungan], ['tipe_rekening', 'detail'] ])->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                    $detailToPenyimpananTabungan = [
                        "teller"    => Auth::user()->id,
                        "dari_rekening" => json_decode($pengajuan->detail)->rekening,
                        "untuk_rekening" => $data->rekDon,
                        "jumlah" => json_decode($pengajuan->detail)->jumlah,
                        "saldo_awal" => json_decode($tabungan_pengirim->detail)->saldo,
                        "saldo_akhir" => floatval(json_decode($tabungan_pengirim->detail)->saldo) - floatval(json_decode($pengajuan->detail)->jumlah)
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => json_decode($pengajuan->detail)->id,
                        "id_tabungan"   => $tabungan_pengirim->id,
                        "status"        => "Donasi",
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

                $update_saldo_pengirim_response = "success";
            }

            $dataToInsertIntoPenyimpananMaal = [
                'id_donatur'    => $pengajuan->id_user,
                'id_maal'       => $data->rekDon,
                'status'        => json_decode($pengajuan->detail)->debit,
                'transaksi'     => $pengajuan->detail,
                'teller'        => Auth::user()->id
            ];

            $dataToInsertIntoPenyimpananBMT = [
                'id_user'       => $pengajuan->id_user,
                'id_bmt'        => $bmt->id,
                'status'        => json_decode($pengajuan->detail)->jenis_donasi,
                'transaksi'     => $pengajuan->detail,
                'teller'        => Auth::user()->id
            ];

            // This is for donasi kegiatan action 
            if($update_saldo_pengirim_response == "success")
            {
                if($data->rekDon != null) {
                    
                    $penyimpananMaal = $this->insertPenyimpananMaal($dataToInsertIntoPenyimpananMaal);

                    if($penyimpananMaal['type'] == 'success') {
                        
                        $penyimpananBMT = $this->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);
                        
                        $detail_pengajuan = json_decode($pengajuan->detail);

                        // Update donasi maal dana terkumpul
                        $update_dana_terkumpul = Maal::where('id', $data->rekDon)->update([ 'detail' => json_encode($detail_maal_update) ]);

                        // update saldo in bmt table
                        $update_saldo_bmt = BMT::where('id', $bmt->id)->update([ 'saldo' => floatval($bmt->saldo) + floatval($detail_pengajuan->jumlah) ]);

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
                    $penyimpananBMT = $this->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);

                    $detail_pengajuan = json_decode($pengajuan->detail);
                    
                    // update saldo in bmt table
                    $update_saldo_bmt = BMT::where('id', $bmt->id)->update([ 'saldo' => floatval($bmt->saldo) + floatval($detail_pengajuan->jumlah) ]);

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
        $penyimpanan->transaksi = $data['transaksi'];
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
     * Insert data to penyimpanan bmt
     * @return Response
    */
    public function insertPenyimpananBMT($data) {
        $penyimpanan = new PenyimpananBMT();
        $penyimpanan->id_user = $data['id_user'];
        $penyimpanan->id_bmt = $data['id_bmt'];
        $penyimpanan->status = $data['status'];
        $penyimpanan->transaksi = $data['transaksi'];
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
                $id_user_pengirim = Auth::user()->id;
                $nama_user_pengirim = Auth::user()->nama;
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
                $user_pengirim = User::where('id', $tabungan_pengirim->id_user)->first();
                $id_user_pengirim = $user_pengirim->id;
                $nama_user_pengirim = $user_pengirim->nama;
            }

            if($data->debit == 0) {
                $debit = "Tunai";
                $path_bukti = null;
                $rekening = $data->rekening;
                $atasnama = null;
                $namabank = null;
                $norek = null;
                $bank_tujuan_transfer = null;

                $user_pengirim = User::where('id', Auth::user()->id)->first();
                $id_user_pengirim = $user_pengirim->id;
                $nama_user_pengirim = $user_pengirim->nama;
            }

            // Get jenis donasi (donasi kegiatan/maal, zis, wakaf)
            if($data->jenis_donasi == 'donasi kegiatan')
            {
                $jenis_pengajuan = "Donasi Kegiatan";
                $id_rekening = 179;
            }
            if($data->jenis_donasi == 'zis')
            {
                $jenis_pengajuan = "ZIS";
                $id_rekening = 112;
            }
            if($data->jenis_donasi == 'wakaf')
            {
                $jenis_pengajuan = "Wakaf";
                $id_rekening = 118;
            }

            $bmt = BMT::where('id_rekening', $id_rekening)->first();

            $detail = [
                'id_maal'   => $data->id_donasi,
                'jenis_donasi'    => $data->jenis_donasi,
                'id'        => $id_user_pengirim,
                'nama'      => $nama_user_pengirim,
                'debit'     => $debit,
                'path_bukti'=> $path_bukti,
                'jumlah'    => $data->nominal,
                'rekening'  => $rekening,
                'atasnama'  => $atasnama,
                'bank'      => $namabank,
                'no_bank'   => $norek,
                'bank_tujuan_transfer' => $bank_tujuan_transfer
            ];

            $dataToSave = [
                'id'                => $nextId,
                'id_user'           => $id_user_pengirim,
                'id_rekening'       => $id_rekening,
                'jenis_pengajuan'   => $jenis_pengajuan,
                'status'            => 'Sudah Konfirmasi',
                'kategori'          => 'Donasi',
                'detail'            => $detail,
                'teller'            => Auth::user()->id
            ];

            $pengajuan = $this->pengajuanReporsitory->createPengajuan($dataToSave);

            $dataToInsertIntoPenyimpananMaal = [
                'id_donatur'    => $id_user_pengirim,
                'id_maal'       => $data->id_donasi,
                'status'        => $debit,
                'transaksi'     => json_encode($detail),
                'teller'        => Auth::user()->id
            ];

            $dataToInsertIntoPenyimpananBMT = [
                'id_user'       => $id_user_pengirim,
                'id_bmt'        => $bmt->id,
                'status'        => $data->jenis_donasi,
                'transaksi'     => json_encode($detail),
                'teller'        => Auth::user()->id
            ];
            
            if($pengajuan['type'] == "success")
            {
                // Update saldo rekening pengirim di bmt
                // Execute for pengajuan via tabungan
                if($data->debit == 2) {                    
                    $tabungan_pengirim = Tabungan::where('id_tabungan', $data->rekening)->first();
                    $rekening_pengirim = Rekening::where([ ['nama_rekening', $tabungan_pengirim->jenis_tabungan], ['tipe_rekening', 'detail'] ])->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                    $detailToPenyimpananTabungan = [
                        "teller"    => Auth::user()->id,
                        "dari_rekening" => $data->rekening,
                        "untuk_rekening" => $id_rekening,
                        "jumlah" => floatval($data->nominal),
                        "saldo_awal" => json_decode($tabungan_pengirim->detail)->saldo,
                        "saldo_akhir" => floatval(json_decode($tabungan_pengirim->detail)->saldo) - floatval($data->nominal)
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"       => $id_user_pengirim,
                        "id_tabungan"   => $tabungan_pengirim->id,
                        "status"        => "Donasi",
                        "transaksi"     => $detailToPenyimpananTabungan,
                        "teller"        => Auth::user()->id
                    ];

                    if(floatval($bmt_pengirim->saldo) > floatval($data->nominal))
                    {
                        $saldo_bmt_pengirim = floatval($bmt_pengirim->saldo) - floatval($data->nominal);
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
                if($bank_tujuan_transfer != null) {
                    $rekening_pengirim = Rekening::where('id', $data->bank_tujuan)->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();

                    $saldo_bmt_pengirim = floatval($bmt_pengirim->saldo) + floatval($data->nominal);
                    $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_bmt_pengirim]);

                    $update_saldo_pengirim_response = "success";
                }

                // Update saldo rekening pengirim di bmt
                // Execute for pengajuan via tunai
                if($data->debit == 0) {
                    $rekening_pengirim = Rekening::where('id', json_decode(Auth::user()->detail)->id_rekening)->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->first();
                    $saldo_bmt_pengirim = floatval($bmt_pengirim->saldo) + floatval($data->nominal);
                    $update_saldo_bmt_pengirim = BMT::where('id_rekening', $rekening_pengirim->id)->update([ "saldo" => $saldo_bmt_pengirim]);

                    $update_saldo_pengirim_response = "success";
                }

                // This is for donasi kegiatan action 
                if($update_saldo_pengirim_response == "success")
                {
                    if($data->jenis_donasi == "donasi kegiatan")
                    {
                        $penyimpananMaal = $this->insertPenyimpananMaal($dataToInsertIntoPenyimpananMaal);
                    }

                    $penyimpananBMT = $this->insertPenyimpananBMT($dataToInsertIntoPenyimpananBMT);
                    
                    
                    if($data->jenis_donasi == "donasi kegiatan")
                    {
                        $kegiatan_maal_didonasi = Maal::where('id', $data->id_donasi)->first();
                        
                        $detail_maal_update = [
                            "detail"    => json_decode($kegiatan_maal_didonasi->detail)->detail,
                            "dana"      => json_decode($kegiatan_maal_didonasi->detail)->dana,
                            "terkumpul" => floatval(json_decode($kegiatan_maal_didonasi->detail)->terkumpul) + floatval($data->nominal),
                            "path_poster" => json_decode($kegiatan_maal_didonasi->detail)->path_poster
                        ];

                        // Update donasi maal dana terkumpul
                        $update_dana_terkumpul = Maal::where('id', $data->id_donasi)->update([ 'detail' => json_encode($detail_maal_update) ]);
                    }

                    // update saldo in bmt table
                    $update_saldo_bmt = BMT::where('id', $bmt->id)->update([ 'saldo' => floatval($bmt->saldo) + floatval($data->nominal) ]);

                    if($data->debit == 2) {
                        $dataToCheckInTabungan = [
                            'id_tabungan' => $data->rekening,
                            'jumlah'      => $data->nominal,
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

}

?>