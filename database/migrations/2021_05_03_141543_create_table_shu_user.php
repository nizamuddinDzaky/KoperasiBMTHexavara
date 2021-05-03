<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableShuUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shu_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->double('shu_pengelola', 8, 2);
            $table->double('shu_pengurus', 8, 2);
            $table->double('shu_simpanan', 8, 2);
            $table->double('shu_margin', 8, 2);
            $table->double('total_shu_anggota', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shu_user');
    }
}
