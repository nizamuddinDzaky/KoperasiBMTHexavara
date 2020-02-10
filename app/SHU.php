<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SHU extends Model
{
    protected $table = 'shu';

    protected $fillable = [
        'id',
        'id_rekening',
        'nama_shu',
        'status',
        'persentase',
    ];

}
