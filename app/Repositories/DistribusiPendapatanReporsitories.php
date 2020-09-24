<?php

namespace App\Repositories;
use App\User;
use App\Pengajuan;
use App\Tabungan;
use App\Deposito;
use App\BMT;
use App\Rekening;
use App\PenyimpananDistribusi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\PengajuanReporsitories;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\SimpananReporsitory;
use App\Repositories\DepositoReporsitories;
use App\PenyimpananBMT;
use Carbon\Carbon;

class DistribusiPendapatanReporsitories {

    public function __construct(RekeningReporsitories $rekeningReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                SimpananReporsitory $simpananReporsitory,
                                DepositoReporsitories $depositoReporsitory
                                ) 
    {
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->simpananReporsitory = $simpananReporsitory;
        $this->depositoReporsitory = $depositoReporsitory;
    }

    /** 
     * Get data to distribusi
     * @return Response
    */
    public function getDistribusiData()
    {
        $pendapatan = $this->getRekeningPendapatan();
        $rekening_tabungan = $this->getRekening("TABUNGAN");
        $rekening_deposito = $this->getRekening("DEPOSITO");
        
        $data_rekening = array();
        $total_rata_rata = array();

        foreach($rekening_tabungan as $tab)
        {
            $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
            array_push($total_rata_rata, floatval($tab->saldo) > 0 ? floatval($tab->saldo) / count($tabungan) : 0);
        }

        foreach($rekening_deposito as $dep)
        {
            $deposito = $this->depositoReporsitory->getDeposito("active", $dep->nama_rekening);
            array_push($total_rata_rata, floatval($dep->saldo) > 0 ? floatval($dep->saldo) / count($deposito) : 0);
        }

        $total_rata_rata = $this->getTotalProductAverage($total_rata_rata);
        $total_pendapatan = $this->getRekeningPendapatan("saldo");
        $total_pendapatan_product = 0;

        foreach($rekening_tabungan as $tab)
        {
            $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
            $rata_rata = floatval($tab->saldo) > 0 ? floatval($tab->saldo) / count($tabungan) : 0;
            $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
            $nisbah_bmt = 100 - json_decode($tab->detail)->nisbah_anggota;
            $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);

            $total_pendapatan_product += $pendapatan_product;

            array_push($data_rekening, [ 
                "jenis_rekening"    => $tab->nama_rekening,
                "jumlah"            => count($tabungan),
                "rata_rata"         => $rata_rata,
                "nisbah_anggota"    => $nisbah_anggota,
                "nisbah_bmt"        => $nisbah_bmt,
                "total_rata_rata"   => $total_rata_rata,
                "total_pendapatan"  => $total_pendapatan,
                "pendapatan_product" => $pendapatan_product,
                "porsi_anggota"     => $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product),
                "porsi_bmt"         => $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product),
                "percentage_anggota" => $total_pendapatan > 0 ?$this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product) / $total_pendapatan : 0
            ]);
        }

        foreach($rekening_deposito as $dep)
        {
            $deposito = $this->depositoReporsitory->getDeposito("active", $dep->nama_rekening);
            $rata_rata = floatval($dep->saldo) > 0 ? floatval($dep->saldo) / count($deposito) : 0;
            $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
            $nisbah_bmt = 100 - json_decode($dep->detail)->nisbah_anggota;
            $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);

            $total_pendapatan_product += $pendapatan_product;

            array_push($data_rekening, [
                "jenis_rekening" => $dep->nama_rekening,
                "jumlah"         => count($deposito),
                "rata_rata"      => $rata_rata,
                "nisbah_anggota"    => $nisbah_anggota,
                "nisbah_bmt"        => $nisbah_bmt,
                "total_rata_rata"   => $total_rata_rata,
                "total_pendapatan"  => $total_pendapatan,
                "pendapatan_product" => $pendapatan_product,
                "porsi_anggota"     => $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product),
                "porsi_bmt"         => $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product)
            ]);
        }


        return $data_rekening;
    }

    /**
     * Get data to distribusi revenue
     * @return Response
     */
    public function getDistribusiRevenueData()
    {
        $rekening_tabungan = $this->getRekening("TABUNGAN");
        $rekening_deposito = $this->getRekening("DEPOSITO");

        $data_rekening = array();
        $total_rata_rata = array();

        foreach($rekening_tabungan as $tab)
        {
            $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
            array_push($total_rata_rata, floatval($tab->saldo) > 0 ? floatval($tab->saldo) / count($tabungan) : 0);
        }

        foreach($rekening_deposito as $dep)
        {
            $deposito = $this->depositoReporsitory->getDeposito("active", $dep->nama_rekening);
            array_push($total_rata_rata, floatval($dep->saldo) > 0 ? floatval($dep->saldo) / count($deposito) : 0);
        }

        $total_rata_rata = $this->getTotalProductAverage($total_rata_rata);
        $total_pendapatan = $this->getRekeningPendapatan("saldo") - $this->getRekeningBeban("saldo");
        $total_pendapatan_product = 0;

        foreach($rekening_tabungan as $tab)
        {
            $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
            $rata_rata = floatval($tab->saldo) > 0 ? floatval($tab->saldo) / count($tabungan) : 0;
            $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
            $nisbah_bmt = 100 - json_decode($tab->detail)->nisbah_anggota;
            $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);

            $total_pendapatan_product += $pendapatan_product;

            array_push($data_rekening, [
                "jenis_rekening"    => $tab->nama_rekening,
                "jumlah"            => count($tabungan),
                "rata_rata"         => $rata_rata,
                "nisbah_anggota"    => $nisbah_anggota,
                "nisbah_bmt"        => $nisbah_bmt,
                "total_rata_rata"   => $total_rata_rata,
                "total_pendapatan"  => $total_pendapatan,
                "pendapatan_product" => $pendapatan_product,
                "porsi_anggota"     => $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product),
                "porsi_bmt"         => $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product),
                "percentage_anggota" => $total_pendapatan > 0 ?$this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product) / $total_pendapatan : 0
            ]);
        }

        foreach($rekening_deposito as $dep)
        {
            $deposito = $this->depositoReporsitory->getDeposito("active", $dep->nama_rekening);
            $rata_rata = floatval($dep->saldo) > 0 ? floatval($dep->saldo) / count($deposito) : 0;
            $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
            $nisbah_bmt = 100 - json_decode($dep->detail)->nisbah_anggota;
            $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);

            $total_pendapatan_product += $pendapatan_product;

            array_push($data_rekening, [
                "jenis_rekening" => $dep->nama_rekening,
                "jumlah"         => count($deposito),
                "rata_rata"      => $rata_rata,
                "nisbah_anggota"    => $nisbah_anggota,
                "nisbah_bmt"        => $nisbah_bmt,
                "total_rata_rata"   => $total_rata_rata,
                "total_pendapatan"  => $total_pendapatan,
                "pendapatan_product" => $pendapatan_product,
                "porsi_anggota"     => $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product),
                "porsi_bmt"         => $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product)
            ]);
        }


        return $data_rekening;
    }

    /** 
     * Get rekening to distribusi
     * @return Response
    */
    public function getRekeningPendapatan($key="")
    {
        $pendapatan = BMT::where('id_bmt', 'like', '4%')->get();

        if($key == "saldo")
        {
            $pendapatan = BMT::where('id_bmt', 'like', '4%')->sum("saldo");
        }
        return $pendapatan;
    }

    /**
     * Get total beban
     * @return Response
     */
    public function getRekeningBeban($key="")
    {
        $pendapatan = BMT::where('id_bmt', 'like', '5%')->get();

        if($key == "saldo")
        {
            $pendapatan = BMT::where('id_bmt', 'like', '5%')->sum("saldo");
        }
        return $pendapatan;
    }


    /**
     * Get rekening data
     * @return Response
     */
    public function getRekening($type="")
    {
        $rekening = Rekening::select(["rekening.*","bmt.saldo","bmt.id_rekening"])
                ->where([ ['tipe_rekening', 'detail'], ['katagori_rekening', $type] ])
                ->join('bmt', 'rekening.id', 'bmt.id_rekening')
                ->get();
        return $rekening;
    }

    /** 
     * Get saldo rata-rata per produk
     * @return Response
    */
    public function getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan)
    {
        if($total_rata_rata == 0 || $total_pendapatan == 0 )
        {
           $result = 0;
        }
        else
        {
            $result = $rata_rata / $total_rata_rata * $total_pendapatan;
        }

        return $result;
    }

    /** 
     * Get rekening shu berjalan
     * @return Response
    */
    public function getRekeningSHU($key="")
    {
        $shu = BMT::where('nama', 'SHU BERJALAN')->get();

        if($key == "saldo")
        {
            $shu = BMT::where('nama', 'SHU BERJALAN')->sum("saldo");
        }
        return $shu;
    }

    /** 
     * Get total rata-rata produk
     * @return Response
    */
    public function getTotalProductAverage($data)
    {
        $result = 0;
        foreach($data as $total)
        {
            $result = $total + $result;
        }
        return $result;
    }

    /** 
     * Get porsi pendapatan per product
     * @return Response
    */
    public function getPorsiPendapatanProduct($nisbah, $pendapatan)
    {
        $result = ($nisbah/100) * $pendapatan;
        return $result;
    }

    /** 
     * Check distribusi pendapatan
     * @return Response
    */
    public function checkDistribusiPendapatanStatus()
    {
        $distribusi = PenyimpananBMT::where([
            ['status', 'Distribusi Pendapatan'],
            ['created_at', '>', Carbon::now()->subDay(30)]
        ])->count();
        
        return $distribusi;
    }

    /** 
     * Get date diff between 2 date
     * @return Response
    */
    public function getDateDiff()
    {
        $distribusi = PenyimpananBMT::where('status', 'Distribusi Pendapatan')->first();
        $date = Carbon::parse($distribusi->created_at);
        $now = Carbon::now();

        return $date->diffInDays($now);
    }

    /** 
     * Insert data to penyimpanan pendistribusian
     * @return Response
    */
    public function insertPenyimpananPendistribusian($data)
    {
        $pendistribusian = new PenyimpananDistribusi();
        $pendistribusian->id_user = $data['id_user'];
        $pendistribusian->status = $data['status'];
        $pendistribusian->transaksi = json_encode($data['transaksi']);

        if($pendistribusian->save())
        {
            return "success";
        }
        else
        {
            return "something wrong";
        }
    }

    /** 
     * Action distribusi pendapatan
     * @return Response
    */
    public function doPendistribusian($data)
    {
        DB::beginTransaction();
        try
        {
            $data_distribusi = [
                "id_user"   => Auth::user()->id,
                "status"    => "Pendistribusian Pendapatan",
                "transaksi" => $this->getDistribusiData()
            ];
            $this->insertPenyimpananPendistribusian($data_distribusi);
            
            if($data->jenis == "net_profit")
            {
                $pendapatan = $this->getRekeningPendapatan();
            }
            else
            {
                $pendapatan = $this->getRekeningSHU();
            }

            /** Get Rekening Bagi Hasil */
            $rekening_tabungan = $this->getRekening("TABUNGAN");
            $rekening_deposito = $this->getRekening("DEPOSITO");
            
            $data_rekening = array();
            $total_rata_rata = array();

            foreach($rekening_tabungan as $tab)
            {
                $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
                array_push($total_rata_rata, floatval($tab->saldo) > 0 ? floatval($tab->saldo) / count($tabungan) : 0);
            }

            foreach($rekening_deposito as $dep)
            {
                $deposito = $this->depositoReporsitory->getDeposito("active", $dep->nama_rekening);
                array_push($total_rata_rata, floatval($dep->saldo) > 0 ? floatval($dep->saldo) / count($deposito) : 0);
            }

            $total_rata_rata = $this->getTotalProductAverage($total_rata_rata);
            if($data->jenis == "net_profit")
            {
                $total_pendapatan = $this->getRekeningPendapatan("saldo");
            }
            else
            {
                $total_pendapatan = $this->getRekeningSHU("saldo");
            }

            $total_pendapatan_product = 0;
            $total_porsi_bmt = 0;

            $pendapatan_product = array();
            $rata_rata_saldo_anggota = array();
            
            $response = array();

            $temp = array();
            foreach($rekening_tabungan as $tab)
            {
                $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
                $rata_rata = floatval($tab->saldo) > 0 ? floatval($tab->saldo) / count($tabungan) : 0;
                $rata_rata_product = floatval($tab->saldo) > 0 ? floatval($tab->saldo) / $this->getDateDiff() : 0;
                $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
                $nisbah_bmt = 100 - json_decode($tab->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $total_porsi_bmt += $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);

                foreach($tabungan as $user_tabungan)
                {
                    $bagi_hasil = (json_decode($user_tabungan->detail)->saldo / $this->getDateDiff()) / $rata_rata_product * $porsi_anggota;
                    $detailToPenyimpananTabungan = [
                        "teller"        => Auth::user()->id,
                        "dari_rekening" => "",
                        "untuk_rekening" => $user_tabungan->jenis_tabungan,
                        "jumlah"    => $bagi_hasil,
                        "saldo_awal" => json_decode($user_tabungan->detail)->saldo,
                        "saldo_akhir" => json_decode($user_tabungan->detail)->saldo + $bagi_hasil
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"   => $user_tabungan->id_user,
                        "id_tabungan" => $user_tabungan->id,
                        "status"    => "Distribusi Pendapatan",
                        "transaksi" => $detailToPenyimpananTabungan,
                        "teller"    => Auth::user()->id
                    ];

                    $insertPenyimpananTabungan = $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);

                    if($insertPenyimpananTabungan == "success")
                    {
                        $bmt_tabungan = BMT::where('id_rekening', $user_tabungan->id_rekening)->first();
                        $tabungan_pendistribusian = $this->tabunganReporsitory->findTabungan($user_tabungan->id);
                        
                        $dataToUpdateTabungan = [
                            "saldo" => json_decode($user_tabungan->detail)->saldo + $bagi_hasil,
                            "id_pengajuan" => null
                        ];
                        $detailToPenyimpananBMT = [
                            "jumlah"        => $bagi_hasil,
                            "saldo_awal"    => $bmt_tabungan->saldo,
                            "saldo_akhir"   => $bmt_tabungan->saldo + $bagi_hasil,
                            "id_pengajuan"  => null
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"   => $user_tabungan->id_user,
                            "id_bmt"    => $bmt_tabungan->id,
                            "status"    => "Distribusi Pendapatan",
                            "transaksi" => $detailToPenyimpananBMT,
                            "teller"    => Auth::user()->id
                        ];

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $tabungan_pendistribusian->detail = json_encode($dataToUpdateTabungan);
                        $bmt_tabungan->saldo = $bmt_tabungan->saldo + $bagi_hasil;

                        $tabungan_pendistribusian->save(); $bmt_tabungan->save();
                    }
                }
            }

            foreach($rekening_deposito as $dep)
            {
                $deposito = $this->depositoReporsitory->getDeposito("active", $dep->nama_rekening);
                $rata_rata = floatval($dep->saldo) > 0 ? floatval($dep->saldo) / count($deposito) : 0;
                $rata_rata_product = floatval($dep->saldo) > 0 ? floatval($dep->saldo) / $this->getDateDiff() : 0;
                $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
                $nisbah_bmt = 100 - json_decode($dep->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $total_porsi_bmt += $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);

                foreach($deposito as $user_deposito)
                {
                    $bagi_hasil = (json_decode($user_deposito->detail)->saldo / $this->getDateDiff()) / ( $rata_rata_product ) * $porsi_anggota;
                    $id_pencairan = json_decode($user_deposito->detail)->id_pencairan;
                    $tabungan_pencairan = $this->tabunganReporsitory->findTabungan($id_pencairan);

                    $detailToPenyimpananTabungan = [
                        "teller"        => Auth::user()->id,
                        "dari_rekening" => "",
                        "untuk_rekening" => $tabungan_pencairan->jenis_tabungan,
                        "jumlah"    => $bagi_hasil,
                        "saldo_awal" => json_decode($tabungan_pencairan->detail)->saldo,
                        "saldo_akhir" => json_decode($tabungan_pencairan->detail)->saldo + $bagi_hasil
                    ];
                    $dataToPenyimpananTabungan = [
                        "id_user"   => $tabungan_pencairan->id_user,
                        "id_tabungan" => $tabungan_pencairan->id,
                        "status"    => "Distribusi Pendapatan",
                        "transaksi" => $detailToPenyimpananTabungan,
                        "teller"    => Auth::user()->id
                    ];

                    $insertPenyimpananTabungan = $this->tabunganReporsitory->insertPenyimpananTabungan($dataToPenyimpananTabungan);
                    
                    if($insertPenyimpananTabungan == "success")
                    {
                        $bmt_tabungan = BMT::where('id_rekening', $tabungan_pencairan->id_rekening)->first();

                        $dataToUpdateTabunganPencairan = [
                            "saldo" => json_decode($tabungan_pencairan->detail)->saldo + $bagi_hasil,
                            "id_pengajuan" => null
                        ];

                        $detailToPenyimpananBMT = [
                            "jumlah"        => $bagi_hasil,
                            "saldo_awal"    => $bmt_tabungan->saldo,
                            "saldo_akhir"   => $bmt_tabungan->saldo + $bagi_hasil,
                            "id_pengajuan"  => null
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"   => $user_tabungan->id_user,
                            "id_bmt"    => $bmt_tabungan->id,
                            "status"    => "Distribusi Pendapatan",
                            "transaksi" => $detailToPenyimpananBMT,
                            "teller"    => Auth::user()->id
                        ];

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $tabungan_pencairan->detail = json_encode($dataToUpdateTabunganPencairan);
                        $bmt_tabungan->saldo = $bmt_tabungan->saldo + $bagi_hasil;

                        $tabungan_pencairan->save(); $bmt_tabungan->save();
                    }

                }
            }

            foreach($pendapatan as $rekening_pendapatan)
            {
                if($rekening_pendapatan->saldo > 0)
                {
                    $detailToPenyimpananBMT = [
                        "jumlah"        => $rekening_pendapatan->saldo,
                        "saldo_awal"    => $rekening_pendapatan->saldo,
                        "saldo_akhir"   => 0,
                        "id_pengajuan"  => null
                    ];
                    $dataToPenyimpananBMT = [
                        "id_user"   => Auth::user()->id,
                        "id_bmt"    => $rekening_pendapatan->id,
                        "status"    => "Distribusi Pendapatan",
                        "transaksi" => $detailToPenyimpananBMT,
                        "teller"    => Auth::user()->id
                    ];

                    $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                    $rekening_pendapatan->saldo = $rekening_pendapatan->saldo - $rekening_pendapatan->saldo; 
                    $rekening_pendapatan->save();
                }
            }

            if($data->jenis == "revenue")
            {
                $bmt_rekening_biaya_dan_pendapatan = BMT::where('id_bmt', 'like', '5%')->orWhere('id_bmt', 'like', '4%')->get();

                foreach($bmt_rekening_biaya_dan_pendapatan as $rekening_biaya)
                {
                    if($rekening_biaya->saldo > 0)
                    {
                        $detailToPenyimpananBMT = [
                            "jumlah"        => $rekening_biaya->saldo,
                            "saldo_awal"    => $rekening_biaya->saldo,
                            "saldo_akhir"   => $rekening_biaya->saldo - $rekening_biaya->saldo,
                            "id_pengajuan"  => null
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"   => Auth::user()->id,
                            "id_bmt"    => $rekening_biaya->id,
                            "status"    => "Distribusi Pendapatan",
                            "transaksi" => $detailToPenyimpananBMT,
                            "teller"    => Auth::user()->id
                        ];

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                        $rekening_biaya->saldo = 0; $rekening_biaya->save();
                    }
                }
            }

            if($data->jenis == "net_profit")
            {
                $bmt_shu_berjalan = BMT::where('nama', 'SHU BERJALAN')->first();
                $detailToPenyimpananBMT = [
                    "jumlah"        => $total_pendapatan,
                    "saldo_awal"    => $bmt_shu_berjalan->saldo,
                    "saldo_akhir"   => $bmt_shu_berjalan->saldo - $total_pendapatan,
                    "id_pengajuan"  => null
                ];
                $dataToPenyimpananBMT = [
                    "id_user"   => Auth::user()->id,
                    "id_bmt"    => $bmt_shu_berjalan->id,
                    "status"    => "Distribusi Pendapatan",
                    "transaksi" => $detailToPenyimpananBMT,
                    "teller"    => Auth::user()->id
                ];
                $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
                $bmt_shu_berjalan->saldo = $bmt_shu_berjalan->saldo - $total_pendapatan;
                $bmt_shu_berjalan->save();
            }

            $shu_yang_harus_dibagikan = BMT::where('nama', 'SHU YANG HARUS DIBAGIKAN')->first();
            $detailToPenyimpananBMT = [
                "jumlah"        => $total_porsi_bmt,
                "saldo_awal"    => $shu_yang_harus_dibagikan->saldo,
                "saldo_akhir"   => $shu_yang_harus_dibagikan->saldo + $total_porsi_bmt,
                "id_pengajuan"  => null
            ];
            $dataToPenyimpananBMT = [
                "id_user"   => Auth::user()->id,
                "id_bmt"    => $shu_yang_harus_dibagikan->id,
                "status"    => "Distribusi Pendapatan",
                "transaksi" => $detailToPenyimpananBMT,
                "teller"    => Auth::user()->id
            ];

            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);
            $shu_yang_harus_dibagikan->saldo = $shu_yang_harus_dibagikan->saldo + $total_porsi_bmt;
            $shu_yang_harus_dibagikan->save();

            DB::commit();
            $response = array("type" => "success", "message" => "Pendistribusian Pendapatan Berhasil Dilakukan.");
        }
        catch(Exception $ex)
        {
            DB::rollback();
            $response = array("type" => "error", "message" => "Pendistribusian Pendapatan Gagal Dilakukan.");
        }

        return $response;
    }

    /** 
     * Get data riwayat distribusi pendapatan
     * @return Response
    */
    public function getDistribusiHistory($date)
    {
        $distribusi = PenyimpananDistribusi::whereMonth('created_at', Carbon::now()->format('n'))
                                            ->whereYear('created_at', Carbon::now()->format('yy'))
                                            ->get();
        
        if($date !== "")
        {
            $distribusi = PenyimpananDistribusi::whereMonth('created_at', Carbon::parse($date)->format('n'))
                                            ->whereYear('created_at', Carbon::parse($date)->format('yy'))
                                            ->get();
        }
        return $distribusi;
    }
}

?>