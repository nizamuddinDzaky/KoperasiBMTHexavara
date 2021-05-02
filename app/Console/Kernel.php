<?php

namespace App\Console;

use App\BMT;
use App\Repositories\SHUTahunanRepositories as shu1;
use App\SHU;
use App\ShuUser;
use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function ()
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
                        $shu_user = $this->get_data_shu_by_user($value->id);
                        $shu_user->id_user = $value->id;
                        $shu_user->shu_pengelola = 0;
                        $shu_user->shu_pengurus = 0;
                        $shu_user->shu_simpanan = $dibagikan_ke_anggota;
                        $shu_user->shu_margin = $dibagikan_ke_anggota_margin;
                        $shu_user->total_shu_anggota = $dibagikan_ke_anggota + $dibagikan_ke_anggota_margin;
                        $shu_user->save();

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
                        $shu_user = $this->get_data_shu_by_user($value->id);
                        $shu_user->id_user = $value->id;
                        $shu_user->shu_pengelola = 0;
                        $shu_user->shu_pengurus = $porsi_shu_pengurus / count($user);
                        $shu_user->shu_simpanan =  $dibagikan_ke_anggota;
                        $shu_user->shu_margin = $dibagikan_ke_anggota_margin;
                        $shu_user->total_shu_anggota =  $dibagikan_ke_anggota + $dibagikan_ke_anggota_margin;
                        $shu_user->save();

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
                        $shu_user = $this->get_data_shu_by_user($value->id);
                        $shu_user->id_user = $value->id;
                        $shu_user->shu_pengelola = $porsi_shu_pengelolah / count($user);
                        $shu_user->shu_pengurus = 0;
                        $shu_user->shu_simpanan =  $dibagikan_ke_anggota;
                        $shu_user->shu_margin = $dibagikan_ke_anggota_margin;
                        $shu_user->total_shu_anggota = $dibagikan_ke_anggota + $dibagikan_ke_anggota_margin;
                        $shu_user->save();

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
                        $shu_user = $this->get_data_shu_by_user($value->id);
                        $shu_user->id_user = $value->id;
                        $shu_user->shu_pengelola = $porsi_shu_pengelolah / count($userPengelolah);
                        $shu_user->shu_pengurus = $porsi_shu_pengurus / count($userPengurus);
                        $shu_user->shu_simpanan =  $dibagikan_ke_anggota;
                        $shu_user->shu_margin = $dibagikan_ke_anggota_margin;
                        $shu_user->total_shu_anggota = $dibagikan_ke_anggota + $dibagikan_ke_anggota_margin;
                        $shu_user->save();

                    }
                }

                if($item->nama_shu !== "ANGGOTA" && $item->nama_shu !== "PENGELOLAH" && $item->nama_shu !== "PENGURUS") {
                }
            }
        })->everyMinute();
        
    }

    public function getPorsiSHU($role)
    {
        $saldo_untuk_dibagikan = BMT::where('nama', 'SHU YANG HARUS DIBAGIKAN')->first();
        $role_porsi = SHU::where([ ['status', 'active'], ['nama_shu', $role] ])->first();
        $porsi = $role_porsi->persentase * $saldo_untuk_dibagikan->saldo;

        return $porsi;
    }


    public function get_data_shu_by_user($id_user)
    {
        $shu_user = ShuUser::where('id_user' , $id_user)->first();
        if(!$shu_user){
            $shu_user = new ShuUser; 
        }
        return $shu_user;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
