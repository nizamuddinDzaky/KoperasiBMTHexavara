<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananSHU extends Model
{
    protected $table = 'penyimpanan_shu';

    protected $fillable = [
        'id',
        'id_user',
        'status',
        'transaksi',
    ];

}
