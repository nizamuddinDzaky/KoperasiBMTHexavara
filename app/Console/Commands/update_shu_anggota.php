<?php

namespace App\Console\Commands;
use App\Repositories\SHUTahunanRepositories;
use App\User;
use Illuminate\Console\Command;

class update_shu_anggota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shu_user:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            $harta_anggota = array();
            $margin_anggota = array();
            $anggota = User::where([ ['tipe', 'anggota'], ['status', '2'], ['is_active',1] ])->get();
            foreach($anggota as $item)
            {
                array_push($harta_anggota, json_decode($item->wajib_pokok)->wajib + json_decode($item->wajib_pokok)->pokok + json_decode($item->wajib_pokok)->khusus);
            }
            foreach ($anggota as $item){
                array_push($margin_anggota, json_decode($item->wajib_pokok)->margin);
            }
            $total_harta = array_sum($harta_anggota);
            $total_margin =  array_sum($margin_anggota);

            $distribusi = array();

            $data_shu = SHU::where('status', 'active')->get();


            foreach($data_shu as $item) {
                foreach($anggota as $value)
                {
                    if($item->nama_shu == "ANGGOTA" && $value->role == "anggota" && $value->tipe =="anggota") {
                        $porsi_shu = $this->getPorsiSHU("ANGGOTA");
                        $harta_anggota = json_decode($value->wajib_pokok)->wajib + json_decode($value->wajib_pokok)->pokok + json_decode($value->wajib_pokok)->khusus;
                        $margin_anggota = json_decode($value->wajib_pokok)->margin;
                        $dibagikan_ke_anggota = $harta_anggota > 0 && $total_harta > 0 ? $harta_anggota / $total_harta * 0.5 * $porsi_shu : 0;
                        $dibagikan_ke_anggota_margin = $margin_anggota > 0 && $total_margin > 0 ? $margin_anggota / $total_margin * 0.5 * $porsi_shu : 0;
                        $temp = array(
                            "no_ktp"    => $value->no_ktp,
                            "nama"  => $value->nama,
                            "account_type" => $item->nama_shu,
                            "simpanan_wajib" => json_decode($value->wajib_pokok)->wajib,
                            "margin" => json_decode($value->wajib_pokok)->margin,
                            "simpanan_pokok" => json_decode($value->wajib_pokok)->pokok,
                            "simpanan_khusus" => json_decode($value->wajib_pokok)->khusus,
                            "shu_anggota" => $dibagikan_ke_anggota + $dibagikan_ke_anggota_margin,
                            "shu_pengelola" => 0,
                            "shu_pengurus"  => 0,
                            "id_rekening" => $item->id_rekening,
                            "pendapatan_simpanan" => $dibagikan_ke_anggota,
                            "pendapatan_margin" => $dibagikan_ke_anggota_margin
                        );
                        array_push($distribusi, $temp);
                    }

                    if($item->nama_shu == "PENGURUS" && $value->role == "pengurus"  && $value->tipe =="anggota") {
                        $porsi_shu_pengurus = $this->getPorsiSHU("PENGURUS");
                        $porsi_shu_anggota = $this->getPorsiSHU("ANGGOTA");
                        $margin_anggota = json_decode($value->wajib_pokok)->margin;
                        $user = User::where([ ['status', '2'], ['role', 'pengurus'], ['tipe', 'anggota'] ])
                            ->orWhere([ ['status', '2'], ['tipe', 'anggota'], ['role', 'pengelolah&pengurus'] ])->get();
                        $harta_anggota = json_decode($value->wajib_pokok)->wajib + json_decode($value->wajib_pokok)->pokok + json_decode($value->wajib_pokok)->khusus;
                        $dibagikan_ke_anggota = $harta_anggota > 0 && $total_harta > 0 ? $harta_anggota / $total_harta * 0.5 * $porsi_shu_anggota : 0;
                        $dibagikan_ke_anggota_margin =  $margin_anggota > 0 && $total_margin > 0 ? $margin_anggota / $total_margin * 0.5 * $porsi_shu_anggota : 0;
                        $temp = array(
                            "no_ktp"    => $value->no_ktp,
                            "nama"  => $value->nama,
                            "account_type" => $item->nama_shu,
                            "simpanan_wajib" => json_decode($value->wajib_pokok)->wajib,
                            "margin" => json_decode($value->wajib_pokok)->margin,
                            "simpanan_pokok" => json_decode($value->wajib_pokok)->pokok,
                            "simpanan_khusus" => json_decode($value->wajib_pokok)->khusus,
                            "shu_anggota" => $dibagikan_ke_anggota + $dibagikan_ke_anggota_margin,
                            "shu_pengelola" => 0,
                            "shu_pengurus"  => $porsi_shu_pengurus / count($user),
                            "id_rekening" => $item->id_rekening,
                            "pendapatan_simpanan" => $dibagikan_ke_anggota,
                            "pendapatan_margin" => $dibagikan_ke_anggota_margin
                        );
                        array_push($distribusi, $temp);
                    }
                    if($item->nama_shu == "PENGELOLAH" && $value->role == "pengelolah" && $value->tipe =="anggota") {
                        $porsi_shu_pengelolah = $this->getPorsiSHU("PENGELOLAH");
                        $porsi_shu_anggota = $this->getPorsiSHU("ANGGOTA");
                        $user = User::where([ ['status', '2'], ['role', 'pengelolah'], ['tipe', 'anggota'] ])
                            ->orWhere([ ['status', '2'], ['tipe', 'anggota'], ['role', 'pengelolah&pengurus'] ])->get();
                        $harta_anggota = json_decode($value->wajib_pokok)->wajib + json_decode($value->wajib_pokok)->pokok + json_decode($value->wajib_pokok)->khusus;
                        $margin_anggota = json_decode($value->wajib_pokok)->margin;
                        $dibagikan_ke_anggota = $harta_anggota > 0 && $total_harta > 0 ? $harta_anggota / $total_harta * 0.5 * $porsi_shu_anggota : 0;
                        $dibagikan_ke_anggota_margin = $margin_anggota > 0 && $total_margin > 0 ? $margin_anggota / $total_margin * 0.5 * $porsi_shu_anggota : 0;
                        $temp = array(
                            "no_ktp"    => $value->no_ktp,
                            "nama"  => $value->nama,
                            "account_type" => $item->nama_shu,
                            "simpanan_wajib" => json_decode($value->wajib_pokok)->wajib,
                            "margin" => json_decode($value->wajib_pokok)->margin,
                            "simpanan_pokok" => json_decode($value->wajib_pokok)->pokok,
                            "simpanan_khusus" => json_decode($value->wajib_pokok)->khusus,
                            "shu_anggota" => $dibagikan_ke_anggota + $dibagikan_ke_anggota_margin,
                            "shu_pengelola" => $porsi_shu_pengelolah / count($user),
                            "shu_pengurus"  => 0,
                            "id_rekening" => $item->id_rekening,
                            "pendapatan_simpanan" => $dibagikan_ke_anggota,
                            "pendapatan_margin" => $dibagikan_ke_anggota_margin
                        );
                        array_push($distribusi, $temp);
                    }
                    if ($item->nama_shu == "PENGELOLAH"  && $value->role == "pengelolah&pengurus" && $value->tipe =="anggota" )
                    {
                        $porsi_shu_pengelolah = $this->getPorsiSHU("PENGELOLAH");
                        $porsi_shu_pengurus = $this->getPorsiSHU("PENGURUS");
                        $porsi_shu_anggota = $this->getPorsiSHU("ANGGOTA");
                        $userPengurus = User::where([ ['status', '2'], ['role', 'pengurus'], ['tipe', 'anggota'] ])
                            ->orWhere([ ['status', '2'], ['tipe', 'anggota'], ['role', 'pengelolah&pengurus'] ])->get();
                        $userPengelolah = User::where([ ['status', '2'], ['role', 'pengelolah'], ['tipe', 'anggota'] ])
                            ->orWhere([ ['status', '2'], ['tipe', 'anggota'], ['role', 'pengelolah&pengurus'] ])->get();
                        $harta_anggota = json_decode($value->wajib_pokok)->wajib + json_decode($value->wajib_pokok)->pokok + json_decode($value->wajib_pokok)->khusus;
                        $margin_anggota = json_decode($value->wajib_pokok)->margin;
                        $dibagikan_ke_anggota = $harta_anggota > 0 && $total_harta > 0 ? $harta_anggota / $total_harta * 0.5 * $porsi_shu_anggota : 0;
                        $dibagikan_ke_anggota_margin =$margin_anggota > 0 && $total_margin > 0 ? $margin_anggota / $total_margin * 0.5 * $porsi_shu_anggota : 0;

                        $temp = array(
                            "no_ktp"    => $value->no_ktp,
                            "nama"  => $value->nama,
                            "account_type" => $item->nama_shu,
                            "simpanan_wajib" => json_decode($value->wajib_pokok)->wajib,
                            "margin" => json_decode($value->wajib_pokok)->margin,
                            "simpanan_pokok" => json_decode($value->wajib_pokok)->pokok,
                            "simpanan_khusus" => json_decode($value->wajib_pokok)->khusus,
                            "shu_anggota" => $dibagikan_ke_anggota + $dibagikan_ke_anggota_margin,
                            "shu_pengelola" => $porsi_shu_pengelolah / count($userPengelolah),
                            "shu_pengurus"  => $porsi_shu_pengurus / count($userPengurus),
                            "id_rekening" => $item->id_rekening,
                            "pendapatan_simpanan" => $dibagikan_ke_anggota,
                            "pendapatan_margin" => $dibagikan_ke_anggota_margin

                        );
                        array_push($distribusi, $temp);

                    }
                }


                
                if($item->nama_shu !== "ANGGOTA" && $item->nama_shu !== "PENGELOLAH" && $item->nama_shu !== "PENGURUS") {
                    $bmt = BMT::where('nama', $item->nama_shu)->first();
                    $temp = array(
                        "no_ktp"    => "",
                        "nama"      => "",
                        "account_type" => $item->nama_shu,
                        "simpanan_wajib" => 0,
                        "simpanan_pokok"    => 0,
                        "simpanan_khusus" => 0,
                        "margin"        => 0,
                        "shu_anggota"   => 0,
                        "shu_pengelola" => 0,
                        "shu_pengurus"  => 0,
                        "id_rekening" => $item->id_rekening,
                        "porsi_shu" => $this->getPorsiSHU($item->nama_shu),
                        "nama_shu"  => $item->nama_shu,
                        "pendapatan_margin" => 0,
                        "pendapatan_simpanan" => 0,
                    );
                    array_push($distribusi, $temp);

                }
            }
            print_r(count($distribusi));
        // echo "qwe";
    }
}
