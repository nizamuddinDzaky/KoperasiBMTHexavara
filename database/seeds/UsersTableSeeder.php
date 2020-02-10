<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rekening = [
            'id_rekening' =>'13'
        ];
        DB::table('users')->insert([
            'nama' => 'admin',
            'no_ktp' =>'admin',
            'alamat' => 'bmt',
            'tipe' =>'admin',
            'status'=>'',
            'detail'=>json_encode($rekening),
            'password' => bcrypt('admin'),

        ]);
        DB::table('users')->insert([
            'nama' => 'user',
            'no_ktp' =>'user',
            'alamat' => 'bmt',
            'tipe' =>'anggota',
            'status'=>'1',
            'detail'=>"",
            'password' => bcrypt('user'),
        ]);

        DB::table('users')->insert([
            'nama' => 'teller',
            'no_ktp' =>'teller',
            'alamat' => 'bmt',
            'tipe' =>'teller',
            'status'=>'2',
            'detail'=> json_encode($rekening),
            'password' => bcrypt('teller'),
        ]);
        //
    }
}
