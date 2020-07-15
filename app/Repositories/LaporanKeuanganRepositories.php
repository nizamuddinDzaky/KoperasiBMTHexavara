<?php

namespace App\Repositories;
use App\User;
use App\Pengajuan;
use App\Tabungan;
use App\Deposito;
use App\BMT;
use App\Rekening;
use App\PenyimpananLaporanKeuangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\SimpananReporsitory;
use App\Repositories\DepositoReporsitories;
use App\Repositories\HelperRepositories;
use App\Repositories\ExportRepositories;
use Carbon\Carbon;

class LaporanKeuanganRepositories {

    public function __construct(RekeningReporsitories $rekeningReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                SimpananReporsitory $simpananReporsitory,
                                DepositoReporsitories $depositoReporsitory,
                                HelperRepositories $helperRepository,
                                ExportRepositories $exportRepository
                                ) 
    {
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->simpananReporsitory = $simpananReporsitory;
        $this->depositoReporsitory = $depositoReporsitory;
        $this->helperRepository = $helperRepository;
        $this->exportRepository = $exportRepository;
    }

    /** 
     * Get riwayat laporan keuangan
     * @return Response
    */
    public function getRiwayat()
    {
        $riwayat = PenyimpananLaporanKeuangan::All();
        return $riwayat;
    }

    /** 
     * Find spesifik riwayat laporan keuangan
     * @return Response
    */
    public function findRiwayat($id)
    {
        $riwayat = PenyimpananLaporanKeuangan::find($id);
        return $riwayat;
    }

    /** 
     * Export data
     * @return Response
    */
    public function exportData($data)
    {
        DB::beginTransaction();
        try 
        {
            $dataToPenyimpananLaporanKeuangan = [
                'id_user'   => Auth::user()->id,
                'status'    => 'Laporan Keuangan',
                'transaksi' => $data
            ];

            $export = $this->insertToPenyimpananLaporanKeuangan($dataToPenyimpananLaporanKeuangan);

            if($export == "success")
            {
                $update_rekening = Rekening::where('tipe_rekening', 'detail')->update(['catatan' => ""]);
            }

            DB::commit();
            $response = array("type", 'success', 'message', 'Export data ke excel berhasil');
        }
        catch(Exception $ex) 
        {
            DB::rollback();
            $response = array("type", 'error', 'message', 'Export data ke excel gagal');
        }
        
        return $response;
    }

    /** 
     * Insert to penyimpanan laporan keuangan
     * @return Response
    */
    public function insertToPenyimpananLaporanKeuangan($data)
    {
        $penyimpanan = new PenyimpananLaporanKeuangan();
        $penyimpanan->id_user = $data['id_user']; 
        $penyimpanan->status = $data['status']; 
        $penyimpanan->transaksi = json_encode($data['transaksi']); 

        if($penyimpanan->save())
        {
            return "success";
        }
        else
        {
            return "error";
        }
    }

}