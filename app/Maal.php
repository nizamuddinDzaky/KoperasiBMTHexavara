<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maal extends Model
{
    protected $table = 'maal';

    protected $fillable = [
        'id',
        'id_maal',
        'id_rekening',
        'nama_kegiatan',
        'tanggal_pelaksanaan',
        'status','detail',
    ];

    public function rekening() {
        return $this->belongsTo('App\Rekening', 'id_rekening');
    }

    public function PenyimpananMaal() {
        return $this->hasMany('App\PenyimpananMaal', 'id_maal');
    }

}
