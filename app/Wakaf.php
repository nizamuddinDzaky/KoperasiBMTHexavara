<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wakaf extends Model
{
    protected $table = 'wakaf';

    protected $fillable = [
        'id',
        'id_wakaf',
        'id_rekening',
        'nama_kegiatan',
        'tanggal_pelaksanaan',
        'status','detail',
    ];

    public function rekening() {
        return $this->belongsTo('App\Rekening', 'id_rekening');
    }

    public function PenyimpananWakaf() {
        return $this->hasMany('App\PenyimpananWakaf', 'id_wakaf');
    }
}
