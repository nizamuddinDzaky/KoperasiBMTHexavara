<?php

namespace App\Repositories;
use App\User;
use App\Pengajuan;
use App\Tabungan;
use App\BMT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\SimpananReporsitory;

class AccountReporsitories {

    public function __construct(RekeningReporsitories $rekeningReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                SimpananReporsitory $simpananReporsitory
                                ) 
    {
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->simpananReporsitory = $simpananReporsitory;
    }

    /**
     * Get all account with specific type
     * @return Response
    */
    public function getAccount($type)
    {
        $account = User::where('tipe', $type)->get();
        return $account;
    }

    /** 
     * Activasi pembukaan akun baru
     * @return Response
    */
    public function activasiAccount($data)
    {
        DB::beginTransaction();
        try {
            $pengajuan = Pengajuan::where('id', $data->id_)->first();
            $bmt_tabungan = BMT::where('id_rekening', $pengajuan->id_rekening)->first();
            $bmt_simpanan_wajib = BMT::where('id_rekening', 119)->first();
            $bmt_simpanan_pokok = BMT::where('id_rekening', 117)->first();
            $bmt_pengonfirmasi = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first();
            $user_tabungan = User::where('id', json_decode($pengajuan->detail)->id)->first();
            $jumlah_bayar_simpanan_wajib = floatval(preg_replace('/[^\d.]/', '', $data->wajib));
            $jumlah_bayar_simpanan_pokok = floatval(preg_replace('/[^\d.]/', '', $data->pokok));

            $statement = DB::select("SHOW TABLE STATUS LIKE 'tabungan'");
            $nextIdTabungan = $statement[0]->Auto_increment;

            $detailToTabungan = [
                "saldo"         => 0,
                "id_pengajuan"  => $pengajuan->id
            ];
            $dataToTabungan = [
                'id_tabungan'       => $user_tabungan->id . "." . $nextIdTabungan,
                'id_rekening'       => $bmt_tabungan->id_rekening,
                'id_user'           => $user_tabungan->id,
                'id_pengajuan'      => $pengajuan->id,
                'jenis_tabungan'    => $bmt_tabungan->nama,
                'detail'            => $detailToTabungan,
                'status'            => "active"
            ];

            if($this->tabunganReporsitory->createTabungan($dataToTabungan) == "success")
            {

                $detailToPenyimpananTabungan = [
                    'teller'            => Auth::user()->id,
                    'dari_rekening'     => "",
                    'untuk_rekening'    => json_decode(Auth::user()->detail)->id_rekening,
                    'jumlah'            => 0,
                    'saldo_awal'        => 0,
                    'saldo_akhir'       => 0
                ];
                $dataToPenyimpananTabungan = [
                    'id_user'       => $user_tabungan->id,
                    'id_tabungan'   => $nextIdTabungan,
                    'status'        => 'Setoran Awal',
                    'transaksi'     => $detailToPenyimpananTabungan,
                    'teller'        => Auth::user()->id    
                ];

                if($this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan) == "success")
                {
                    $detailToPenyimpananBMT = [
                        "jumlah"        => 0,
                        "saldo_awal"    => $bmt_tabungan->saldo,
                        "saldo_akhir"   => $bmt_tabungan->saldo + 0,
                        "id_pengajuan"  => $pengajuan->id
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user"   => $user_tabungan->id,
                        "id_bmt"    => $bmt_tabungan->id,
                        "status"    => "Setoran Awal",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller"    => Auth::user()->id
                    ];

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_simpanan_wajib;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_simpanan_wajib->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_simpanan_wajib->saldo + $jumlah_bayar_simpanan_wajib;
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_simpanan_wajib->id;
                    $dataToPenyimpananBMT['status'] = "Pembayaran Simpanan Wajib";
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_simpanan_pokok;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_simpanan_pokok->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_simpanan_pokok->saldo + $jumlah_bayar_simpanan_pokok;
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_simpanan_pokok->id;
                    $dataToPenyimpananBMT['status'] = "Pembayaran Simpanan Pokok";
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $detailToPenyimpananWajibPokok = [
                        'teller'            => Auth::user()->id,
                        'dari_rekening'     => "",
                        'untuk_rekening'    => 119,
                        'jumlah'            => $jumlah_bayar_simpanan_wajib,
                        'saldo_awal'        => 0,
                        'saldo_akhir'       => $jumlah_bayar_simpanan_pokok
                    ];
                    $dataToPenyimpananWajibPokok = [
                        'id_user'       => $user_tabungan->id,
                        'id_rekening'   => 119,
                        'status'        => 'Simpanan Wajib',
                        'transaksi'     => $detailToPenyimpananWajibPokok,
                        'teller'        => Auth::user()->id    
                    ];

                    $this->simpananReporsitory->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);

                    $detailToPenyimpananWajibPokok['untuk_rekening'] = 117;
                    $detailToPenyimpananWajibPokok['jumlah'] = $jumlah_bayar_simpanan_pokok;
                    $detailToPenyimpananWajibPokok['saldo_akhir'] = $jumlah_bayar_simpanan_pokok;
                    $dataToPenyimpananWajibPokok['id_rekening'] = 117;
                    $dataToPenyimpananWajibPokok['status'] = "Simpanan Pokok";
                    $dataToPenyimpananWajibPokok['transaksi'] = $detailToPenyimpananWajibPokok;

                    $this->simpananReporsitory->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);

                    $dataToWajibPokok = [
                        "wajib"     => $jumlah_bayar_simpanan_wajib,
                        "pokok"     => $jumlah_bayar_simpanan_pokok,
                        "khusus"    => 0
                    ];

                    $updateUser = User::where('id', $user_tabungan->id)->update([
                        'status'        => 2,
                        'wajib_pokok'   => json_encode($dataToWajibPokok),
                        'role'          => 'anggota'
                    ]);
                    $updatePengajuan = Pengajuan::where('id', $pengajuan->id)->update([
                        'status'    => "Disetujui",
                        'teller'    => Auth::user()->id
                    ]);
                    $bmt_pengonfirmasi->saldo = $bmt_pengonfirmasi->saldo + ($jumlah_bayar_simpanan_pokok + $jumlah_bayar_simpanan_wajib);
                    $bmt_simpanan_pokok->saldo = $bmt_simpanan_pokok->saldo + $jumlah_bayar_simpanan_pokok;
                    $bmt_simpanan_wajib->saldo = $bmt_simpanan_wajib->saldo + $jumlah_bayar_simpanan_wajib;
                    
                    if($updateUser && $updatePengajuan && $bmt_pengonfirmasi->save() && $bmt_simpanan_pokok->save() && $bmt_simpanan_wajib->save()) {
                        DB::commit();
                        $result = array('type' => 'success', 'message' => 'Konfirmasi akun baru berhasil.');
                    }
                    else {
                        DB::rollback();
                        $result = array('type' => 'error', 'message' => 'Konfirmasi akun baru gagal 3.');
                    }
                }
                else {
                    DB::rollback();
                    $result = array('type' => 'error', 'message' => 'Konfirmasi akun baru gagal 4.');
                }
            }
        }
        catch(Exception $e) {
            DB::rollback();
            $result = array('type' => 'error', 'message' => 'Konfirmasi akun baru gagal 5.');
        }

        return $result;
    }
}

?>