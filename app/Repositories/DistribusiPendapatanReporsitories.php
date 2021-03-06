<?php

namespace App\Repositories;
use App\Http\Controllers\HomeController;
use App\PenyimpananDeposito;
use App\PenyimpananTabungan;
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
use App\Repositories\InformationRepository;
use App\PenyimpananBMT;
use App\PenyimpananRekening;
use Carbon\Carbon;
use phpDocumentor\Reflection\Project;
use PhpParser\Node\Stmt\DeclareDeclare;
use Illuminate\Support\Facades\Session;

class DistribusiPendapatanReporsitories {

    public function __construct(RekeningReporsitories $rekeningReporsitory,
                                TabunganReporsitories $tabunganReporsitory,
                                SimpananReporsitory $simpananReporsitory,
                                DepositoReporsitories $depositoReporsitory,
                                InformationRepository $informationRepository
                                ) 
    {
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->simpananReporsitory = $simpananReporsitory;
        $this->depositoReporsitory = $depositoReporsitory;
        $this->informationRepository = $informationRepository;
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
            $rata_rata_product_tabungan = 0.0;
            $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
            foreach($tabungan as $user_tabungan) {
                $temporarySaldo = $this->getSaldoAverageTabunganAnggota($user_tabungan->id_user, $user_tabungan->id);
                $rata_rata_product_tabungan = $rata_rata_product_tabungan + $temporarySaldo;
            }
            Session::put($user_tabungan->jenis_tabungan, $rata_rata_product_tabungan);
            array_push($total_rata_rata, $rata_rata_product_tabungan);
        }

        foreach($rekening_deposito as $dep)
        {
            $rata_rata_product_deposito = 0.0;
            $deposito = $this->depositoReporsitory->getDepositoDistribusi($dep->nama_rekening);
            foreach($deposito as $user_deposito) {
                if ($user_deposito->type == 'jatuhtempo'){
                    $tanggal =  $user_deposito->tempo;
                }
                else if($user_deposito->type == 'pencairanawal')
                {
                    $tanggal = $user_deposito->tanggal_pencairan;
                }
                else if($user_deposito->type == 'active')
                {
                    $tanggal = Carbon::parse('first day of January 1970');
                }
                $temporarySaldo = $this->getSaldoAverageDepositoAnggota($user_deposito->id_user, $user_deposito->id, $tanggal);
                $rata_rata_product_deposito = $rata_rata_product_deposito + $temporarySaldo ;
            }
            Session::put($dep->nama_rekening, $rata_rata_product_deposito);
            array_push($total_rata_rata, $rata_rata_product_deposito);
        }

        $total_rata_rata = $this->getTotalProductAverage($total_rata_rata);
        Session::put("total_rata_rata", $total_rata_rata);
        $total_pendapatan = $this->getRekeningPendapatan("saldo");
        $total_pendapatan_product = 0;

        $total_porsi_anggota = 0;
        $total_porsi_bmt = 0;


        foreach($rekening_tabungan as $tab)
        {
            $rata_rata = Session::get($tab->nama_rekening);
            $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
            $nisbah_bmt = 100 - json_decode($tab->detail)->nisbah_anggota;
            $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);

