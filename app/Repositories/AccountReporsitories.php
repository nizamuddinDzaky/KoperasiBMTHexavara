<?php

namespace App\Repositories;
use App\User;
use App\Pengajuan;
use App\Tabungan;
use App\Deposito;
use App\BMT;
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

class AccountReporsitories {

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
     * Get all account with specific type
     * @return Response
    */
    public function getAccount($type)
    {
        $account = User::where('tipe', $type)->where('status',2)->where('is_active',1)->get();
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
                    $dataToPenyimpananBMT['status'] = "Pembayaran Simpanan Wajib [Pendaftaran Anggota Baru]";
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_simpanan_pokok;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_simpanan_pokok->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_simpanan_pokok->saldo + $jumlah_bayar_simpanan_pokok;
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_simpanan_pokok->id;
                    $dataToPenyimpananBMT['status'] = "Pembayaran Simpanan Pokok [Pendaftaran Anggota Baru]";
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    // Untuk pencatatan rekening teller pengonfirmasi pendaftaran
                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_simpanan_pokok;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_pengonfirmasi->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_pengonfirmasi->saldo + $jumlah_bayar_simpanan_pokok;
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_pengonfirmasi->id;
                    $dataToPenyimpananBMT['status'] = "Pembayaran Simpanan Pokok [Pendaftaran Anggota Baru]";
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $bmt_pengonfirmasi->saldo = $bmt_pengonfirmasi->saldo + $jumlah_bayar_simpanan_pokok;
                    $bmt_pengonfirmasi->save();

                    $detailToPenyimpananBMT['jumlah'] = $jumlah_bayar_simpanan_wajib;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_pengonfirmasi->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_pengonfirmasi->saldo + $jumlah_bayar_simpanan_wajib;
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_pengonfirmasi->id;
                    $dataToPenyimpananBMT['status'] = "Pembayaran Simpanan Wajib [Pendaftaran Anggota Baru]";
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $bmt_pengonfirmasi->saldo = $bmt_pengonfirmasi->saldo + $jumlah_bayar_simpanan_wajib;

                    $detailToPenyimpananWajibPokok = [
                        'teller'            => Auth::user()->id,
                        'dari_rekening'     => "",
                        'untuk_rekening'    => "SIMPANAN WAJIB ANGGOTA",
                        'jumlah'            => $jumlah_bayar_simpanan_wajib,
                        'saldo_awal'        => 0,
                        'saldo_akhir'       => $jumlah_bayar_simpanan_wajib
                    ];
                    $dataToPenyimpananWajibPokok = [
                        'id_user'       => $user_tabungan->id,
                        'id_rekening'   => 119,
                        'status'        => 'Simpanan Wajib',
                        'transaksi'     => $detailToPenyimpananWajibPokok,
                        'teller'        => Auth::user()->id    
                    ];

                    $this->simpananReporsitory->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);

                    $detailToPenyimpananWajibPokok['untuk_rekening'] = "SIMPANAN POKOK ANGGOTA";
                    $detailToPenyimpananWajibPokok['jumlah'] = $jumlah_bayar_simpanan_pokok;
                    $detailToPenyimpananWajibPokok['saldo_akhir'] = $jumlah_bayar_simpanan_pokok;
                    $dataToPenyimpananWajibPokok['id_rekening'] = 117;
                    $dataToPenyimpananWajibPokok['status'] = "Simpanan Pokok";
                    $dataToPenyimpananWajibPokok['transaksi'] = $detailToPenyimpananWajibPokok;

