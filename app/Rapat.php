<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rapat extends Model
{
    protected $table = 'rapat';

    protected $fillable = [
        'id',
        'id_admin',
        'judul',
        'description',
        'foto',
        'tanggal_dibuat',
        'tanggal_berakhir'
    ];
    
    public function User() {
        return $this->belongsTo('App\User', 'id_admin');
    }

    public function Vote() {
        return $this->hasMany('App\Vote', 'id_rapat');
    }
}