            $total_porsi_anggota += $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
            $total_porsi_bmt += $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);

        }

        foreach($rekening_deposito as $dep)
        {
            $rata_rata = Session::get($dep->nama_rekening);
            $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
            $nisbah_bmt = 100 - json_decode($dep->detail)->nisbah_anggota;
            $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);

            $total_porsi_anggota += $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
            $total_porsi_bmt += $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);
        }

        $shuBerjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
        $selisihBMTAnggota = $shuBerjalan->saldo - $total_porsi_anggota;


        foreach($rekening_tabungan as $tab)
        {
            $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
            $rata_rata = Session::get($tab->nama_rekening);
            $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
            $nisbah_bmt = 100 - json_decode($tab->detail)->nisbah_anggota;
            $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);

            $total_pendapatan_product += $pendapatan_product;

            if ($total_porsi_bmt == 0)
            {
                $porsi_bmt = 0;
            }
            else
            {
//                $porsi_bmt = $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product) / $total_porsi_bmt * $selisihBMTAnggota;
                $porsi_bmt = $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);

            }

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
                "porsi_bmt"         => $porsi_bmt,
                "percentage_anggota" => $total_pendapatan > 0 ?$this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product) / $total_pendapatan : 0
            ]);
        }

        foreach($rekening_deposito as $dep)
        {
            $deposito = $this->depositoReporsitory->getDepositoDistribusi($dep->nama_rekening);
            $rata_rata = Session::get($dep->nama_rekening);
            $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
            $nisbah_bmt = 100 - json_decode($dep->detail)->nisbah_anggota;
            $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);

            $total_pendapatan_product += $pendapatan_product;
            if ($total_porsi_bmt == 0)
            {
                $porsi_bmt = 0;
            }
            else
            {
//                $porsi_bmt = $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product) / $total_porsi_bmt * $selisihBMTAnggota;
                $porsi_bmt = $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);

            }
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
                "porsi_bmt"         => $porsi_bmt
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
            $rata_rata_product_tabungan = 0.0;
            $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
            foreach($tabungan as $user_tabungan) {
                $temporarySaldo = $this->getSaldoAverageTabunganAnggota($user_tabungan->id_user, $user_tabungan->id);
                $rata_rata_product_tabungan = $rata_rata_product_tabungan + $temporarySaldo;
            }
            Session::put($user_tabungan->jenis_tabungan, $rata_rata_product_tabungan);
            array_push($total_rata_rata, $rata_rata_product_tabungan);
        }

        foreach($rekening_deposito as $dep)
        {
            $rata_rata_product_deposito = 0.0;
            $deposito = $this->depositoReporsitory->getDepositoDistribusi($dep->nama_rekening);
            foreach($deposito as $user_deposito) {
                if ($user_deposito->type == 'jatuhtempo'){
                    $tanggal =  $user_deposito->tempo;
                }
                else if($user_deposito->type == 'pencairanawal')
                {
                    $tanggal = $user_deposito->tanggal_pencairan;
                }
                else if($user_deposito->type == 'active')
                {
                    $tanggal = Carbon::parse('first day of January 1970');
                }
                $temporarySaldo = $this->getSaldoAverageDepositoAnggota($user_deposito->id_user, $user_deposito->id, $tanggal);
                $rata_rata_product_deposito = $rata_rata_product_deposito + $temporarySaldo ;
            }
            Session::put($dep->nama_rekening, $rata_rata_product_deposito);
            array_push($total_rata_rata, $rata_rata_product_deposito);
        }

        $total_rata_rata = $this->getTotalProductAverage($total_rata_rata);
        Session::put("total_rata_rata", $total_rata_rata);
        $total_pendapatan = $this->getRekeningPendapatan("saldo") - $this->getRekeningBeban("saldo");
        $total_pendapatan_product = 0;

        foreach($rekening_tabungan as $tab)
        {
            $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
            $rata_rata = Session::get($tab->nama_rekening);
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
            $deposito = $this->depositoReporsitory->getDepositoDistribusi($dep->nama_rekening);
            $rata_rata = Session::get($dep->nama_rekening);
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
        $rekening = Rekening::select(["rekening.*","bmt.saldo","bmt.id_rekening","bmt.id as id_bmt"])
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
        $distribusi = PenyimpananBMT::where('status', 'Distribusi Pendapatan')
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->first();

        ($distribusi == null ) ? $distribusi = 0 : $distribusi = 1;

        return $distribusi;
    }

    /** 
     * Get date diff between 2 date
     * @return Response
    */
    public function getDateDiff()
    {
        $distribusi = PenyimpananBMT::where('status', 'Distribusi Pendapatan')
            ->where('created_at', '<', Carbon::now()->toDateString())
            ->orderBy('created_at', 'desc')->first();

        if($distribusi == null)
        {
            $distribusi = PenyimpananBMT::first();
            $date = Carbon::parse($distribusi->created_at)->startOfDay();
        }
        else
        {
            $date = Carbon::parse($distribusi->created_at)->startOfDay()->addDays(1);
        }

        $now = Carbon::now()->startOfDay();

        return $now->diffInDays($date);
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
            $this->insertPenyimpananRekeningLabaRugi();

            if($data->jenis == "net_profit") {
                $data_distribusi = [
                    "id_user"   => Auth::user()->id,
                    "status"    => "Pendistribusian Pendapatan",
                    "transaksi" => $this->getDistribusiRevenueData()
                ];
            }
            else
            {
                $data_distribusi = [
                    "id_user"   => Auth::user()->id,
                    "status"    => "Pendistribusian Pendapatan",
                    "transaksi" => $this->getDistribusiData()
                ];
            }


            $this->insertPenyimpananPendistribusian($data_distribusi);
            
            if($data->jenis == "net_profit")
            {
                $pendapatan = $this->getRekeningSHU();
            }
            else
            {
                $pendapatan = $this->getRekeningPendapatan();
            }

            /** Get Rekening Bagi Hasil */
            $rekening_tabungan = $this->getRekening("TABUNGAN");
            $rekening_deposito = $this->getRekening("DEPOSITO");
            
            $data_rekening = array();
            $total_rata_rata = array();



            $total_rata_rata = Session::get("total_rata_rata"); //valid


            if($data->jenis == "net_profit")
            {
                $total_pendapatan = $this->getRekeningSHU("saldo");
            }
            else
            {
                $total_pendapatan = $this->getRekeningPendapatan("saldo");
            }

            $total_pendapatan_product = 0;
            $total_porsi_bmt = 0;
            $total_porsi_anggota = 0;


            $pendapatan_product = array();
            $rata_rata_saldo_anggota = 0.0;
            
            $response = array();
            $temp = array();

            foreach($rekening_tabungan as $tab)
            {
                $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
                $rata_rata = Session::get($tab->nama_rekening);
                $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
                $nisbah_bmt = 100 - json_decode($tab->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan); // untuk cari pendapatan per produk
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $insertPenyimpananRekening = $this->insertPenyimpananRekeningPorsiRugi($porsi_anggota, json_decode($tab->detail)->rek_margin);
                $total_porsi_anggota += $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $total_porsi_bmt += $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);


                foreach($tabungan as $user_tabungan)
                {
                    if($rata_rata == 0 || $porsi_anggota == 0){
                        $bagi_hasil = 0;
                    }
                    else
                    {
                        $bagi_hasil = $this->getSaldoAverageTabunganAnggota($user_tabungan->id_user, $user_tabungan->id) / $rata_rata * $porsi_anggota ;
                    }

                    $active_flag = User::where('id', $user_tabungan->id_user)->select('is_active')->first();

                    if(($user_tabungan->status != 'active' && $active_flag->is_active == 0) || ($user_tabungan->status != 'active' && $active_flag->is_active == 1) ){
                        $zakat = BMT::where('id',334)->first();
                        $detailToPenyimpananBMT = [
                            "jumlah"        => $bagi_hasil,
                            "saldo_awal"    => $zakat->saldo,
                            "saldo_akhir"   => $zakat->saldo + $bagi_hasil,
                            "id_pengajuan"  => null
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"   => $user_tabungan->id_user,
                            "id_bmt"    => $zakat->id,
                            "status"    => "Distribusi Pendapatan",
                            "transaksi" => $detailToPenyimpananBMT,
                            "teller"    => Auth::user()->id
                        ];

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $zakat->saldo = $zakat->saldo + $bagi_hasil;
                        $zakat->save();

                    }
                    else
                    {
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

            }

            $tanggal = "";


            foreach($rekening_deposito as $dep)
            {
                $deposito = $this->depositoReporsitory->getDepositoDistribusi($dep->nama_rekening);
                $rata_rata = Session::get($dep->nama_rekening);
                $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
                $nisbah_bmt = 100 - json_decode($dep->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $insertPenyimpananRekening = $this->insertPenyimpananRekeningPorsiRugi($porsi_anggota, json_decode($dep->detail)->rek_margin);
                $total_porsi_anggota += $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $total_porsi_bmt += $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);


                foreach($deposito as $user_deposito)
                {
                    if($rata_rata == 0 || $porsi_anggota == 0){
                        $bagi_hasil = 0;
                    }
                    else
                    {
                        if ($user_deposito->type == 'jatuhtempo'){
                            $tanggal =  $user_deposito->tempo;
                        }
                        else if($user_deposito->type == 'pencairanawal')
                        {
                            $tanggal = $user_deposito->tanggal_pencairan;
                        }
                        else if($user_deposito->type == 'active')
                        {
                            $tanggal = Carbon::parse('first day of January 1970');
                        }

                        $bagi_hasil = $this->getSaldoAverageDepositoAnggota($user_deposito->id_user, $user_deposito->id, $tanggal ) / $rata_rata * $porsi_anggota ;


                    }
                    $type = $user_deposito->type;
                    $active_flag = User::where('id', $user_deposito->id_user)->select('is_active')->first();
                    $id_pencairan = json_decode($user_deposito->detail)->id_pencairan;
                    $tabungan_pencairan = $this->tabunganReporsitory->findTabungan($id_pencairan);

                    if(($active_flag->is_active == 1 && $type == "pencairanawal") || ($active_flag->is_active == 0 && $type == "pencairanawal") || ($active_flag->is_active == 0 && $type == "jatuhtempo")  )
                    {
//                        dd($bagi_hasil);
                        $zakat = BMT::where('id',334)->first();
                        $detailToPenyimpananBMT = [
                            "jumlah"        => $bagi_hasil,
                            "saldo_awal"    => $zakat->saldo,
                            "saldo_akhir"   => $zakat->saldo + $bagi_hasil,
                            "id_pengajuan"  => null
                        ];
                        $dataToPenyimpananBMT = [
                            "id_user"   => $user_deposito->id_user,
                            "id_bmt"    => $zakat->id,
                            "status"    => "Distribusi Pendapatan",
                            "transaksi" => $detailToPenyimpananBMT,
                            "teller"    => Auth::user()->id
                        ];

                        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

                        $zakat->saldo = $zakat->saldo + $bagi_hasil;
                        $zakat->save();
                    }
                    else
                    {
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
                                "id_user"   => $user_deposito->id_user,
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

            $shu_yang_harus_dibagikan = BMT::where('nama', 'SHU YANG HARUS DIBAGIKAN')->first();
            $shu_berjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
            $selisihBMTAnggota = $shu_berjalan->saldo - $total_porsi_anggota;

            if ($data->jenis == "revenue"){
                $detailToPenyimpananBMT = [
                    "jumlah"        => $selisihBMTAnggota,
                    "saldo_awal"    => $shu_yang_harus_dibagikan->saldo,
                    "saldo_akhir"   => $shu_yang_harus_dibagikan->saldo + $selisihBMTAnggota,
                    "id_pengajuan"  => null
                ];
            }
            else
            {
                $detailToPenyimpananBMT = [
                    "jumlah"        => $total_porsi_bmt,
                    "saldo_awal"    => $shu_yang_harus_dibagikan->saldo,
                    "saldo_akhir"   => $shu_yang_harus_dibagikan->saldo + $total_porsi_bmt,
                    "id_pengajuan"  => null
                ];
            }


            $dataToPenyimpananBMT = [
                "id_user"   => Auth::user()->id,
                "id_bmt"    => $shu_yang_harus_dibagikan->id,
                "status"    => "Distribusi Pendapatan",
                "transaksi" => $detailToPenyimpananBMT,
                "teller"    => Auth::user()->id
            ];

            $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

            if ($data->jenis == "revenue")
            {
                $shu_yang_harus_dibagikan->saldo = $shu_yang_harus_dibagikan->saldo + $selisihBMTAnggota;
                $shu_yang_harus_dibagikan->save();
                $this->insertPenyimpananShuBerjalan($selisihBMTAnggota);
            }
            else
            {
                $shu_yang_harus_dibagikan->saldo = $shu_yang_harus_dibagikan->saldo + $total_porsi_bmt;
                $shu_yang_harus_dibagikan->save();
                $this->insertPenyimpananShuBerjalan($total_porsi_bmt);
            }



            if($data->jenis == "net_profit")
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

            if($data->jenis == "revenue")
            {
                $bmt_rekening_biaya = BMT::where('id_bmt', 'like', '5%')->get();

                foreach($bmt_rekening_biaya as $rekening_biaya)
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


                $bmt_shu_berjalan = BMT::where('nama', 'SHU BERJALAN')->first();
                $detailToPenyimpananBMT = [
                    "jumlah"        => $total_pendapatan,
                    "saldo_awal"    => $bmt_shu_berjalan->saldo,
                    "saldo_akhir"   => $bmt_shu_berjalan->saldo - $bmt_shu_berjalan->saldo,
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
                $bmt_shu_berjalan->saldo = $bmt_shu_berjalan->saldo - $bmt_shu_berjalan->saldo;
                $bmt_shu_berjalan->save();
            }




            $this->insertPenyimpananRekeningAktivaPasiva();
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
        $distribusi = PenyimpananDistribusi::whereMonth('created_at', Carbon::now()->format('m'))
                                            ->whereYear('created_at', Carbon::now()->format('Y'))
                                            ->get();

        
        if($date !== "")
        {
            $distribusi = PenyimpananDistribusi::whereMonth('created_at', Carbon::parse($date)->format('m'))
                                            ->whereYear('created_at', Carbon::parse($date)->format('Y'))
                                            ->get();
        }
        return $distribusi;
    }

    public function getSaldoAverageProduct($idbmt){

        $distribusi = PenyimpananBMT::where('status', 'Distribusi Pendapatan')
            ->where('created_at', '<', Carbon::now()->toDateString())
            ->orderBy('created_at', 'desc')->first();

        if($distribusi == null)
        {
            $distribusi = PenyimpananBMT::first();
            $date = Carbon::parse($distribusi->created_at);
            $date2 = Carbon::parse($distribusi->created_at);
        }
        else
        {
            $date = Carbon::parse($distribusi->created_at);
            $date = $date->addDays(1);
            $date2 = $date->addDays(1);
        }

        $saldoAkhir = 0.0;
        $storeSaldoAkhir = 0.0;
        $jumlahHari = $this->getDateDiff();
        $divider = 0;
        for ($i = 0 ; $i <= $jumlahHari ; $i++){

            if($date->diffInDays(Carbon::now()) >= 0)
            {
                $divider++;

                $getSaldoAkhir = PenyimpananBMT::select('transaksi') // cari transaksi dengan bmt tertentu di tanggal tersebut
                    ->whereDate('created_at', $date->toDateString())
                    ->where('id_bmt', $idbmt)
                    ->orderBy('created_at', 'DESC')->first();


                if($getSaldoAkhir == null && $i == 0)
                {
                    $getDistribusiPendapatan = PenyimpananBMT::where('status', 'Distribusi Pendapatan')
                        ->where('created_at', '<', Carbon::now()->toDateString())
                        ->where('id_bmt', $idbmt)->orderBy('created_at', 'desc')->first();

                    if ($getDistribusiPendapatan == null )
                    {

                        $distribusiWithDate = PenyimpananBMT::select('transaksi')
                            ->whereDate('created_at','<=', $date2->toDateString() )
                            ->where('id_bmt', $idbmt)->orderBy('created_at', 'desc')->first();

                        if ($distribusiWithDate == null)
                        {
                            $storeSaldoAkhir += $saldoAkhir;
                        }
                        else
                        {
                            $saldoAkhir = json_decode($distribusiWithDate->transaksi)->saldo_akhir;
                            $storeSaldoAkhir += $saldoAkhir;
                        }

                    }
                    else
                    {
                        $saldoAkhir = json_decode($getDistribusiPendapatan->transaksi)->saldo_akhir;
                        $storeSaldoAkhir += $saldoAkhir;
                    }

                }
                else if ($getSaldoAkhir == null)
                {
                    $storeSaldoAkhir += $saldoAkhir;
                }
                else
                {
                    $saldoAkhir = json_decode($getSaldoAkhir->transaksi)->saldo_akhir;
                    $storeSaldoAkhir += $saldoAkhir;
                }
                $date->addDays(1);

            }

        }


        
        return $storeSaldoAkhir/$divider;

    }

    public function getSaldoAverageTabunganAnggota($id_user, $id_product){

        $distribusi = PenyimpananBMT::where('status', 'Distribusi Pendapatan')
            ->where('created_at', '<', Carbon::now()->toDateString())
            ->orderBy('created_at', 'desc')->first();

        if($distribusi == null)
        {
            $distribusi = PenyimpananBMT::first();
            $date = Carbon::parse($distribusi->created_at);
        }
        else
        {
            $date = Carbon::parse($distribusi->created_at);
            $date = $date->addDays(1);
        }



        $saldoAkhir = 0.0;
        $storeSaldoAkhir = 0.0;
        $jumlahHari = $this->getDateDiff();
        $divider = 0;
        for ($i = 0 ; $i <= $jumlahHari ; $i++){

            if($date->diffInDays(Carbon::now()) >= 0)
            {
                $divider++;

                    $getSaldoAkhir = PenyimpananTabungan::select('transaksi')
                        ->whereDate('created_at', $date->toDateString())
                        ->where('id_tabungan', $id_product)
                        ->where('id_user', $id_user)
                        ->orderBy('created_at', 'DESC')->first();





                if($getSaldoAkhir == null && $i == 0)
                {
                        $distribusiWithDate = PenyimpananTabungan::select('transaksi')
                            ->whereDate('created_at', '<=', $date->toDateString())
                            ->where('id_tabungan', $id_product)
                            ->where('id_user', $id_user)
                            ->orderBy('created_at', 'DESC')->first();


                    if ($distribusiWithDate == null)
                    {
                        $storeSaldoAkhir += $saldoAkhir;
                    }
                    else
                    {
                        $saldoAkhir = json_decode($distribusiWithDate->transaksi)->saldo_akhir;
                        $storeSaldoAkhir += $saldoAkhir;
                    }

                }
                else if ($getSaldoAkhir == null)
                {
                    $storeSaldoAkhir += $saldoAkhir;
                }
                else
                {
                    $saldoAkhir = json_decode($getSaldoAkhir->transaksi)->saldo_akhir;
                    $storeSaldoAkhir += $saldoAkhir;
                }
                $date->addDays(1);

            }

        }

        return $storeSaldoAkhir/$divider;

    }

    public function getSaldoAverageDepositoAnggota($id_user, $id_product, $tanggal){

        if ($tanggal !=  Carbon::parse('first day of January 1970') )
        {
            $tanggal = Carbon::parse($tanggal);
        }


        $distribusi = PenyimpananBMT::where('status', 'Distribusi Pendapatan')
            ->where('created_at', '<', Carbon::now()->toDateString())
            ->orderBy('created_at', 'desc')->first();

        if($distribusi == null)
        {
            $distribusi = PenyimpananBMT::first();
            $date = Carbon::parse($distribusi->created_at);
        }
        else
        {
            $date = Carbon::parse($distribusi->created_at);
            $date = $date->addDays(1);
        }

        $saldoAkhir = 0.0;
        $storeSaldoAkhir = 0.0;
        $jumlahHari = $this->getDateDiff();
        $divider = 0;
        $status = "";
        for ($i = 0 ; $i <= $jumlahHari ; $i++){

            if($date->diffInDays(Carbon::now()) >= 0)
            {
                $divider++;

                $getSaldoAkhir = PenyimpananDeposito::select('transaksi')
                    ->whereDate('created_at', $date->toDateString())
                    ->where('id_deposito', $id_product)
                    ->where('id_user', $id_user)
                    ->orderBy('created_at', 'DESC')->first();



                if ($tanggal != Carbon::parse('first day of january 1970')  && ($tanggal->toDateString() <= $date->toDateString() || $tanggal->toDateString() == $date->toDateString())  ) // apabila sudah jatuh tempo atau pencairan lebih awal
                {
                    $storeSaldoAkhir += 0;
                }
                else if($getSaldoAkhir == null && $i == 0)
                {
                    $distribusiWithDate = PenyimpananDeposito::select('transaksi')
                        ->whereDate('created_at', '<=', $date->toDateString())
                        ->where('id_deposito', $id_product)
                        ->where('id_user', $id_user)
                        ->orderBy('created_at', 'desc')->first();


                    if ($distribusiWithDate == null)
                    {
                        $storeSaldoAkhir += $saldoAkhir;
                    }
                    else
                    {
                        $saldoAkhir = json_decode($distribusiWithDate->transaksi)->saldo_akhir;
                        $storeSaldoAkhir += $saldoAkhir;
                    }

                }
                else if ($getSaldoAkhir == null) // apabila hari tersebut tidak ada transaksi
                {
                    $storeSaldoAkhir += $saldoAkhir;
                }
                else //apabila hari tersebut ada transaksi
                {
                    $saldoAkhir = json_decode($getSaldoAkhir->transaksi)->saldo_akhir;
                    $storeSaldoAkhir += $saldoAkhir;
                }
                $date->addDays(1);

            }

        }

        return $storeSaldoAkhir/$divider;


    }


    public function getRataRataSaldoTabungan(){


        $rekening_tabungan = $this->getRekening("TABUNGAN");
        $rekening_deposito = $this->getRekening("DEPOSITO");


        $total_rata_rata = array();

        foreach($rekening_tabungan as $tab)
        {
            $rata_rata_product_tabungan = $this->getSaldoAverageProduct($tab->id_bmt);
            array_push($total_rata_rata, $rata_rata_product_tabungan);
        }

        foreach($rekening_deposito as $dep)
        {
            $rata_rata_product_deposito = $this->getSaldoAverageProduct($dep->id_bmt);
            array_push($total_rata_rata, $rata_rata_product_deposito);
        }

        $total_rata_rata = $this->getTotalProductAverage($total_rata_rata); //valid

        $total_pendapatan = $this->getRekeningPendapatan("saldo");



        $saldo = array();

        $anggota = User::where('tipe', 'anggota')->where('status',2)->where('is_active',1)->get();


        foreach ($anggota as $data)
        {

            $saldoTemporer = array();

            foreach($rekening_tabungan as $tab) {


                $tabungan = Tabungan::join('users', 'users.id', 'tabungan.id_user')
                    ->select('tabungan.*', 'users.detail as user_detail', 'users.nama')
                    ->where('jenis_tabungan', $tab->nama_rekening)
                    ->where('users.id', $data->id)
                    ->get();



                $rata_rata =  $this->getSaldoAverageProduct($tab->id_bmt);
                $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan); // untuk cari pendapatan per produk
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);


                foreach($tabungan as $tab2)
                {
                        $saldoTemporer = array();
                        array_push($saldoTemporer, $data->nama);
                        $saldoRataRata = $this->getSaldoAverageTabunganAnggota($data->id, $tab2->id);
                    if($rata_rata == 0 || $porsi_anggota == 0){
                        $bagi_hasil = 0;
                    }
                    else
                    {
                        $bagi_hasil = $this->getSaldoAverageTabunganAnggota($tab2->id_user, $tab2->id) / $rata_rata * $porsi_anggota ;
                    }
                        array_push($saldoTemporer, $saldoRataRata);
                        array_push($saldoTemporer, $bagi_hasil);
                        array_push($saldoTemporer, $tab2->jenis_tabungan);

                    if(count($saldoTemporer) != 0 )
                    {
                        array_push($saldo, $saldoTemporer);
                    }

                }

            }


        }

        return $saldo;

    }

    public function getRataRataSaldoTabunganNet(){

        $total_pendapatan = $this->getRekeningSHU("saldo");


        $rekening_tabungan = $this->getRekening("TABUNGAN");
        $rekening_deposito = $this->getRekening("DEPOSITO");

        $total_rata_rata = array();

        foreach($rekening_tabungan as $tab)
        {
            $rata_rata_product_tabungan = $this->getSaldoAverageProduct($tab->id_bmt);
            array_push($total_rata_rata, $rata_rata_product_tabungan);
        }

        foreach($rekening_deposito as $dep)
        {
            $rata_rata_product_deposito = $this->getSaldoAverageProduct($dep->id_bmt);
            array_push($total_rata_rata, $rata_rata_product_deposito);
        }

        $total_rata_rata = $this->getTotalProductAverage($total_rata_rata); //valid



        $saldo = array();

        $anggota = User::where('tipe', 'anggota')->where('status',2)->where('is_active',1)->get();


        foreach ($anggota as $data)
        {

            $saldoTemporer = array();

            foreach($rekening_tabungan as $tab) {


                $tabungan = Tabungan::join('users', 'users.id', 'tabungan.id_user')
                    ->select('tabungan.*', 'users.detail as user_detail', 'users.nama')
                    ->where('jenis_tabungan', $tab->nama_rekening)
                    ->where('users.id', $data->id)
                    ->get();


                $rata_rata =  $this->getSaldoAverageProduct($tab->id_bmt);
                $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan); // untuk cari pendapatan per produk
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);


                foreach($tabungan as $tab2)
                {
                    $saldoTemporer = array();
                    array_push($saldoTemporer, $data->nama);
                    $saldoRataRata = $this->getSaldoAverageTabunganAnggota($data->id, $tab2->id);
                    if($rata_rata == 0 || $porsi_anggota == 0){
                        $bagi_hasil = 0;
                    }
                    else
                    {
                        $bagi_hasil = $this->getSaldoAverageTabunganAnggota($tab2->id_user, $tab2->id) / $rata_rata * $porsi_anggota ;
                    }
                    array_push($saldoTemporer, $saldoRataRata);
                    array_push($saldoTemporer, $bagi_hasil);
                    array_push($saldoTemporer, $tab2->jenis_tabungan);

                    if(count($saldoTemporer) != 0 )
                    {
                        array_push($saldo, $saldoTemporer);
                    }

                }

            }


        }

        return $saldo;

    }

    public function getRataRataSaldoDeposito(){
        $rekening_tabungan = $this->getRekening("TABUNGAN");
        $rekening_deposito = $this->getRekening("DEPOSITO"); // semua deposito
        $saldo = array();
        $total_rata_rata = array();

        foreach($rekening_tabungan as $tab)
        {
            $rata_rata_product_tabungan = $this->getSaldoAverageProduct($tab->id_bmt);
            array_push($total_rata_rata, $rata_rata_product_tabungan);
        }

        foreach($rekening_deposito as $dep)
        {
            $rata_rata_product_deposito = $this->getSaldoAverageProduct($dep->id_bmt);
            array_push($total_rata_rata, $rata_rata_product_deposito);
        }

        $total_rata_rata = $this->getTotalProductAverage($total_rata_rata); //valid
        $total_pendapatan = $this->getRekeningPendapatan("saldo");
        $anggota = User::where('tipe', 'anggota')->where('status',2)->where('is_active',1)->get();

        foreach ($anggota as $data)
        {

            $saldoTemporer = array();

            foreach($rekening_deposito as $dep) {

                $deposito = $this->depositoReporsitory->getDepositoDistribusiDisplay($dep->nama_rekening, $data->id);
                $rata_rata =  $this->getSaldoAverageProduct($dep->id_bmt);
                $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan); // untuk cari pendapatan per produk
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);


                foreach($deposito as $dep2)
                {
                    $saldoTemporer = array();
                    array_push($saldoTemporer, $data->nama);
                    if ($dep2->type == 'jatuhtempo'){
                        $tanggal =  $dep2->tempo;
                    }
                    else if($dep2->type == 'pencairanawal')
                    {
                        $tanggal = $dep2->tanggal_pencairan;
                    }
                    else if($dep2->type == 'active')
                    {
                        $tanggal = Carbon::parse('first day of January 1970');
                    }

                    if($rata_rata == 0 || $porsi_anggota == 0){

                        $bagi_hasil = 0;
                        $saldoRataRata = $this->getSaldoAverageDepositoAnggota($data->id, $dep2->id,$tanggal);
                    }
                    else
                    {

                        $saldoRataRata = $this->getSaldoAverageDepositoAnggota($data->id, $dep2->id,$tanggal);
                        $bagi_hasil = $this->getSaldoAverageDepositoAnggota($dep2->id_user, $dep2->id, $tanggal ) / $rata_rata * $porsi_anggota ;


                    }
                    array_push($saldoTemporer, $saldoRataRata);
                    array_push($saldoTemporer, $bagi_hasil);
                    array_push($saldoTemporer, $dep2->jenis_deposito);

                    if(count($saldoTemporer) != 0 )
                    {
                        array_push($saldo, $saldoTemporer);
                    }

                }

            }


        }

        return $saldo;

    }

    public function getRataRataSaldoDepositoNet(){
        $rekening_tabungan = $this->getRekening("TABUNGAN");
        $rekening_deposito = $this->getRekening("DEPOSITO"); // semua tabungan
        $saldo = array();
        $total_rata_rata = array();

        foreach($rekening_tabungan as $tab)
        {
            $rata_rata_product_tabungan = $this->getSaldoAverageProduct($tab->id_bmt);
            array_push($total_rata_rata, $rata_rata_product_tabungan);
        }

        foreach($rekening_deposito as $dep)
        {
            $rata_rata_product_deposito = $this->getSaldoAverageProduct($dep->id_bmt);
            array_push($total_rata_rata, $rata_rata_product_deposito);
        }

        $total_rata_rata = $this->getTotalProductAverage($total_rata_rata);
        $total_pendapatan = $this->getRekeningSHU("saldo");
        $anggota = User::where('tipe', 'anggota')->where('status',2)->where('is_active',1)->get();

        foreach ($anggota as $data)
        {

            $saldoTemporer = array();

            foreach($rekening_deposito as $dep) {

                $deposito = $this->depositoReporsitory->getDepositoDistribusiDisplay($dep->nama_rekening, $data->id);

                $rata_rata =  $this->getSaldoAverageProduct($dep->id_bmt);
                $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan); // untuk cari pendapatan per produk
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);


                foreach($deposito as $dep2)
                {
                    $saldoTemporer = array();
                    array_push($saldoTemporer, $data->nama);
                    if ($dep2->type == 'jatuhtempo'){
                        $tanggal =  $dep2->tempo;
                    }
                    else if($dep2->type == 'pencairanawal')
                    {
                        $tanggal = $dep2->tanggal_pencairan;
                    }
                    else if($dep2->type == 'active')
                    {
                        $tanggal = Carbon::parse('first day of January 1970');
                    }

                    if($rata_rata == 0 || $porsi_anggota == 0){
                        $bagi_hasil = 0;

                        $saldoRataRata = $this->getSaldoAverageDepositoAnggota($data->id, $dep2->id,$tanggal);
                    }
                    else
                    {
                        $saldoRataRata = $this->getSaldoAverageDepositoAnggota($data->id, $dep2->id, $tanggal);
                        $bagi_hasil = $this->getSaldoAverageDepositoAnggota($dep2->id_user, $dep2->id, $tanggal ) / $rata_rata * $porsi_anggota ;


                    }
                    array_push($saldoTemporer, $saldoRataRata);
                    array_push($saldoTemporer, $bagi_hasil);
                    array_push($saldoTemporer, $dep2->jenis_deposito);

                    if(count($saldoTemporer) != 0 )
                    {
                        array_push($saldo, $saldoTemporer);
                    }

                }

            }


        }

        return $saldo;

    }


    public function insertPenyimpananRekeningLabaRugi(){
        $laba = $this->informationRepository->getPendapatan();
        $rekening3 = $this->informationRepository->getPasiva3();
        $rugi = $this->informationRepository->getRugi();

        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));

        foreach ($laba as $dt){

            $rekening = PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt->id_rekening)->first();

            if($rekening == null)
            {
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                $rek->save();
            }
            else
            {
                PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt->id_rekening)->update([
                   'saldo' => $dt->saldo
                ]);
            }


        }

        foreach ($rugi as $dt){
            $rekening = PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt->id_rekening)->first();

            if($rekening == null)
            {
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                $rek->save();
            }
            else
            {
                PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt->id_rekening)->update([
                    'saldo' => $dt->saldo
                ]);
            }
        }



        foreach ($rekening3 as $dt){
            $rekening = PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt['id_rekening'])->first();

            if($rekening == null)
            {
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt['id_rekening'];
                $rek->periode = $date;
                $rek->saldo = $dt['saldo'];
                $rek->save();
            }
            else
            {
                PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt['id_rekening'])->update([
                    'saldo' => $dt['saldo']
                ]);
            }
        }


    }

    public function insertPenyimpananRekeningPorsiRugi($porsi_anggota, $rekening_margin){
        //masuk ke buku besar lalu keluar lagi
        //tambah ke penyimpanan rekening
        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));

        // update penyimpanan rekening sesuai dengan porsi product
        $rekeningMargin = Rekening::where('id_rekening', $rekening_margin)->select('id')->first();
        $saldoPenyimpananRekening = PenyimpananRekening::where('id_rekening', $rekeningMargin->id)->where('periode',$date)->select('saldo')->first();
        $penyimpananRekening = PenyimpananRekening::where('id_rekening', $rekeningMargin->id)
            ->where('periode',$date)->first();
        $penyimpananRekening->saldo = $saldoPenyimpananRekening->saldo + $porsi_anggota;
        $penyimpananRekening->save();

        //masuk ke buku besar
        $bmt = BMT::where('id_rekening', $rekeningMargin->id)->select('id', 'saldo')->first();
        $saldo = $bmt->saldo + $porsi_anggota;
        $detailToPenyimpananBMT = [
            "jumlah"        => $porsi_anggota,
            "saldo_awal"    => $bmt->saldo,
            "saldo_akhir"   => $bmt->saldo + $porsi_anggota,
            "id_pengajuan"  => null
        ];
        $dataToPenyimpananBMT = [
            "id_user"   => Auth::user()->id,
            "id_bmt"    => $bmt->id,
            "status"    => "Distribusi Pendapatan",
            "transaksi" => $detailToPenyimpananBMT,
            "teller"    => Auth::user()->id
        ];

        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);


        $detailToPenyimpananBMT = [
            "jumlah"        => $porsi_anggota,
            "saldo_awal"    => $saldo,
            "saldo_akhir"   => $saldo - $porsi_anggota,
            "id_pengajuan"  => null
        ];
        $dataToPenyimpananBMT = [
            "id_user"   => Auth::user()->id,
            "id_bmt"    => $bmt->id,
            "status"    => "Distribusi Pendapatan",
            "transaksi" => $detailToPenyimpananBMT,
            "teller"    => Auth::user()->id
        ];

        $this->rekeningReporsitory->insertPenyimpananBMT($dataToPenyimpananBMT);

    }

    public function insertPenyimpananRekeningAktivaPasiva(){
        $aktiva = $this->informationRepository->getAktiva();
        $pasiva = $this->informationRepository->getPasivaDistribusiPendapatan();

        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));

        foreach ($aktiva as $dt){
            $rekening = PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt->id_rekening)->first();

            if($rekening == null)
            {
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt->id_rekening;
                $rek->periode = $date;
                $rek->saldo = $dt->saldo;
                $rek->save();
            }
            else
            {
                PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt->id_rekening)->update([
                    'saldo' => $dt->saldo
                ]);
            }
        }

        foreach ($pasiva as $dt){
            $rekening = PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt['id_rekening'])->first();

            if($rekening == null)
            {
                $rek = new PenyimpananRekening();
                $rek->id_rekening = $dt['id_rekening'];
                $rek->periode = $date;
                $rek->saldo = $dt['saldo'];
                $rek->save();
            }
            else
            {
                PenyimpananRekening::where('periode',$date)->where('id_rekening', $dt['id_rekening'])->update([
                    'saldo' => $dt['saldo']
                ]);
            }
        }

    }

    public function insertPenyimpananShuBerjalan($shu){
        $home = new HomeController();
        $date = $home->MonthShifter(0)->format(('Ym'));

        $rekening = PenyimpananRekening::where('periode',$date)->where('id_rekening', 122 )->first();

        if($rekening == null)
        {
            $rek = new PenyimpananRekening();
            $rek->id_rekening = 122;
            $rek->periode = $date;
            $rek->saldo = $shu;
            $rek->save();
        }
        else
        {
            PenyimpananRekening::where('periode',$date)->where('id_rekening', 122)->update([
                'saldo' => $shu
            ]);
        }
    }

    public function perubahanEquitas($jenis)
    {
        DB::beginTransaction();
        try
        {

            $shu_berjalan_sekarang = BMT::where('id', 344)->select('saldo')->first();
            $saldo_shu_berjalan_sekarang = $shu_berjalan_sekarang->saldo;

            if($jenis == "net_profit")
            {
                $pendapatan = $this->getRekeningSHU();
            }
            else
            {
                $pendapatan = $this->getRekeningPendapatan();
            }

            /** Get Rekening Bagi Hasil */
            $rekening_tabungan = $this->getRekening("TABUNGAN");
            $rekening_deposito = $this->getRekening("DEPOSITO");

            $data_rekening = array();
            $total_rata_rata = array();


            // need some change
            foreach($rekening_tabungan as $tab)
            {
                $rata_rata_product_tabungan = $this->getSaldoAverageProduct($tab->id_bmt);
                array_push($total_rata_rata, $rata_rata_product_tabungan);
            }

            foreach($rekening_deposito as $dep)
            {
                $rata_rata_product_deposito = $this->getSaldoAverageProduct($dep->id_bmt);
                array_push($total_rata_rata, $rata_rata_product_deposito);
            }

            $total_rata_rata = $this->getTotalProductAverage($total_rata_rata); //valid
            if($jenis == "net_profit")
            {
                $total_pendapatan = $this->getRekeningSHU("saldo");
            }
            else
            {
                $total_pendapatan = $this->getRekeningPendapatan("saldo");
            }

            $total_pendapatan_product = 0;
            $total_porsi_bmt = 0;
            $total_porsi_anggota = 0;


            $pendapatan_product = array();
            $rata_rata_saldo_anggota = 0.0;

            $response = array();

            $temp = array();

            foreach($rekening_tabungan as $tab)
            {

                $tabungan = $this->tabunganReporsitory->getTabungan($tab->nama_rekening);
                $rata_rata =  $this->getSaldoAverageProduct($tab->id_bmt);
                $nisbah_anggota = json_decode($tab->detail)->nisbah_anggota;
                $nisbah_bmt = 100 - json_decode($tab->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan); // untuk cari pendapatan per produk
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $total_porsi_anggota += $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $total_porsi_bmt += $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);

                foreach($tabungan as $user_tabungan) {

                    if ($rata_rata == 0 || $porsi_anggota == 0) {
                        $bagi_hasil = 0;
                    } else {
                        $bagi_hasil = $this->getSaldoAverageTabunganAnggota($user_tabungan->id_user, $user_tabungan->id) / $rata_rata * $porsi_anggota;
                    }


                }
            }

            $tanggal = "";


            foreach($rekening_deposito as $dep)
            {
                $deposito = $this->depositoReporsitory->getDepositoDistribusi($dep->nama_rekening);
                $rata_rata =  $this->getSaldoAverageProduct($dep->id_bmt);
                $nisbah_anggota = json_decode($dep->detail)->nisbah_anggota;
                $nisbah_bmt = 100 - json_decode($dep->detail)->nisbah_anggota;
                $pendapatan_product = $this->getPendapatanProduk($rata_rata, $total_rata_rata, $total_pendapatan);
                $porsi_anggota = $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $total_porsi_anggota += $this->getPorsiPendapatanProduct($nisbah_anggota, $pendapatan_product);
                $total_porsi_bmt += $this->getPorsiPendapatanProduct($nisbah_bmt, $pendapatan_product);


                foreach($deposito as $user_deposito)
                {
                    if($rata_rata == 0 || $porsi_anggota == 0){
                        $bagi_hasil = 0;
                    }
                    else
                    {
                        if ($user_deposito->type == 'jatuhtempo'){
                            $tanggal =  $user_deposito->tempo;
                        }
                        else if($user_deposito->type == 'pencairanawal')
                        {
                            $tanggal = $user_deposito->tanggal_pencairan;
                        }
                        else if($user_deposito->type == 'active')
                        {
                            $tanggal = Carbon::parse('first day of January 1970');
                        }

                        $bagi_hasil = $this->getSaldoAverageDepositoAnggota($user_deposito->id_user, $user_deposito->id, $tanggal ) / $rata_rata * $porsi_anggota ;

                    }
                    $id_pencairan = json_decode($user_deposito->detail)->id_pencairan;


                }
            }

            $shu_berjalan = BMT::where('nama', 'SHU BERJALAN')->select('saldo')->first();
            $selisihBMTAnggota = $shu_berjalan->saldo - $total_porsi_anggota;

            if ($jenis == "revenue"){
                $saldo_shu_berjalan_sekarang = $selisihBMTAnggota;
            }
            else
            {
                $saldo_shu_berjalan_sekarang =  $total_porsi_bmt;
            }


            return $saldo_shu_berjalan_sekarang;


            DB::commit();
        }
        catch(Exception $ex)
        {
            DB::rollback();
        }

        return $response;
    }


}

?>