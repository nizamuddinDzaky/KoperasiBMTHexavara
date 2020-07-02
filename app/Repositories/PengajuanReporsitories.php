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
        
        if($data['teller'] != null)
        {
            $pengajuan->id = $data['id'];
        }
        $pengajuan->id_user = $data['id_user'];
        $pengajuan->id_rekening = $data['id_rekening'];
        $pengajuan->jenis_pengajuan = $data['jenis_pengajuan'];
        $pengajuan->status = $data['status'];
        $pengajuan->kategori = $data['kategori'];
        $pengajuan->detail = json_encode($data['detail']);
        $pengajuan->teller = $data['teller'];

        if($pengajuan->save())
        {
            if($data['teller'] != 0)
            {
                $data = $data['id'];
            }
            $response = array("type" => "success", "message" => "Pengajuan " . $data['jenis_pengajuan'] . " berhasil dibuat", "data" => $data);
        } 
        else 
        {
            $response = array("type" => "error", "message" => "Pengajuan " . $data['jenis_pengajuan'] . " gagal dibuat");
        }
        return $response;
    }

    /** 
     * Find pengajuan
     * @return Response
    */
    public static function findPengajuan($id)
    {
        $pengajuan = Pengajuan::where('id', $id)->first();
        return $pengajuan;
    }

    /**
     * Get pengajuan berdasarkan kategori tertentu
     * @return Response 
    */
    public function getPengajuanSpecificCategory($category)
    {
        $pengajuan = Pengajuan::where('jenis_pengajuan', $category)->get();
        return $pengajuan;
    }

    /** 
     * Get data for notification
     * @return Response
    */
    public function getNotification()
    {
        $pengajuan = Pengajuan::where('status', 'Menunggu Konfirmasi')->get();
        return $pengajuan;
    }

}

?>