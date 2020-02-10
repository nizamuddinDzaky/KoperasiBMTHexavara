<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    protected $fillable = [
        'id',
        'id_user',
        'id_rekening',
        'jenis_pengajuan',
        'status',
        'kategori',
        'detail',
    ];

    public function rekening() {
        return $this->belongsTo('App\Rekening', 'id_rekening');
    }

    public function user(){
        return $this->belongsTo('App\User','id');
    }

}
