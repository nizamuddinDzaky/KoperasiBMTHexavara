<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;
use App\Repositories\PembiayaanReporsitory;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function __construct(
        TabunganReporsitories $tabunganReporsitory,
        RekeningReporsitories $rekeningReporsitory,
        PembiayaanReporsitory $pembiayaanReporsitory
    )
    {
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
        $this->pembiayaanReporsitory = $pembiayaanReporsitory;
    }
    
    /** 
     * Get user tabungan controller
     * @return Response
    */
    public function getUserTabungan($id_user)
    {
        $tabungan = $this->tabunganReporsitory->getUserTabungan($id_user);
        return response()->json($tabungan);
    }

    /** 
     * Get user tabungan with excluding specific rekening controller
     * @return Response
    */
    public function getUserTabunganWithSpecificExclude(Request $request)
    {
        $tabungan = $this->tabunganReporsitory->getUserTabungan($id_user);
        return response()->json($tabungan);
    }

    /** 
     * Get user pembiayaan controller
     * @return Response
    */
    public function getUserPembiayaan($id_user)
    {
        $pembiayaan = $this->pembiayaanReporsitory->getPembiayaanSpecificUser($id_user, "active");
        return response()->json($pembiayaan);
    }

    /** 
     * Get rekening with excluding controller
     * @return Response
    */
    public function getRekeningWithExcluding(Request $request)
    {
        if(Auth::user()->tipe == "admin")
        {
            $rekening = $this->rekeningReporsitory->getRekeningExcludedCategory(['KAS ADMIN'], "detail", "id_rekening");
        }
        else
        {
            $rekening = $this->rekeningReporsitory->getRekeningExcludedCategory(['kas', 'bank', 'shu berjalan'], "detail", "id_rekening");
        }
        return response()->json($rekening);
    }

}
