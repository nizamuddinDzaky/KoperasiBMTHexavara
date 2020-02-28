<?php

namespace App\Repositories;

use App\Http\Controllers\HomeController;
use App\Pembiayaan;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembiayaanReporsitory {

    /** 
     * Ambil data pembiayaan specific user
     * @return Array
    */
    public function getPembiayaanSpecificUser()
    {
        $pembiayaan = Pembiayaan::where("id_user", Auth::user()->id)->get();

        return $pembiayaan;
    }
}
