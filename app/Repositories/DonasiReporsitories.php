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
use App\Maal;
use App\PenyimpananMaal;

class DonasiReporsitories {

    public function __construct(
                                PengajuanReporsitories $pengajuanReporsitory
                                )
    {
        $this->pengajuanReporsitory = $pengajuanReporsitory;
    }

    /** 
     * Mengirim pengajuan donasi
     * @return Response 
    */
    public function sendDonasi($data)
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
        } else {
            $debit = "Tabungan";
            $path_bukti = null;
            $rekening = $data->rekening;
            $atasnama = null;
            $namabank = null;
            $norek = null;
        }

        // Get jenis donasi (donasi kegiatan/maal, zis, wakaf)
        if($data->jenis_donasi == 'donasi kegiatan')
        {
            $jenis_pengajuan = "Donasi Kegiatan";
            $id_rekening = 179;
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
            'no_bank'   => $norek
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
        
        return $this->pengajuanReporsitory->createPengajuan($dataToSave);
    }

    /** 
     * Get all pengajuan donasi
     * @return Response
    */
    public function getPengajuanDonasi()
    {
        $pengajuanDonasi = Pengajuan::where('kategori', 'Donasi')->get();

        return $pengajuanDonasi;
    }

    /** 
     * Confirm pengajuan donasi
     * @return Response
    */
    public function confirmDonasi($data)
    {
        $pengajuan = Pengajuan::where('id', $data->id_)->first();

        $maal = Maal::where('id', $data->rekDon)->first();
        $dana_terkumpul = json_decode($maal->detail)->terkumpul;
        $jumlah_donasi = json_decode($pengajuan->detail)->jumlah;

        $detail_maal_update = [
            "detail"    => json_decode($maal->detail)->detail,
            "dana"      => json_decode($maal->detail)->dana,
            "terkumpul" => $dana_terkumpul + $jumlah_donasi,
            "path_poster" => json_decode($maal->detail)->path_poster
        ];

        $dataToInsertIntoPenyimpananMaal = [
            'id_donatur'    => $pengajuan->id_user,
            'id_maal'       => $data->rekDon,
            'status'        => json_decode($pengajuan->detail)->jenis_donasi,
            'transaksi'     => $pengajuan->detail,
            'teller'        => Auth::user()->id
        ];

        $penyimpananMaal = $this->insertPenyimpananMaal($dataToInsertIntoPenyimpananMaal);

        if($penyimpananMaal['type'] == 'success') {
            // Update donasi maal dana terkumpul
            $update_dana_terkumpul = Maal::where('id', $data->rekDon)->update([ 'detail' => json_encode($detail_maal_update) ]);
                $detail_pengajuan = json_decode($pengajuan->detail);
                if($detail_pengajuan->debit == "Tabungan") {
                    $dataToCheckInTabungan = [
                        'id_tabungan' => $detail_pengajuan->rekening,
                        'jumlah'      => $detail_pengajuan->jumlah,
                        'id_pengajuan' => $data->id_
                    ];
                    $this->updateSaldoTabungan($dataToCheckInTabungan);
                }
        }

        return $penyimpananMaal;
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

        $update = Tabungan::where('id_tabungan', $data['id_tabungan'])->update([ 'detail' => json_encode($dataToUpdateTabungan) ]);
    }

}

?>