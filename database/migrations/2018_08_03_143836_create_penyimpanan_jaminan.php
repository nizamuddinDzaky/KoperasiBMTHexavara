<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenyimpananJaminan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('penyimpanan_jaminan');
        Schema::create('penyimpanan_jaminan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_jaminan');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_pengajuan');
            $table->text('transaksi');
            $table->timestamps();
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_pengajuan')
                ->references('id')
                ->on('pengajuan')
                ->onDelete('cascade');
            $table->foreign('id_jaminan')
                ->references('id')
                ->on('jaminan')
                ->onDelete('cascade');
        });
        //
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penyimpanan_jaminan');
        //
    }
}
