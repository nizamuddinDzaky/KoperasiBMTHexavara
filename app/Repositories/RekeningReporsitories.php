<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Pengajuan;
use App\Rekening;
use App\BMT;
use App\PenyimpananBMT;
use Carbon\Carbon;

class RekeningReporsitories {

    /** 
     * Get all rekening
     * @return Response
    */
    public function getRekening($name="", $type="", $sort="")
    {
        $rekening = "SELECT rekening.*, bmt.saldo FROM rekening INNER JOIN bmt ON rekening.id=bmt.id_rekening";

        if($name != "") {
            $rekening .= " AND nama_rekening='" . $name . "'";
        }

        if($type != "") {
            $rekening .= " AND tipe_rekening='" . $type . "'";
        }

        if($sort != "") {
            $rekening .= " ORDER BY " . $sort . " ASC";
        }

        $data = DB::select( DB::raw($rekening) );
        
        return $data;
    }

    /** 
     * Get rekening with several category excluded
     * @return Response
    */
    public function getRekeningExcludedCategory($excluded, $type="", $sort="")
    {
        $rekening = "SELECT rekening.*, bmt.saldo FROM rekening INNER JOIN bmt ON rekening.id=bmt.id_rekening WHERE rekening.nama_rekening NOT LIKE '%" . $excluded[0] . "%'";
        for($i=1; $i < count($excluded); $i++) {
            $rekening .= " AND rekening.nama_rekening NOT LIKE '%" . $excluded[$i] . "%'";
        }

        if($type != "") {
            $rekening .= " AND tipe_rekening='" . $type . "'";
        }

        if($sort != "") {
            $rekening .= " ORDER BY " . $sort . " ASC";
        }

        $data = DB::select( DB::raw($rekening) );
        
        return $data;
    }

    public function getRekeningExcludedCategoryIDRekening($excluded, $type="", $sort="")
    {
        $rekening = "SELECT rekening.*, bmt.saldo FROM rekening INNER JOIN bmt ON rekening.id=bmt.id_rekening WHERE rekening.id_rekening <>'".$excluded[0]."'";
        for($i=1; $i < count($excluded); $i++) {
            $rekening .= " AND rekening.id_rekening <> '" . $excluded[$i] . "'";
        }

        if($type != "") {
            $rekening .= " AND tipe_rekening='" . $type . "'";
        }

        if($sort != "") {
            $rekening .= " ORDER BY " . $sort . " ASC";
        }

        $data = DB::select( DB::raw($rekening) );

        return $data;
    }

