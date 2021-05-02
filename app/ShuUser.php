<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShuUser extends Model
{
    protected $table = 'shu_user';

    protected $fillable = [
        'id',
        'id_user',
        'shu_pengelola',
        'shu_pengurus',
        'shu_simpanan',
        'shu_margin',
        'total_shu_anggota',
    ];
}
