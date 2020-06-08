<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TabunganReporsitories;
use App\Repositories\RekeningReporsitories;

class ApiController extends Controller
{
    public function __construct(
        TabunganReporsitories $tabunganReporsitory,
        RekeningReporsitories $rekeningReporsitory
    )
    {
        $this->tabunganReporsitory = $tabunganReporsitory;
        $this->rekeningReporsitory = $rekeningReporsitory;
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
     * Get rekening with excluding controller
     * @return Response
    */
    public function getRekeningWithExcluding()
    {
        $rekening = $this->rekeningReporsitory->getRekeningExcludedCategory(array('kas', 'bank', 'shu berjalan'));
        return response()->json($rekening);
    }

}
