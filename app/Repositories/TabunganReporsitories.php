<?php

namespace App\Repositories;

use App\BMT;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

use App\Rekening;
use App\Tabungan;

class TabunganReporsitories {
    
    /** 
     * Get All Tabungan
     * @return Response
    */
    public function getRekening($kategori_rekening)
    {
        $tabungan = Rekening::where('katagori_rekening', $kategori_rekening)->get();
        return $tabungan;
    }

    /** 
     * Find specific tabungan
     * @return Response
    */
    public function findRekening($kategori_rekening, $id_tabungan)
    {
        $tabungan = Rekening::where([ ['katagori_rekening', $kategori_rekening], ['id_rekening', $id_tabungan] ])->get();
        return $tabungan;
    }

    /** 
     * Get user within specific tabungan
     * @return Response
    */
    public function getUserInTabungan($id_tabungan)
    {
        $userInTabungan = Tabungan::where('id_rekening', $id_tabungan)
                        ->join('users', 'tabungan.id_user', 'users.id')
                        ->select(['users.*', 'users.id as id_user', 'users.detail as detail_user', 'tabungan.*', 'tabungan.id as id_tabungan', 'tabungan.detail as detail_tabungan'])
                        ->get();
        return $userInTabungan;
    }

    /** 
     * Get tabungan for specific user
     * @return Response
    */
    public function getUserTabungan($id_user)
    {
        $tabunganUser = Tabungan::where('id_user', $id_user)->with('user')->get();
        return $tabunganUser;
    }

    /** 
     * Get BMT saldo
     * @return Response
    */
    public function getBMTSaldo($id_bmt)
    {
        $bmt = BMT::where('id_rekening', $id_bmt)->get();
        return $bmt;
    }
}