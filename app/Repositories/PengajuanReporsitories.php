<?php

namespace App\Repositories;

use App\User;
use App\Pengajuan;

class PengajuanReporsitories {

    /** 
     * Get all user pengajuan
     * @return Response
    */
    public function getAllPengajuan()
    {
        $pengajuan = Pengajuan::All();
        return $pengajuan;
    }

    /** 
     * Get all pengajuan from specified user
     * @return Response
    */
    public function getUserPengajuan($user_id)
    {
        $pengajuan = Pengajuan::where('id_user', $user_id)->get();
        return $pengajuan;
    }
}

?>