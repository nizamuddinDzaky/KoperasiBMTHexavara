<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jaminan extends Model
{
    protected $table = 'jaminan';

    protected $fillable = [
        'id',
        'nama_jaminan',
        'status',
        'detail',
    ];

}
