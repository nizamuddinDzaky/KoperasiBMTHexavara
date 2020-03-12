<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\User;
use App\Pengajuan;
use App\Rekening;

class RekeningReporsitories {

    /** 
     * Get rekening with several category excluded
     * @return Response
    */
    public function getRekeningExcludedCategory($excluded)
    {
        $rekening = "SELECT * FROM rekening WHERE nama_rekening NOT LIKE '%" . $excluded[0] . "%'";
        for($i=1; $i < count($excluded); $i++) {
            $rekening .= " AND nama_rekening NOT LIKE '%" . $excluded[$i] . "%'";
        }

        $data = DB::select( DB::raw($rekening) );
        
        return $data;
    }
}

?>