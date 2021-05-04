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
            $table->unsignedDecimal('shu_pengelola', 15, 2);
            $table->unsignedDecimal('shu_pengurus', 15, 2);
            $table->unsignedDecimal('shu_simpanan', 15, 2);
            $table->unsignedDecimal('shu_margin', 15, 2);
            $table->unsignedDecimal('total_shu_anggota', 15, 2);
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