    /** 
     * Pengeluaran pemasukan rekening 
     * use in teller dashboard
     * @return Response
    */
    public function transferRekening($data, $type)
    {
        DB::beginTransaction();

        try
        {
            foreach($data as $data) {
                /** 
                 * Inset data to penyimpanan bmt table 
                 * Using for history data
                 * @return Null
                */
                if($data['tipe'] == 1) {
                    $jenis = "Pemasukan";
                    $dari = $data['dari'];

                    if($data['tujuan'] != null) {
                        $ke = $data['tujuan'];
                        $eksekutor = "Admin"; // Who is do it
                    } else {
                        $ke = json_decode(Auth::user()->detail)->id_rekening;
                        $eksekutor = "Teller"; // Who is do it
                    }



                    $bmt_penerima = BMT::where('id_rekening', $ke)->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $dari)->select('id')->first();
                    $rekening_penerima = Rekening::where('id', $ke)->first();
                    $rekening_pengirim = Rekening::where('id', $dari)->first();
                    $id_penerima = $bmt_penerima->id;
                    $id_pengirim = $bmt_pengirim->id;
                    $keterangan = "Pemasukan - KT [" . $data['keterangan'] . "]";
                } else {
                    $jenis = "Pengeluaran";

                    if($data['tujuan'] != null) {
                        $dari = $data['tujuan'];
                        $eksekutor = "Admin"; // Who is do it
                    } else {
                        $dari = json_decode(Auth::user()->detail)->id_rekening;
                        $eksekutor = "Teller"; // Who is do it
                    }

                    $ke = $data['dari'];
                    $bmt_penerima = BMT::where('id_rekening', $ke)->select('id')->first();
                    $bmt_pengirim = BMT::where('id_rekening', $dari)->select('id')->first();
                    $rekening_penerima = Rekening::where('id', $ke)->first();
                    $rekening_pengirim = Rekening::where('id', $dari)->first();

                    $id_penerima = $bmt_penerima->id;
                    $id_pengirim = $bmt_pengirim->id;
                    $keterangan = "Pengeluaran - KK [" . $data['keterangan'] . "]";
                }

                $id_user = Auth::user()->id;
                $id_bmt_penerima = $id_penerima;
                $id_bmt_pengirim = $id_pengirim;
                if($type == "Pemasukan Kas" || $type == "Pengeluaran Kas")
                {
                    $status = $data['keterangan'];
                }
                else
                {
                    $status = $type;
                }

                $saldo_penerima = BMT::where('id', $id_bmt_penerima)->select('saldo')->first();
                $saldo_pengirim = BMT::where('id', $id_bmt_pengirim)->select('saldo')->first();
                
                if($saldo_penerima['saldo'] == "") {
                    $saldo_penerima['saldo'] = 0;
                }
                if($saldo_pengirim['saldo'] == "") {
                    $saldo_pengirim['saldo'] = 0;
                }

                if($data['tipe'] == 1) {
                    $saldoAkhir = floatval($saldo_penerima['saldo']) + preg_replace('/[^\d.]/', '', $data['jumlah']);
                    $rekening_pengirim_id = explode(".", $rekening_pengirim->id_rekening);
                    if ($rekening_pengirim_id[0] == "4" || $rekening_pengirim_id[0] == "5" ){
                        $shuBerjalan = BMT::where('id', 344)->first();
                        $saldoAkhirShuBerjalan = $shuBerjalan->saldo + preg_replace('/[^\d.]/', '', $data['jumlah']);

                        $detailSHU = [
                            "jumlah"    => preg_replace('/[^\d.]/', '', $data['jumlah']),
                            "saldo_awal"=> floatval($shuBerjalan->saldo),
                            "saldo_akhir" => $saldoAkhirShuBerjalan,
                            "id_pengajuan"=> null
                        ];
                        $teller = Auth::user()->id;

                        $dataToPenyimpananBMTSHU = [
                            "id_user"   => $id_user,
                            "id_bmt"    => 344,
                            "status"    => $status,
                            "transaksi" => $detailSHU,
                            "teller"    => $teller
                        ];

                        $this->insertPenyimpananBMT($dataToPenyimpananBMTSHU);


                    }
                }
                else{
                    $rekening_penerima_id = explode(".", $rekening_penerima->id_rekening);
                    if($rekening_penerima_id[0] == "2" || $rekening_penerima_id[0] == "3" || $rekening_penerima_id[0] == "4")
                    {
                        $saldoAkhir = floatval($saldo_penerima['saldo']) - preg_replace('/[^\d.]/', '', $data['jumlah']);
                    }
                    else{
                        $saldoAkhir = floatval($saldo_penerima['saldo']) + preg_replace('/[^\d.]/', '', $data['jumlah']);
                    }

                    if ($rekening_penerima_id[0] == "4" || $rekening_penerima_id[0] == "5" ){
                        $shuBerjalan = BMT::where('id', 344)->first();
                        $saldoAkhirShuBerjalan = $shuBerjalan->saldo - preg_replace('/[^\d.]/', '', $data['jumlah']);

                        $detailSHU = [
                            "jumlah"    => preg_replace('/[^\d.]/', '', $data['jumlah']),
                            "saldo_awal"=> floatval($shuBerjalan->saldo),
                            "saldo_akhir" => $saldoAkhirShuBerjalan,
                            "id_pengajuan"=> null
                        ];
                        $teller = Auth::user()->id;

                        $dataToPenyimpananBMTSHU = [
                            "id_user"   => $id_user,
                            "id_bmt"    => 344,
                            "status"    => $status,
                            "transaksi" => $detailSHU,
                            "teller"    => $teller
                        ];

                        $this->insertPenyimpananBMT($dataToPenyimpananBMTSHU);

                    }
                }

                $detail = [
                    "jumlah"    => preg_replace('/[^\d.]/', '', $data['jumlah']),
                    "saldo_awal"=> floatval($saldo_penerima['saldo']),
                    "saldo_akhir" => $saldoAkhir,
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
                    "jenis_transaksi"       => $jenis,
                    "id_rekening_pengirim"  => $dari,
                    "id_rekening_penerima"  => $ke,
                    "jumlah"                => preg_replace('/[^\d.]/', '', $data['jumlah'])
                ];

                if( 
                    $this->insertPenyimpananBMT($dataToPenyimpananBMT) == "success" &&
                    $this->updateSaldoRekening($dataToBMT, $jenis, $eksekutor) == "success"
                )
                {
                    $dataToPenyimpananBMT = $this->saveRiwayatRekeningPengirimJurnalLain($dataToPenyimpananBMT, $dataToBMT, $saldo_pengirim, $id_pengirim, $eksekutor);
                    $this->insertPenyimpananBMT($dataToPenyimpananBMT);

                    DB::commit();
                    $result = array('type' => 'success', 'message' => 'Transfer Pengeluaran/Pemasukan Berhasil Dilakukan');
                }
                else
                { 
                    $result = array('type' => 'error', 'message' => 'Transfer Pengeluaran/Pemasukan Gagal. Pastikan data benar & saldo rekening penyeimbang cukup');
                }
            }
        }
        catch(\Exception $e)
        {
            DB::rollback();

            $result = array('type' => 'error', 'message' => 'Transfer Pengeluaran/Pemasukan Gagal.');
        }

