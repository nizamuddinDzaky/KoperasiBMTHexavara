<?php

namespace App\Repositories;

use App\Http\Controllers\HomeController;
use App\Pengajuan;
use App\PenyimpananWajibPokok;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpananReporsitory {

    /** 
     * Get pengajuan data with kategory Simpanan from specific user
     * @return Array
    */
    public function getUserPengajuanSimpanan()
    {
        $simpanan = Pengajuan::where('kategori', 'Simpanan Wajib')->orWhere('kategori', 'Simpanan Pokok')->get();

        return $simpanan;
    }

    /** 
     * Get simpanan wajib dan pokok in user data
     * @return Array
    */
    public function getSimwaAndSimpok()
    {
        $simwaAndSimpok = User::where('id', Auth::user()->id)->select(['wajib_pokok', 'created_at'])->get();

        return $simwaAndSimpok;
    }
}