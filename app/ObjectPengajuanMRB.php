<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObjectPengajuanMRB extends Model
{
    protected $table = 'objek_pengajuan_mrb';

    protected $fillable = [
        'id',
        'nama',
        'is_active',
        'created_at',
        'updated_at'
    ];

}