                    $this->simpananReporsitory->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);

                    $dataToWajibPokok = [
                        "wajib"     => $jumlah_bayar_simpanan_wajib,
                        "pokok"     => $jumlah_bayar_simpanan_pokok,
                        "khusus"    => 0,
                        "margin"    => 0
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

    /** 
     * Tutup akun anggota
     * @return Response
    */
    public function closeAccount($data)
    {
        return $data;
    }

    /** 
     * Pencairan saldo rekening ditutup
     * @return Response
    */
    public function pencairanSaldoRekening($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = PengajuanReporsitories::findPengajuan($data->id_pengajuan);
            $tabungan = $this->tabunganReporsitory->getUserTabungan($id_user=$pengajuan->id_user, $id="", $status="active");
            $deposito = $this->depositoReporsitory->getUserDeposito($status="active", $user=$pengajuan->id_user);
            $user_rekening = User::where('id', $pengajuan->id_user)->first();
            $bmt_teller_pencairan = BMT::where('id_rekening', json_decode(Auth::user()->detail)->id_rekening)->first();

            $this->exportKeteranganAnggotaKeluar($pengajuan);

            if(count($tabungan) > 0)
            {
                foreach($tabungan as $rekening_tabungan)
                {

                    if($bmt_teller_pencairan->saldo > json_decode($rekening_tabungan->detail)->saldo)
                    {
                        $detailToPenyimpananTabungan = [
                            "teller"            => Auth::user()->id,
                            "dari_rekening"     => $rekening_tabungan->id_rekening,
                            "untuk_rekening"    => "",
                            "jumlah"            => json_decode($rekening_tabungan->detail)->saldo,
                            "saldo_awal"        => json_decode($rekening_tabungan->detail)->saldo,
                            "saldo_akhir"       => 0
                        ];
                        $dataToPenyimpananTabungan = [
                            "id_user"       => $rekening_tabungan->id_user,
                            "id_tabungan"   => $rekening_tabungan->id,
                            "status"        => "Pencairan Tabungan",
                            "transaksi"     => $detailToPenyimpananTabungan,
                            "teller"        => Auth::user()->id
                        ];
                        
                        $insertPenyimpananTabungan = $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);

                        $bmt_tabungan = BMT::where('id_rekening', $rekening_tabungan->id_rekening)->first();
                        $detailToPenyimpananBMT = [
                            "jumlah"            => json_decode($rekening_tabungan->detail)->saldo,
                            "saldo_awal"        => $bmt_tabungan->saldo,
                            "saldo_akhir"       => $bmt_tabungan->saldo - json_decode($rekening_tabungan->detail)->saldo,
                            "id_pengajuan"      => $pengajuan->id
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"       => $rekening_tabungan->id_user,
                            "id_bmt"        => $bmt_tabungan->id,
                            "status"        => "Pencairan Tabungan",
                            "transaksi"     => $detailToPenyimpananBMT,
                            "teller"        => Auth::user()->id
                        ];

                        $insertPenyimpananBMTTabungan = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $detailToPenyimpananBMT = [
                            "jumlah"            => json_decode($rekening_tabungan->detail)->saldo,
                            "saldo_awal"        => $bmt_teller_pencairan->saldo,
                            "saldo_akhir"       => $bmt_teller_pencairan->saldo - json_decode($rekening_tabungan->detail)->saldo,
                            "id_pengajuan"      => $pengajuan->id
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"       => $rekening_tabungan->id_user,
                            "id_bmt"        => $bmt_teller_pencairan->id,
                            "status"        => "Pencairan Tabungan",
                            "transaksi"     => $detailToPenyimpananBMT,
                            "teller"        => Auth::user()->id
                        ];

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $dataToUpdateTabungan = [
                            "saldo"         => 0,
                            "id_pengajuan"  => $pengajuan->id
                        ];

                        $updateTabungan = Tabungan::where('id', $rekening_tabungan->id)->update([
                            "detail"    => json_encode($dataToUpdateTabungan),
                            "status"    => "not active"
                        ]);
                        $updateBMTTabungan = BMT::where('id_rekening', $rekening_tabungan->id_rekening)->update([
                            "saldo" => $bmt_tabungan->saldo - json_decode($rekening_tabungan->detail)->saldo
                        ]);
                        $bmt_teller_pencairan->saldo = $bmt_teller_pencairan->saldo - json_decode($rekening_tabungan->detail)->saldo;
                        $update_bmt_teller_pencairan_tabungan = $bmt_teller_pencairan->save();
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pencairan Penutupan Rekening Gagal. Saldo " . $bmt_teller_pencairan->nama . " Tidak Cukup.");
                    }
                }
            }

            if(count($deposito) > 0)
            {
                foreach($deposito as $rekening_deposito)
                {
                    if($bmt_teller_pencairan->saldo > json_decode($rekening_deposito->detail)->jumlah)
                    {
                        $detailToPenyimpananDeposito = [
                            "teller"            => Auth::user()->id,
                            "dari_rekening"     => $rekening_deposito->id_rekening,
                            "untuk_rekening"    => "",
                            "jumlah"            => json_decode($rekening_deposito->detail)->jumlah,
                            "saldo_awal"        => json_decode($rekening_deposito->detail)->jumlah,
                            "saldo_akhir"       => 0
                        ];
                        $dataToPenyimpananDeposito = [
                            "id_user"       => $rekening_deposito->id_user,
                            "id_deposito"   => $rekening_deposito->id,
                            "status"        => "Pencairan Deposito",
                            "transaksi"     => $detailToPenyimpananDeposito,
                            "teller"        => Auth::user()->id
                        ];

                        $insertToPenyimpananDeposito = $this->depositoReporsitory->insertToPenyimpananDeposito($dataToPenyimpananDeposito);

                        $bmt_deposito = BMT::where('id_rekening', $rekening_deposito->id_rekening)->first();
                        $detailToPenyimpananBMT = [
                            "jumlah"            => json_decode($rekening_deposito->detail)->saldo,
                            "saldo_awal"        => $bmt_deposito->saldo,
                            "saldo_akhir"       => $bmt_deposito->saldo - json_decode($rekening_deposito->detail)->saldo,
                            "id_pengajuan"      => $pengajuan->id
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"       => $rekening_deposito->id_user,
                            "id_bmt"        => $bmt_deposito->id,
                            "status"        => "Pencairan Deposito",
                            "transaksi"     => $detailToPenyimpananBMT,
                            "teller"        => Auth::user()->id
                        ];

                        $insertPenyimpananBMTDeposito = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $detailToPenyimpananBMT = [
                            "jumlah"            => json_decode($rekening_deposito->detail)->saldo,
                            "saldo_awal"        => $bmt_teller_pencairan->saldo,
                            "saldo_akhir"       => $bmt_teller_pencairan->saldo - json_decode($rekening_deposito->detail)->saldo,
                            "id_pengajuan"      => $pengajuan->id
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"       => $rekening_deposito->id_user,
                            "id_bmt"        => $bmt_teller_pencairan->id,
                            "status"        => "Pencairan Deposito",
                            "transaksi"     => $detailToPenyimpananBMT,
                            "teller"        => Auth::user()->id
                        ];

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $updateDeposito = Deposito::where('id', $rekening_deposito->id)->update([
                            "status"    => "not active"
                        ]);
                        $updateBMTDeposito = BMT::where('id_rekening', $rekening_deposito->id_rekening)->update([
                            "saldo" => $bmt_deposito->saldo - json_decode($rekening_deposito->detail)->jumlah
                        ]);

                        $bmt_teller_pencairan->saldo = $bmt_teller_pencairan->saldo - json_decode($rekening_deposito->detail)->jumlah;
                        $update_bmt_teller_pencairan_deposito = $bmt_teller_pencairan->save();
                    }
                    else
                    {
                        DB::rollback();
                        $response = array("type" => "error", "message" => "Pencairan Penutupan Rekening Gagal. Saldo " . $bmt_teller_pencairan->nama . " Tidak Cukup.");
                    }
                }
            }

            if(isset(json_decode($user_rekening->wajib_pokok)->wajib) && $bmt_teller_pencairan->saldo > json_decode($user_rekening->wajib_pokok)->wajib)
            {
                $bmt_simpanan_wajib = BMT::where('id_rekening', 119)->first();
                
                $detailToPenyimpananWajibPokok = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => 119,
                    "untuk_rekening"    => "",
                    "jumlah"            => json_decode($user_rekening->wajib_pokok)->wajib,
                    "saldo_awal"        => json_decode($user_rekening->wajib_pokok)->wajib,
                    "saldo_akhir"       => 0
                ];
                $dataToPenyimpananWajibPokok = [
                    "id_user"       => $pengajuan->id_user,
                    "id_rekening"   => 119,
                    "status"        => "Pencairan Simpanan Wajib",
                    "transaksi"     => $detailToPenyimpananWajibPokok,
                    "teller"        => Auth::user()->id
                ];
                
                $insertPenyimpananWajibPokokWajib = $this->simpananReporsitory->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);

                $detailToPenyimpananBMT = [
                    "jumlah"            => json_decode($user_rekening->wajib_pokok)->wajib,
                    "saldo_awal"        => $bmt_teller_pencairan->saldo,
                    "saldo_akhir"       => $bmt_teller_pencairan->saldo - json_decode($user_rekening->wajib_pokok)->wajib,
                    "id_pengajuan"      => $pengajuan->id
                ];
                $dataToPenyimpananBMT = [
                    "id_user"       => $user_rekening->id,
                    "id_bmt"        => $bmt_teller_pencairan->id,
                    "status"        => "Pencairan Simpanan Wajib",
                    "transaksi"     => $detailToPenyimpananBMT,
                    "teller"        => Auth::user()->id
                ];

                $insertPenyimpananBMTWajib = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                $detailToPenyimpananBMT['saldo_awal'] = $bmt_simpanan_wajib->saldo;
                $detailToPenyimpananBMT['saldo_akhir'] = $bmt_simpanan_wajib->saldo - json_decode($user_rekening->wajib_pokok)->wajib;
                $dataToPenyimpananBMT['id_bmt'] = $bmt_simpanan_wajib->id;
                $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                $dataToUpdateWajibPokok = [
                    "wajib" => 0,
                    "pokok" => json_decode($user_rekening->wajib_pokok)->pokok,
                    "khusus" => json_decode($user_rekening->wajib_pokok)->khusus
                ];

                $bmt_simpanan_wajib->saldo = $bmt_simpanan_wajib->saldo - json_decode($user_rekening->wajib_pokok)->wajib;
                $update_bmt_simpanan_wajib = $bmt_simpanan_wajib->save();

                $bmt_teller_pencairan->saldo = $bmt_teller_pencairan->saldo - json_decode($user_rekening->wajib_pokok)->wajib;
                $update_bmt_teller_wajib = $bmt_teller_pencairan->save();

                $user_rekening->wajib_pokok = json_encode($dataToUpdateWajibPokok);
                $user_rekening->save();


            }

            if(isset(json_decode($user_rekening->wajib_pokok)->pokok) && $bmt_teller_pencairan->saldo > json_decode($user_rekening->wajib_pokok)->pokok)
            {
                $bmt_simpanan_pokok = BMT::where('id_rekening', 117)->first();
                
                $detailToPenyimpananWajibPokok = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => 117,
                    "untuk_rekening"    => "",
                    "jumlah"            => json_decode($user_rekening->wajib_pokok)->pokok,
                    "saldo_awal"        => json_decode($user_rekening->wajib_pokok)->pokok,
                    "saldo_akhir"       => 0
                ];
                $dataToPenyimpananWajibPokok = [
                    "id_user"       => $pengajuan->id_user,
                    "id_rekening"   => 117,
                    "status"        => "Pencairan Simpanan Pokok",
                    "transaksi"     => $detailToPenyimpananWajibPokok,
                    "teller"        => Auth::user()->id
                ];
                
                $insertPenyimpananWajibPokokWajibPokok = $this->simpananReporsitory->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);

                $detailToPenyimpananBMT = [
                    "jumlah"            => json_decode($user_rekening->wajib_pokok)->pokok,
                    "saldo_awal"        => $bmt_teller_pencairan->saldo,
                    "saldo_akhir"       => $bmt_teller_pencairan->saldo - json_decode($user_rekening->wajib_pokok)->pokok,
                    "id_pengajuan"      => $pengajuan->id
                ];
                $dataToPenyimpananBMT = [
                    "id_user"       => $user_rekening->id,
                    "id_bmt"        => $bmt_teller_pencairan->id,
                    "status"        => "Pencairan Simpanan Pokok",
                    "transaksi"     => $detailToPenyimpananBMT,
                    "teller"        => Auth::user()->id
                ];

                $insertPenyimpananBMTPokok = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                $detailToPenyimpananBMT['saldo_awal'] = $bmt_simpanan_pokok->saldo;
                $detailToPenyimpananBMT['saldo_akhir'] = $bmt_simpanan_pokok->saldo - json_decode($user_rekening->wajib_pokok)->pokok;
                $dataToPenyimpananBMT['id_bmt'] = $bmt_simpanan_pokok->id;
                $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                
                $dataToUpdateWajibPokok = [
                    "wajib" => json_decode($user_rekening->wajib_pokok)->wajib,
                    "pokok" => 0,
                    "khusus" => json_decode($user_rekening->wajib_pokok)->khusus
                ];

                $bmt_simpanan_pokok->saldo = $bmt_simpanan_pokok->saldo - json_decode($user_rekening->wajib_pokok)->pokok;
                $update_bmt_simpanan_pokok = $bmt_simpanan_pokok->save();

                $bmt_teller_pencairan->saldo = $bmt_teller_pencairan->saldo - json_decode($user_rekening->wajib_pokok)->pokok;
                $update_bmt_teller_pokok = $bmt_teller_pencairan->save();


                $user_rekening->wajib_pokok = json_encode($dataToUpdateWajibPokok);
                $user_rekening->save();


            }

            if(isset(json_decode($user_rekening->wajib_pokok)->khusus) && $bmt_teller_pencairan->saldo > json_decode($user_rekening->wajib_pokok)->khusus)
            {
                $bmt_simpanan_khusus = BMT::where('id_rekening', 120)->first();
                
                $detailToPenyimpananWajibPokok = [
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => 120,
                    "untuk_rekening"    => "",
                    "jumlah"            => json_decode($user_rekening->wajib_pokok)->khusus,
                    "saldo_awal"        => json_decode($user_rekening->wajib_pokok)->khusus,
                    "saldo_akhir"       => 0
                ];
                $dataToPenyimpananWajibPokok = [
                    "id_user"       => $pengajuan->id_user,
                    "id_rekening"   => 117,
                    "status"        => "Pencairan Simpanan Khusus",
                    "transaksi"     => $detailToPenyimpananWajibPokok,
                    "teller"        => Auth::user()->id
                ];
                
                $insertPenyimpananWajibPokokKhusus = $this->simpananReporsitory->insertPenyimpananWajibPokok($dataToPenyimpananWajibPokok);

                $detailToPenyimpananBMT = [
                    "jumlah"            => json_decode($user_rekening->wajib_pokok)->khusus,
                    "saldo_awal"        => $bmt_teller_pencairan->saldo,
                    "saldo_akhir"       => $bmt_teller_pencairan->saldo - json_decode($user_rekening->wajib_pokok)->khusus,
                    "id_pengajuan"      => $pengajuan->id
                ];
                $dataToPenyimpananBMT = [
                    "id_user"       => $user_rekening->id,
                    "id_bmt"        => $bmt_teller_pencairan->id,
                    "status"        => "Pencairan Simpanan Khusus",
                    "transaksi"     => $detailToPenyimpananBMT,
                    "teller"        => Auth::user()->id
                ];

                $insertPenyimpananBMTKhusus = $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                $detailToPenyimpananBMT['saldo_awal'] = $bmt_simpanan_khusus->saldo;
                $detailToPenyimpananBMT['saldo_akhir'] = $bmt_simpanan_khusus->saldo - json_decode($user_rekening->wajib_pokok)->khusus;
                $dataToPenyimpananBMT['id_bmt'] = $bmt_simpanan_khusus->id;
                $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;

                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                
                $dataToUpdateWajibPokok = [
                    "wajib" => json_decode($user_rekening->wajib_pokok)->wajib,
                    "pokok" => json_decode($user_rekening->wajib_pokok)->pokok,
                    "khusus" => 0
                ];

                $bmt_simpanan_khusus->saldo = $bmt_simpanan_khusus->saldo - json_decode($user_rekening->wajib_pokok)->khusus;
                $update_bmt_simpanan_khusus = $bmt_simpanan_khusus->save();

                $bmt_teller_pencairan->saldo = $bmt_teller_pencairan->saldo - json_decode($user_rekening->wajib_pokok)->khusus;
                $update_bmt_teller_khusus = $bmt_teller_pencairan->save();

                $user_rekening->wajib_pokok = json_encode($dataToUpdateWajibPokok);
                $user_rekening->save();



            }


            $user_rekening->status = 1;
            $user_rekening->is_active = 0;

            $pengajuan->status = "Sudah Dikonfirmasi"; $pengajuan->teller = Auth::user()->id;

        if (count($deposito) > 0 && count($tabungan) > 0 )
        {
                if ($update_bmt_simpanan_khusus && $update_bmt_simpanan_pokok && $update_bmt_simpanan_wajib && isset($update_bmt_teller_pencairan_deposito) && $update_bmt_teller_pencairan_deposito && isset($update_bmt_teller_pencairan_tabungan) && $update_bmt_teller_pencairan_tabungan && $update_bmt_teller_khusus & $update_bmt_teller_pokok && $update_bmt_teller_wajib ) {
                    $user_rekening->save();
                    $pengajuan->save();
                    DB::commit();
                    $response = array("type" => "success", "message" => "Pencairan Penutupan Rekening Berhasil");
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pencairan Penutupan Rekening Gagal. Pastikan saldo anda cukup untuk pencairan");
                }
            }
            else if (count($tabungan) > 0 ) {
                if ($update_bmt_simpanan_khusus && $update_bmt_simpanan_pokok && $update_bmt_simpanan_wajib && isset($update_bmt_teller_pencairan_tabungan) && $update_bmt_teller_pencairan_tabungan  && $update_bmt_teller_khusus & $update_bmt_teller_pokok && $update_bmt_teller_wajib ) {
                    $user_rekening->save();
                    $pengajuan->save();
                    DB::commit();
                    $response = array("type" => "success", "message" => "Pencairan Penutupan Rekening Berhasil");
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pencairan Penutupan Rekening Gagal. Pastikan saldo anda cukup untuk pencairan");
                }

            }
            elseif (count($deposito) > 0) {
                if ($update_bmt_simpanan_khusus && $update_bmt_simpanan_pokok && $update_bmt_simpanan_wajib && isset($update_bmt_teller_pencairan_deposito) && $update_bmt_teller_pencairan_deposito  && $update_bmt_teller_khusus & $update_bmt_teller_pokok && $update_bmt_teller_wajib) {
                    $user_rekening->save();
                    $pengajuan->save();
                    DB::commit();
                    $response = array("type" => "success", "message" => "Pencairan Penutupan Rekening Berhasil");
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pencairan Penutupan Rekening Gagal. Pastikan saldo anda cukup untuk pencairan");
                }
            }
}
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pencairan Penutupan Rekening Gagal");
        }
        return $response;
    }

    /** 
     * Export keterangan keluar anggota
     * @return Response
    */
    public function exportKeteranganAnggotaKeluar($data)
    {
        $user = User::where('id', json_decode($data['detail'])->id)->first();
        $tabungan = Tabungan::where('id_user', $user->id)->get();
        $deposito = Deposito::where('id_user', $user->id)->get();
        $total = 0;
        $data_template_row = array();
        foreach ($tabungan as $value) {
            array_push(
                $data_template_row, array('rekening_title' => $value['jenis_tabungan'], 'rekening_created_at' => Carbon::parse($value['created_at'])->format('d-m-Y'), 'rekening_saldo' => number_format(json_decode($value['detail'])->saldo,2))
            );
            $total += json_decode($value['detail'])->saldo;
        }
        foreach ($deposito as $deposit) {
            array_push(
                $data_template_row, array('rekening_title' => $deposit['jenis_deposito'], 'rekening_created_at' => Carbon::parse($deposit['created_at'])->format('d-m-Y'), 'rekening_saldo' => number_format(json_decode($deposit['detail'])->jumlah,2))
            );
            $total += json_decode($deposit['detail'])->jumlah;
        }

        $total = $total + json_decode($user->wajib_pokok)->pokok + json_decode($user->wajib_pokok)->wajib + json_decode($user->wajib_pokok)->khusus;
        $total = number_format($total, 2);

        $export_data = array(
            "user"                              => preg_replace('/[^A-Za-z0-9_\.-]/', ' ',$user->nama),
            "id"                                => $user->id,
            "data_template"                     => array(
                "nik"   => $user->no_ktp,
                "nama_user" => strtoupper($user->nama),
                "tanggal_keluar"    => $this->helperRepository->getDayName() . ", " . Carbon::now()->format("d") . " " . $this->helperRepository->getMonthName() . " " . Carbon::now()->format("Y H:i A P"),
                "jumlah_simpanan_pokok"    => number_format(json_decode($user->wajib_pokok)->pokok,2),
                "jumlah_simpanan_wajib"    => number_format(json_decode($user->wajib_pokok)->wajib,2),
                "jumlah_simpanan_sukarela"    => number_format(json_decode($user->wajib_pokok)->khusus,2),
                "pihak_bmt"         => "SUNOYO",
                "cabang"            => "Surabaya",
                "tanggal_penetapan" => Carbon::now()->format('d') . " " . $this->helperRepository->getMonthName() . " " . Carbon::now()->format("Y"),
                "saldo"             => $total
            ),
            "data_template_row"                 => $data_template_row,  
            "data_template_row_title"           => "rekening_title",
            "template_path"                     => public_path('template/anggota_keluar.docx')
        );

        $this->exportRepository->exportWord("anggota_keluar", $export_data);

        return $export_data;
    }
}

?>