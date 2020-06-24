<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'no_ktp',
        'alamat',
        'tipe',
        'status',
        'detail',
        'password',
        'pathfile',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tabungan()
    {
        return $this->hasMany('App\Tabungan', 'id_user');
    }

    public function deposito()
    {
        return $this->hasMany('App\Deposito', 'id_user');
    }

    public function pembiayaan()
    {
        return $this->hasMany('App\Pembiayaan', 'id_user');
    }

    public function pengajuan()
    {
        return $this->hasMany('App\Pengajuan');
    }

    public function Vote()
    {
        return $this->hasMany('App\Vote', 'id_user');
    }

    public function Rapat()
    {
        return $this->hasMany('App\Rapat', 'id_admin');
    }

    public function PenyimpananBMT()
    {
        return $this->hasMany('App\PenyimpananBMT', 'id_user');
    }

    public function PenyimpananMaal() {
        return $this->hasMany('App\PenyimpananMaal', 'id_donatur');
    }

    public function PenyimpananDistribusi() {
        return $this->hasMany('App\PenyimpananDistribusi', 'id_user');
    }

    public function PenyimpananWajibPokok() {
        return $this->hasMany('App\PenyimpananWajibPokok', 'id_user');
    }

    public function PenyimpananTabungan() {
        return $this->hasMany('App\PenyimpananTabungan', 'id_user');
    }
}
