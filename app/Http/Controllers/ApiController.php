<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TabunganReporsitories;

class ApiController extends Controller
{
    public function __construct(
        TabunganReporsitories $tabunganReporsitory
    )
    {
        $this->tabunganReporsitory = $tabunganReporsitory;
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
}
