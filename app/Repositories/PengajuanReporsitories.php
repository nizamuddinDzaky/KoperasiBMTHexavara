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

    /** 
     * Create pengajuan in database
     * @return Response
    */
    public function createPengajuan($data)
    {
        $pengajuan = new Pengajuan();
        
        $pengajuan->id_user = $data['id_user'];
        $pengajuan->id_rekening = $data['id_rekening'];
        $pengajuan->jenis_pengajuan = $data['jenis_pengajuan'];
        $pengajuan->status = $data['status'];
        $pengajuan->kategori = $data['kategori'];
        $pengajuan->detail = json_encode($data['detail']);
        $pengajuan->teller = $data['teller'];

        if($pengajuan->save())
        {
            $response = array("type" => "success", "message" => "Pengajuan " . $data['kategori'] . " berhasil dibuat");
        } 
        else 
        {
            $response = array("type" => "error", "message" => "Pengajuan " . $data['kategori'] . " gagal dibuat");
        }
        return $response;
    }

}

?>