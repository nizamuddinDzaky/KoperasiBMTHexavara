<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BMT extends Model
{
    protected $table = 'bmt';

    protected $fillable = [
        'id',
        'id_bmt',
        'id_rekening',
        'nama',
        'saldo',
        'detail',
    ];

    public function rekening() {
        return $this->belongsTo('App\Rekening', 'id_rekening');
    }

}
