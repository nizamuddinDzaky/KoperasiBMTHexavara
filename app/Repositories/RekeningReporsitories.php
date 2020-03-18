<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Pengajuan;
use App\Rekening;
use App\BMT;
use App\PenyimpananBMT;

class RekeningReporsitories {

    /** 
     * Get all rekening
     * @return Response
    */
    public function getRekening($type="", $sort="")
    {
        $rekening = "SELECT rekening.*, bmt.saldo FROM rekening INNER JOIN bmt ON rekening.id=bmt.id_rekening";

        if($type != "") {
            $rekening .= " AND tipe_rekening='" . $type . "'";
        }

        if($type != "") {
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

        if($type != "") {
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
            /** 
             * Inset data to penyimpanan bmt table 
             * Using for history data
             * @return Null
            */
            if($data->tipe == 1) {
                $jenis = "Pemasukan";
                $dari = $data->dari;

                if($data->tujuan != null) {
                    $ke = $data->tujuan;
                    $eksekutor = "Admin"; // Who is do it
                } else {
                    $ke = json_decode(Auth::user()->detail)->id_rekening;
                    $eksekutor = "Teller"; // Who is do it
                }

                $bmt_penerima = BMT::where('id_rekening', $ke)->select('id')->first();
                $id_penerima = $bmt_penerima->id;
            } else {
                $jenis = "Pengeluaran";

                if($data->tujuan != null) {
                    $dari = $data->tujuan;
                    $eksekutor = "Admin"; // Who is do it
                } else {
                    $dari = json_decode(Auth::user()->detail)->id_rekening;
                    $eksekutor = "Teller"; // Who is do it
                }
                
                $ke = $data->dari;
                $bmt_penerima = BMT::where('id_rekening', $ke)->select('id')->first();
                $id_penerima = $bmt_penerima->id;
            }

            $id_user = Auth::user()->id;
            $id_bmt = $id_penerima;
            $status = $type;
            $saldo = BMT::where('id', $id_bmt)->select('saldo')->first();
            
            if($saldo['saldo'] == "") {
                $saldo['saldo'] = 0;
            }

            $detail = [
                "jumlah"    => preg_replace('/[^\d.]/', '', $data->jumlah),
                "saldo_awal"=> floatval($saldo['saldo']),
                "saldo_akhir" => floatval($saldo['saldo']) + preg_replace('/[^\d.]/', '', $data->jumlah),
                "dari"      => $dari,
                "ke"    => $ke,
                "keterangan"=> "[" . $jenis . "] " . $data->keterangan
            ];
            $teller = Auth::user()->id;

            $dataToPenyimpananBMT = [
                "id_user"   => $id_user,
                "id_bmt"    => $id_bmt,
                "status"    => $status,
                "transaksi" => $detail,
                "teller"    => $teller,
                "saldo_awal"=> $saldo['saldo'],
                "jumlah"    => preg_replace('/[^\d.]/', '', $data->jumlah),
            ];
            $dataToBMT = [
                "jenis_transaksi"       => $jenis,
                "id_rekening_pengirim"  => $dari,
                "id_rekening_penerima"  => $ke,
                "jumlah"                => preg_replace('/[^\d.]/', '', $data->jumlah)
            ];
            
            if( 
                $this->insertPenyimpananBMT($dataToPenyimpananBMT) == "success" &&
                $this->updateSaldoRekening($dataToBMT, $jenis, $eksekutor) == "success"
              )
            {
                DB::commit();
                $result = array('type' => 'success', 'message' => 'Transfer Pengeluaran/Pemasukan Berhasil Dilakukan');
            }
            else
            { 
                $result = array('type' => 'error', 'message' => 'Transfer Pengeluaran/Pemasukan Gagal. ' . $this->updateSaldoRekening($dataToBMT, $jenis));
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
                /**
                 *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(
                    explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                    explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 5 ||
                    explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                    explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 5
                ) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                        return "success";
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 or 4 as penyeimbang
                 *  This is will add saldo to both
                */
                else {
                    if($data['jenis_transaksi'] == "Pemasukan") {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                    }
                    return "success";
                }
            }
            if($teller == "Teller") {
                /**
                 *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPengirim->id_bmt)[0] == 1 || explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                        return "success";
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 or 4 as penyeimbang
                 *  This is will add saldo to both
                */
                else {
                    if($data['jenis_transaksi'] == "Pemasukan") {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) + floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);
                    }
                    return "success";
                }
            }
            
            
        }

        if($data['jenis_transaksi'] == "Pengeluaran") {

            if($teller == "Admin") {
                /**
                 *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(
                    explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                    explode(".", $rekeningPengirim->id_bmt)[0] == 1 && explode(".", $rekeningTujuan->id_bmt)[0] == 5 ||
                    explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 1 ||
                    explode(".", $rekeningPengirim->id_bmt)[0] == 5 && explode(".", $rekeningTujuan->id_bmt)[0] == 5
                ) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                        return "success";
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 or 4 as penyeimbang
                 *  This is will add saldo to both
                */
                else {
                    if($data['jenis_transaksi'] == "Pemasukan") {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) - floatval($data['jumlah'])  ]);
                    }
                    return "success";
                }
            }
            if($teller == "Teller") {
                /**
                 *  This block will execute when user choose rekening kepala 1 or 5 as penyeimbang
                 *  This is will add saldo to teller and reduce saldo to penyeimbang 
                */
                if(explode(".", $rekeningPengirim->id_bmt)[0] == 1 || explode(".", $rekeningPengirim->id_bmt)[0] == 5) {
                    if(floatval($rekeningPengirim->saldo) >= floatval($data['jumlah'])) {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) + floatval($data['jumlah'])  ]);

                        return "success";
                    } else {
                        return "Saldo Rekening " . $rekeningPengirim->nama . " Tidak Cukup";
                    }
                }

                /**
                 *  This block will execute when user choose rekening kepala 2, 3 or 4 as penyeimbang
                 *  This is will add saldo to both
                */
                else {
                    if($data['jenis_transaksi'] == "Pemasukan") {
                        $pengirimUpdate = BMT::where('id_rekening', $data['id_rekening_pengirim'])->update([ "saldo" => floatval($rekeningPengirim->saldo) - floatval($data['jumlah'])  ]);
                        $penerimaUpdate = BMT::where('id_rekening', $data['id_rekening_penerima'])->update([ "saldo" => floatval($rekeningPenerima->saldo) - floatval($data['jumlah'])  ]);
                    }
                    return "success";
                }
            }

        }
    }   
}

?>