        return $result;
    }

    /** 
     * Insert data to penyimpanan BMT table
     * @return Response
    */
    public function insertPenyimpananBMT($data)
    {
        $penyimpanan = new PenyimpananBMT();
        $penyimpanan->id_user = $data['id_user'];
        $penyimpanan->id_bmt = $data['id_bmt'];
        $penyimpanan->status = $data['status'];
        $penyimpanan->transaksi = json_encode($data['transaksi']);
        $penyimpanan->teller = $data['teller'];

        if($penyimpanan->save()) {
            return "success";
        }
        else {
            return "error";
        }
    }

    /** 
     * Update rekening saldo in BMT table
     * @return Response
    */
    public function updateSaldoRekening($data, $type, $teller)
    {
        $rekeningPengirim = BMT::where('id_rekening', $data['id_rekening_pengirim'])->select([ 'saldo', 'id_bmt', 'id_rekening', 'nama' ])->first();
        $rekeningPenerima = BMT::where('id_rekening', $data['id_rekening_penerima'])->select([ 'saldo', 'id_bmt', 'id_rekening', 'nama' ])->first();
       
        if($data['jenis_transaksi'] == "Pemasukan") {
            
            if($teller == "Admin") {
                // /**
                //  *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                //  *  This is will add saldo to teller and reduce saldo to penyeimbang 
                // */
                // if(
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 5 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 5
                // ) {
                //     // if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                //         $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                //         $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                //         return "success";
                //     // } else {
                //     //     return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                //     // }
                // }

                // /**
                //  *  This block will execute when user choose rekening kepala 2, 3 or 4 as penyeimbang
                //  *  This is will add saldo to both
                // */
                // else {
                //     // if($data['jenis_transaksi'] == "Pemasukan") {
                //         $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                //         $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                //     // }
                //     return "success";
                // }
                /**
                 *  This block will execute when user choose rekening kepala 1 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                    // if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                        return "success";
                    // } else {
                    //     return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    // }
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                    $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                    $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                    return "success";
                }

                /**
                 *  This block will execute when user choose rekening kepala 4 as penyeimbang
                 *  This is will add saldo to both
                */
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

                /**
                 *  This block will execute when user choose rekening kepala 5 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                    // if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
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
                    // } else {
                    //     return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    // }
                }
            }
            if($teller == "Teller") {
                /**
                 *  This block will execute when user choose rekening kepala 1 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                    // if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                        return "success";
                    // } else {
                    //     return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    // }
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                    $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                    $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                    return "success";
                }

                /**
                 *  This block will execute when user choose rekening kepala 4 as penyeimbang
                 *  This is will add saldo to both
                */
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

                /**
                 *  This block will execute when user choose rekening kepala 5 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                    // if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
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
                    // } else {
                    //     return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    // }
                }
            }
            
            
        }

        if($data['jenis_transaksi'] == "Pengeluaran") {

            if($teller == "Admin") {
                // /**
                //  *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                //  *  This is will add saldo to teller and reduce saldo to penyeimbang 
                // */
                // if(
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 5 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 5
                // ) {
                //     if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                //         $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                //         $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                //         return "success";
                //     } else {
                //         return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                //     }
                // }

                // /**
                //  *  This block will execute when user choose rekening kepala 2, 3 or 4 as penyeimbang
                //  *  This is will add saldo to both
                // */
                // else {
                //     if($data['jenis_transaksi'] == "Pemasukan") {
                //         $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                //         $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) - floatval($data['jumlah'])  ]);
                //     }
                //     return "success";
                // }
                /**
                 *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPenerima->id_bmt)[0] == 1) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                        return "success";
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 2 || explode(".", $rekeningPenerima->id_bmt)[0] == 3) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah']) && floatval($rekeningPenerima->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) - floatval($data['jumlah'])  ]);
                        
                        return "success";
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 4 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 4) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah']) && floatval($rekeningPenerima->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) - floatval($data['jumlah'])  ]);
                        
                        $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                        if($shuBerjalan->saldo == "")
                        {
                            $saldoShuBerjalan = 0;
                        }
                        else
                        {
                            $saldoShuBerjalan = $shuBerjalan->saldo;
                        }
                        
                        // if(floatval($saldoShuBerjalan) >= floatval($data['jumlah'])) {
                            $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) - floatval($data['jumlah']) ]);
                            return "success";
                        // } else {
                        //     return "Saldo Rekening SHU Berjalan Tidak Cukup";
                        // }
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 5 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 5) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
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
                        // if(floatval($saldoShuBerjalan) >= floatval($data['jumlah'])) {
                            $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) - floatval($data['jumlah']) ]);
                            return "success";
                        // } else {
                        //     return "Saldo Rekening SHU Berjalan Tidak Cukup";
                        // }
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }
            }
            if($teller == "Teller") {
                /**
                 *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPenerima->id_bmt)[0] == 1) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                        return "success";
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 2 || explode(".", $rekeningPenerima->id_bmt)[0] == 3) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah']) && floatval($rekeningPenerima->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) - floatval($data['jumlah'])  ]);
                        
                        return "success";
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 4 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 4) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah']) && floatval($rekeningPenerima->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) - floatval($data['jumlah'])  ]);
                        
                        $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
                        if($shuBerjalan->saldo == "")
                        {
                            $saldoShuBerjalan = 0;
                        }
                        else
                        {
                            $saldoShuBerjalan = $shuBerjalan->saldo;
                        }
                        
                        // if(floatval($saldoShuBerjalan) >= floatval($data['jumlah'])) {
                            $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) - floatval($data['jumlah']) ]);
                            return "success";
                        // } else {
                        //     return "Saldo Rekening SHU Berjalan Tidak Cukup";
                        // }
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 5 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 5) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
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
                        // if(floatval($saldoShuBerjalan) >= floatval($data['jumlah'])) {
                            $shuBerjalanUpdate = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->update([ "saldo" => floatval($saldoShuBerjalan) - floatval($data['jumlah']) ]);
                            return "success";
                        // } else {
                        //     return "Saldo Rekening SHU Berjalan Tidak Cukup";
                        // }
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }
            }

        }
    }   

    /** 
     * Get kas harian data
     * @return Response
    */
    public function getKasHarian($id, $date)
    {
        $id_rekening_teller = $id;
        if(Auth::user()->tipe == "teller")
        {
            $id_rekening_teller = json_decode(Auth::user()->detail)->id_rekening;
        }
        $bmt_teller = BMT::where('id_rekening', $id_rekening_teller)->first();
        
        $kas_harian = PenyimpananBMT::where([ 
                        ['id_bmt', $bmt_teller->id ], ['status', '!=', 'Setoran Awal'], 
                        ['created_at', '>', Carbon::parse($date)->startOfDay()], ['created_at', '<', Carbon::parse($date)->endOfDay()]
                        ])->orderBy('created_at', 'asc')
                        ->get();
        $saldo_awal = PenyimpananBMT::where([ 
                        ['id_bmt', $bmt_teller->id ], ['status', '!=', 'Setoran Awal'], 
                        ['created_at', '>', Carbon::parse($date)->startOfDay()], ['created_at', '<', Carbon::parse($date)->endOfDay()]
                    ])
                    ->first();
        
        $saldo_akhir = PenyimpananBMT::where([ 
                        ['id_bmt', $bmt_teller->id ], ['status', '!=', 'Setoran Awal'], 
                        ['created_at', '>', Carbon::parse($date)->startOfDay()], ['created_at', '<', Carbon::parse($date)->endOfDay()]
                    ])->orderBy('created_at', 'desc')
                    ->first();
        
        $data = array();
        $temp_data = array();
        foreach($kas_harian as $kas)
        {
            $teller_confirmer = User::where('id', $kas->teller)->first();
            $kas['teller_confirmer'] = $teller_confirmer;
            array_push($temp_data, $kas);
            $data['saldo_awal'] = json_decode($saldo_awal->transaksi)->saldo_awal;
            $data['saldo_akhir'] = json_decode($saldo_akhir->transaksi)->saldo_akhir;
            $data['data'] = $temp_data;
        }

        return $data;
    }

    /** 
     * Find specific rekening
     * @return Response
    */
    public function findRekening($column_name, $column_val)
    {
        $rekening = Rekening::where($column_name, $column_val)->first();
        return $rekening;
    }

    /** 
     * Save riwayar rekening pengirim jurnal lain
     * @return Response
    */
    public function saveRiwayatRekeningPengirimJurnalLain($dataToPenyimpananBMT, $data, $saldo_pengirim, $id_pengirim, $teller)
    {
        $rekeningPengirim = BMT::where('id_rekening', $data['id_rekening_pengirim'])->select([ 'saldo', 'id_bmt', 'id_rekening', 'nama' ])->first();
        $rekeningPenerima = BMT::where('id_rekening', $data['id_rekening_penerima'])->select([ 'saldo', 'id_bmt', 'id_rekening', 'nama' ])->first();
       
        if($data['jenis_transaksi'] == "Pemasukan") {
            
            if($teller == "Admin") {
                // /**
                //  *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                //  *  This is will add saldo to teller and reduce saldo to penyeimbang 
                // */
                // if(
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 5 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 5
                // ) {
                //     $jumlah = -floatval($data['jumlah']);
                // }

                // /**
                //  *  This block will execute when user choose rekening kepala 2, 3 or 4 as penyeimbang
                //  *  This is will add saldo to both
                // */
                // else {
                //     $jumlah = floatval($data['jumlah']);

                // }

                /**
                 *  This block will execute when user choose rekening kepala 1 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                    $jumlah = -floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                    $jumlah = floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 4 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                    $jumlah = floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 5 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                    $jumlah = -floatval($data['jumlah']);
                }
            }
            if($teller == "Teller") {
                /**
                 *  This block will execute when user choose rekening kepala 1 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPengirim->id_bmt)[0] == 1) {
                    $jumlah = -floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 2 || explode(".", $rekeningPengirim->id_bmt)[0] == 3) {
                    $jumlah = floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 4 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 4) {
                    $jumlah = floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 5 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                    $jumlah = -floatval($data['jumlah']);
                }
            }
        }

        if($data['jenis_transaksi'] == "Pengeluaran") {

            if($teller == "Admin") {
                // /**
                //  *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                //  *  This is will add saldo to teller and reduce saldo to penyeimbang 
                // */
                // if(
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 5 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                //     explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 5
                // ) {
                //     $jumlah = -floatval($data['jumlah']);
                // }

                // /**
                //  *  This block will execute when user choose rekening kepala 2, 3 or 4 as penyeimbang
                //  *  This is will add saldo to both
                // */
                // else {
                //     if($data['jenis_transaksi'] == "Pemasukan") {
                //         $jumlah = -floatval($data['jumlah']);
                //     }
                // }

                /**
                 *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPenerima->id_bmt)[0] == 1) {
                    $jumlah = -floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 2 || explode(".", $rekeningPenerima->id_bmt)[0] == 3) {
                    $jumlah = -floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 4 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 4) {
                    $jumlah = -floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 5 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 5) {
                    $jumlah = -floatval($data['jumlah']);
                }
            }
            if($teller == "Teller") {
                /**
                 *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPenerima->id_bmt)[0] == 1) {
                    $jumlah = -floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 2 || explode(".", $rekeningPenerima->id_bmt)[0] == 3) {
                    $jumlah = -floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 4 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 4) {
                    $jumlah = -floatval($data['jumlah']);
                }

                /**
                 *  This block will execute when user choose rekening kepala 5 as penyeimbang
                 *  This is will add saldo to both
                */
                elseif(explode(".", $rekeningPenerima->id_bmt)[0] == 5) {
                    $jumlah = -floatval($data['jumlah']);
                }
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

?>