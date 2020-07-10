<?php

namespace App\Repositories;

use App\Tabungan;
use App\Rekening;
use App\BMT;
use App\Pengajuan;
use Auth;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;
use Illuminate\Support\Facades\DB;

class TransferTabunganRepositories {
    
    public function __construct(PengajuanReporsitories $pengajuanRepository,
        TabunganReporsitories $tabunganRepository, RekeningReporsitories $rekeningRepository
    ) {
        $this->pengajuanRepository = $pengajuanRepository;        
        $this->tabunganRepository = $tabunganRepository;        
        $this->rekeningRepository = $rekeningRepository;        
    }

    /** 
     * Pengajuan transfer antar tabungan
     * @return Response
    */
    public function pengajuanTransferAntarTabungan($data)
    {
        DB::beginTransaction();
        try {
            $tabungan = Tabungan::where('id_tabungan', $data->rekening_pengirim)->first();
            $rekening = Rekening::where('id_rekening', $tabungan->id_rekening)->first();
            $nominal = floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
            
            $saldo_akhir_tabungan = json_decode($tabungan->detail)->saldo - $nominal;

            if($saldo_akhir_tabungan > $nominal && $saldo_akhir_tabungan > json_decode($rekening->detail)->saldo_min)
            {
                $detailToPengajuan = array(
                    "tabungan_pengirim"     => $data->rekening_pengirim,
                    "tabungan_penerima"     => $data->rekening_penerima,
                    "user_pengirim"         => Auth::user()->id,
                    "user_penerima"         => $data->user_penerima,
                    "nominal"               => floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                    "keterangan"            => $data->keterangan,
                    "nama"                  => Auth::user()->nama
                );
                $dataToPengajuan = array(
                    "id_user"           => Auth::user()->id,
                    "id_rekening"       => $tabungan->id_rekening,
                    "jenis_pengajuan"   => "Transfer Antar Tabungan",
                    "status"            => "Menunggu Konfirmasi",
                    "kategori"          => "Transfer Antar Anggota",
                    "detail"            => $detailToPengajuan,
                    "teller"            => 0
                );

                $create_pengajuan = $this->pengajuanRepository->createPengajuan($dataToPengajuan);
                if($create_pengajuan['type'] == "success")
                {
                    DB::commit();
                    $response = array("type" => "success", "message" => "Pengajuan Transfer Antar Tabungan Berhasil Dibuat.");    
                }
                else
                {
                    DB::rollback();
                    $response = array("type" => "error", "message" => "Pengajuan Transfer Antar Tabungan Gagal Dibuat.");    
                }
            }
            elseif($saldo_akhir_tabungan < $nominal)
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Transfer Antar Tabungan Gagal Dibuat. Saldo tabungan anda tidak cukup.");    
            }
            elseif($saldo_akhir_tabungan < json_decode($rekening->detail)->saldo_min)
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Pengajuan Transfer Antar Tabungan Gagal Dibuat. Saldo tabungan anda melebihi limit transaksi.");    
            }
        }
        catch(Exception $ex) 
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Transfer Antar Tabungan Gagal Dibuat.");    
        }

        return $response;
    }

    /** 
     * Konfirmasi pengajuan transfer antar tabungan
     * @return Response
    */
    public function confirmPengajuanTransferTabungan($data)
    {
        DB::beginTransaction();
        try
        {
            $pengajuan = Pengajuan::where('id', $data->id_pengajuan)->first();
            $tabungan_penerima = Tabungan::where('id_tabungan', $data->crekening_penerima)->first();
            $tabungan_pengirim = Tabungan::where('id_tabungan', $data->crekening_pengirim)->first();
            $rekening_tabungan_penerima = Rekening::where('id', $tabungan_penerima->id_rekening)->first();
            $rekening_tabungan_pengirim = Rekening::where('id', $tabungan_pengirim->id_rekening)->first();
            $bmt_tabungan_pengirim = BMT::where('id_rekening', $rekening_tabungan_pengirim->id)->first();

            $saldo_awal_tabungan_pengirim = json_decode($tabungan_pengirim->detail)->saldo;
            $saldo_akhir_tabungan_pengirim = json_decode($tabungan_pengirim->detail)->saldo - floatval($data->cjumlah);
            $saldo_awal_tabungan_penerima = json_decode($tabungan_penerima->detail)->saldo;
            $saldo_akhir_tabungan_penerima = json_decode($tabungan_penerima->detail)->saldo + floatval($data->cjumlah);
            
            
            $detailToPenyimpananTabungan = array(
                "teller"            => Auth::user()->id,
                "dari_rekening"     => "[" . $tabungan_pengirim->id_tabungan . "] " . $tabungan_pengirim->jenis_tabungan,
                "untuk_rekening"    => "[" . $tabungan_penerima->id_tabungan . "] " . $tabungan_penerima->jenis_tabungan,
                "jumlah"            => $data->cjumlah,
                "saldo_awal"        => $saldo_awal_tabungan_penerima,
                "saldo_akhir"       => $saldo_akhir_tabungan_penerima
            );
            $dataToPenyimpananTabungan = array(
                "id_user"       => $tabungan_penerima->id_user,
                "id_tabungan"   => $tabungan_penerima->id,
                "status"        => "Transfer Antar Anggota",
                "transaksi"     => $detailToPenyimpananTabungan,
                "teller"        => Auth::user()->id
            );

            $this->tabunganRepository->insertPenyimpananTabungan($dataToPenyimpananTabungan);

            $detailToPenyimpananTabungan['saldo_awal'] = $saldo_awal_tabungan_pengirim;
            $detailToPenyimpananTabungan['saldo_akhir'] = $saldo_akhir_tabungan_pengirim;
            $dataToPenyimpananTabungan['id_tabungan'] = $tabungan_pengirim->id;
            $dataToPenyimpananTabungan['transaksi'] = $detailToPenyimpananTabungan;

            $this->tabunganRepository->insertPenyimpananTabungan($dataToPenyimpananTabungan);

            $dataToUpdateTabunganPengirim = array(
                "saldo" => $saldo_akhir_tabungan_pengirim,
                "id_pengajuan" => $pengajuan->id
            );
            $dataToUpdateTabunganPenerima = array(
                "saldo" => $saldo_akhir_tabungan_penerima,
                "id_pengajuan" => $pengajuan->id
            );
            
            $tabungan_pengirim->detail = json_encode($dataToUpdateTabunganPengirim);
            
            if($tabungan_pengirim->save())
            {
                $tabungan_penerima->detail = json_encode($dataToUpdateTabunganPenerima);
                $tabungan_penerima->save();

                $detailToPenyimpananBMT = array(
                    "jumlah"        => -$data->cjumlah,
                    "saldo_awal"    => $bmt_tabungan_pengirim->saldo,
                    "saldo_akhir"   => $bmt_tabungan_pengirim->saldo - $data->cjumlah,
                    "id_pengajuan"  => $pengajuan->id
                );
                $dataToPenyimpananBMT = array(
                    "id_user"   => $tabungan_pengirim->id_user,
                    "id_bmt"    => $bmt_tabungan_pengirim->id,
                    "status"    => "Transfer Antar Tabungan",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                );
    
                if($this->rekeningRepository->insertPenyimpananBMT($dataToPenyimpananBMT) == "success")
                {
                    $bmt_tabungan_pengirim->saldo = $bmt_tabungan_pengirim->saldo - $data->cjumlah;
                    $bmt_tabungan_pengirim->save();

                    $bmt_tabungan_penerima = BMT::where('id_rekening', $rekening_tabungan_penerima->id)->first();
                    $detailToPenyimpananBMT['jumlah'] = $data->cjumlah;
                    $detailToPenyimpananBMT['saldo_awal'] = $bmt_tabungan_penerima->saldo;
                    $detailToPenyimpananBMT['saldo_akhir'] = $bmt_tabungan_penerima->saldo + $data->cjumlah;
                    $dataToPenyimpananBMT['id_user'] = $tabungan_penerima->id_user;
                    $dataToPenyimpananBMT['id_bmt'] = $bmt_tabungan_penerima->id;
                    $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                    
                    $this->rekeningRepository->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $bmt_tabungan_penerima->saldo = $bmt_tabungan_penerima->saldo + $data->cjumlah;
                    $bmt_tabungan_penerima->save();
                    
                    $pengajuan->status = "Sudah Dikonfirmasi"; $pengajuan->teller = Auth::user()->id; $pengajuan->save();

                    DB::commit();
                    $response = array("type" => "success", "message" => "Pengajuan Transfer Antar Tabungan Berhasil Dikonfirmasi.");
                }
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pengajuan Transfer Antar Tabungan Gagal Dikonfirmasi.");
        }

        return $response;
    }

    /** 
     * Pay transfer antar tabungan
     * @return Response
    */
    public function payTransferAntarTabungan($data)
    {
        DB::beginTransaction();
        try
        {
            $tabungan_pengirim = Tabungan::where('id_tabungan', $data->rekening_pengirim)->first();
            $tabungan_penerima = Tabungan::where('id_tabungan', $data->rekening_penerima)->first();
            $rekening_tabungan_penerima = Rekening::where('id', $tabungan_penerima->id_rekening)->first();
            $rekening_tabungan_pengirim = Rekening::where('id', $tabungan_pengirim->id_rekening)->first();
            $bmt_tabungan_pengirim = BMT::where('id_rekening', $rekening_tabungan_pengirim->id)->first();

            $saldo_awal_tabungan_pengirim = json_decode($tabungan_pengirim->detail)->saldo;
            $saldo_akhir_tabungan_pengirim = json_decode($tabungan_pengirim->detail)->saldo - floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
            $saldo_awal_tabungan_penerima = json_decode($tabungan_penerima->detail)->saldo;
            $saldo_akhir_tabungan_penerima = json_decode($tabungan_penerima->detail)->saldo + floatval(preg_replace('/[^\d.]/', '', $data->jumlah));;
            
            $nominal = floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
            
            if($saldo_akhir_tabungan_pengirim > $nominal && $saldo_akhir_tabungan_pengirim > json_decode($rekening_tabungan_pengirim->detail)->saldo_min)
            {
                $detailToPenyimpananTabungan = array(
                    "teller"            => Auth::user()->id,
                    "dari_rekening"     => "[" . $tabungan_pengirim->id_tabungan . "] " . $tabungan_pengirim->jenis_tabungan,
                    "untuk_rekening"    => "[" . $tabungan_penerima->id_tabungan . "] " . $tabungan_penerima->jenis_tabungan,
                    "jumlah"            => floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                    "saldo_awal"        => $saldo_awal_tabungan_penerima,
                    "saldo_akhir"       => $saldo_akhir_tabungan_penerima
                );
                $dataToPenyimpananTabungan = array(
                    "id_user"       => $tabungan_penerima->id_user,
                    "id_tabungan"   => $tabungan_penerima->id,
                    "status"        => "Transfer Antar Anggota",
                    "transaksi"     => $detailToPenyimpananTabungan,
                    "teller"        => Auth::user()->id
                );

                $this->tabunganRepository->insertPenyimpananTabungan($dataToPenyimpananTabungan);

                $detailToPenyimpananTabungan['saldo_awal'] = $saldo_awal_tabungan_pengirim;
                $detailToPenyimpananTabungan['saldo_akhir'] = $saldo_akhir_tabungan_pengirim;
                $dataToPenyimpananTabungan['id_tabungan'] = $tabungan_pengirim->id;
                $dataToPenyimpananTabungan['transaksi'] = $detailToPenyimpananTabungan;

                $this->tabunganRepository->insertPenyimpananTabungan($dataToPenyimpananTabungan);

                $dataToUpdateTabunganPengirim = array(
                    "saldo" => $saldo_akhir_tabungan_pengirim,
                    "id_pengajuan" => null
                );
                $dataToUpdateTabunganPenerima = array(
                    "saldo" => $saldo_akhir_tabungan_penerima,
                    "id_pengajuan" => null
                );
                
                $tabungan_pengirim->detail = json_encode($dataToUpdateTabunganPengirim);
                
                if($tabungan_pengirim->save())
                {
                    $tabungan_penerima->detail = json_encode($dataToUpdateTabunganPenerima);
                    $tabungan_penerima->save();

                    $detailToPenyimpananBMT = array(
                        "jumlah"        => -floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                        "saldo_awal"    => $bmt_tabungan_pengirim->saldo,
                        "saldo_akhir"   => $bmt_tabungan_pengirim->saldo - floatval(preg_replace('/[^\d.]/', '', $data->jumlah)),
                        "id_pengajuan"  => null
                    );
                    $dataToPenyimpananBMT = array(
                        "id_user"   => $tabungan_pengirim->id_user,
                        "id_bmt"    => $bmt_tabungan_pengirim->id,
                        "status"    => "Transfer Antar Tabungan",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller"    => Auth::user()->id
                    );

                    if($this->rekeningRepository->insertPenyimpananBMT($dataToPenyimpananBMT) == "success")
                    {
                        $bmt_tabungan_pengirim->saldo = $bmt_tabungan_pengirim->saldo - floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                        $bmt_tabungan_pengirim->save();

                        $bmt_tabungan_penerima = BMT::where('id_rekening', $rekening_tabungan_penerima->id)->first();
                        $detailToPenyimpananBMT['jumlah'] = floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                        $detailToPenyimpananBMT['saldo_awal'] = $bmt_tabungan_penerima->saldo;
                        $detailToPenyimpananBMT['saldo_akhir'] = $bmt_tabungan_penerima->saldo + floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                        $dataToPenyimpananBMT['id_bmt'] = $bmt_tabungan_penerima->id;
                        $dataToPenyimpananBMT['transaksi'] = $detailToPenyimpananBMT;
                        
                        $this->rekeningRepository->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $bmt_tabungan_penerima->saldo = $bmt_tabungan_penerima->saldo + floatval(preg_replace('/[^\d.]/', '', $data->jumlah));
                        $bmt_tabungan_penerima->save();

                        DB::commit();
                        $response = array("type" => "success", "message" => "Pembayaran Transfer Antar Tabungan Berhasil.");
                    }
                }
            }
            elseif($saldo_akhir_tabungan_pengirim < $nominal)
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Transfer Antar Tabungan Gagal. Saldo tabungan anda tidak cukup.");    
            }
            elseif($saldo_akhir_tabungan_pengirim < json_decode($rekening_tabungan_pengirim->detail)->saldo_min)
            {
                DB::rollback();
                $response = array("type" => "error", "message" => "Transfer Antar Tabungan Gagal. Saldo tabungan anda melebihi limit transaksi.");    
            }
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pembayaran Transfer Antar Tabungan Gagal.");
        }

        return $response;
    }

    /** 
     * Transfer antar rekening tabungan
     * @return Response
    */
    public function transferAntarRekeningBMT($data)
    {
        DB::beginTransaction();
        try
        {
            /** 
             * Inset data to penyimpanan bmt table 
             * Using for history data
             * @return Null
            */
            $jenis = "Jurnal Lain";
            $dari = $data['dari'];
            $ke = $data['untuk'];

            $bmt_penerima = BMT::where('id_rekening', $ke)->select(['id','saldo'])->first();
            $bmt_pengirim = BMT::where('id_rekening', $dari)->select(['id','saldo'])->first();
            $rekening_penerima = Rekening::where('id', $ke)->first();
            $rekening_pengirim = Rekening::where('id', $dari)->first();
            $id_penerima = $bmt_penerima->id;
            $id_pengirim = $bmt_pengirim->id;
            $keterangan = "Jurnal Lain [" . $data['keterangan'] . "]";

            $id_user = Auth::user()->id;
            $id_bmt_penerima = $id_penerima;
            $id_bmt_pengirim = $id_pengirim;
            $status = $jenis;
            $saldo_penerima = $bmt_penerima->saldo;
            $saldo_pengirim = $bmt_pengirim->saldo;
            
            if(($saldo_pengirim - preg_replace('/[^\d.]/', '', $data['jumlah'])) < preg_replace('/[^\d.]/', '', $data['jumlah']))
            {
                DB::rollback();
                $result = array('type' => 'error', 'message' => 'Transfer Antar Rekening Gagal. Saldo rekening pengirim tidak cukup.');
            }
            else 
            {
                $detail = [
                    "jumlah"    => preg_replace('/[^\d.]/', '', $data['jumlah']),
                    "saldo_awal"=> floatval($saldo_penerima),
                    "saldo_akhir" => floatval($saldo_penerima) + preg_replace('/[^\d.]/', '', $data['jumlah']),
                    "dari"      => $dari,
                    "ke"    => $ke,
                    "keterangan"=> $keterangan
                ];
                $teller = Auth::user()->id;

                $dataToPenyimpananBMT = [
                    "id_user"   => $id_user,
                    "id_bmt"    => $id_penerima,
                    "status"    => $status,
                    "transaksi" => $detail,
                    "teller"    => $teller
                ];
                $dataToBMT = [
                    "id_rekening_pengirim"  => $dari,
                    "id_rekening_penerima"  => $ke,
                    "jumlah"                => preg_replace('/[^\d.]/', '', $data['jumlah'])
                ];
                if( 
                    $this->rekeningRepository->insertPenyimpananBMT($dataToPenyimpananBMT) == "success"
                )
                {
                    // $detail['jumlah'] = -preg_replace('/[^\d.]/', '', $data['jumlah']);
                    // $detail['saldo_awal'] = floatval($saldo_pengirim);
                    // $detail['saldo_akhir'] = floatval($saldo_pengirim) - preg_replace('/[^\d.]/', '', $data['jumlah']);
                    // $dataToPenyimpananBMT['id_bmt'] = $id_pengirim;
                    // $dataToPenyimpananBMT['transaksi'] = $detail;
                    $dataToPenyimpananBMT = $this->saveRiwayatRekeningPengirimJurnalLain($dataToPenyimpananBMT, $dataToBMT, $saldo_pengirim, $id_pengirim);
                    $this->rekeningRepository->insertPenyimpananBMT($dataToPenyimpananBMT);
                    
                    if($this->updateSaldoRekening($dataToBMT) == "success")
                    {
                        DB::commit();
                        $result = array('type' => 'success', 'message' => 'Transfer Antar Rekening Berhasil Dilakukan');
                    }
                    else
                    {
                        DB::rollback();
                        $result = array('type' => 'error', 'message' => 'Transfer Antar Rekening Gagal. Terjadi kesalahan saat update saldo rekening.');
                    }
                }
            }
        }
        catch(\Exception $e)
        {
            DB::rollback();
            $result = array('type' => 'error', 'message' => 'Transfer Antar Rekening Gagal.');
        }

        return $result;
    }

    /** 
     * Update rekening saldo in BMT table
     * @return Response
    */
    public function updateSaldoRekening($data)
    {
        $rekeningPengirim = BMT::where('id_rekening', $data['id_rekening_pengirim'])->select([ 'saldo', 'id_bmt', 'id_rekening', 'nama' ])->first();
        $rekeningPenerima = BMT::where('id_rekening', $data['id_rekening_penerima'])->select([ 'saldo', 'id_bmt', 'id_rekening', 'nama' ])->first();
            
        /**
         *  This block will execute when user choose rekening kepala 1 as penyeimbang
         *  This is will add saldo to teller and reduce saldo to penyeimbang 
        */
        if(explode(".", $rekeningPenerima->id_bmt)[0] == 1)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
    
                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
        }
        elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 2)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
    
                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
        }
        elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 3)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
    
                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
        }
        elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 4)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                
                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                
                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
    
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                return "success";
            }
        }
        elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 5)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                
                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                
                $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                if($shuBerjalan->saldo == "")
                {
                    $saldoShuBerjalan = 0;
                }
                else
                {
                    $saldoShuBerjalan = $shuBerjalan->saldo;
                }
                $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) + floatval($data['jumlah']) ]);
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
    
                return "success";
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                return "success";
            }
        }   
    }   

    /** 
     * Save riwayar rekening pengirim jurnal lain
     * @return Response
    */
    public function saveRiwayatRekeningPengirimJurnalLain($dataToPenyimpananBMT, $data, $saldo_pengirim, $id_pengirim)
    {
        $rekeningPengirim = BMT::where('id_rekening', $data['id_rekening_pengirim'])->select([ 'saldo', 'id_bmt', 'id_rekening', 'nama' ])->first();
        $rekeningPenerima = BMT::where('id_rekening', $data['id_rekening_penerima'])->select([ 'saldo', 'id_bmt', 'id_rekening', 'nama' ])->first();
           
        /**
         *  This block will execute when user choose rekening kepala 1 as penyeimbang
         *  This is will add saldo to teller and reduce saldo to penyeimbang 
        */
        if(explode(".", $rekeningPenerima->id_bmt)[0] == 1)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $jumlah = -floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $jumlah = floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $jumlah = floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $jumlah = -floatval($data['jumlah']);
            }
        }
        elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 2)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $jumlah = -floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $jumlah = -floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $jumlah = floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $jumlah = -floatval($data['jumlah']);
            }
        }
        elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 3)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $jumlah = -floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $jumlah = floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $jumlah = floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $jumlah = -floatval($data['jumlah']);
            }
        }
        elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 4)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $jumlah = floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $jumlah = -floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $jumlah = -floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $jumlah = -floatval($data['jumlah']);
            }
        }
        elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 5)
        {
            if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                $jumlah = floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                $jumlah = -floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                $jumlah = -floatval($data['jumlah']);
            }
            elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                $jumlah = -floatval($data['jumlah']);
            }
        }   

        $detail['jumlah'] = $jumlah;
        $detail['saldo_awal'] = $saldo_pengirim['saldo'];
        $detail['saldo_akhir'] = floatval($saldo_pengirim['saldo']) + $jumlah;
        $dataToPenyimpananBMT['transaksi'] = $detail;
        $dataToPenyimpananBMT['id_bmt'] = $id_pengirim;

        return $dataToPenyimpananBMT;
    }

}