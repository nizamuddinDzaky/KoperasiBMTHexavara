<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenyimpananUsers extends Model
{
    protected $table = 'penyimpanan_users';

    protected $fillable = [
        'id',
        'id_user',
        'periode',
        'transaksi',
    ];

}
