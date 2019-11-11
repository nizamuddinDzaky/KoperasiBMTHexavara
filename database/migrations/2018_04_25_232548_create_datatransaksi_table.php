<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatatransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('penyimpanan_rekening');
        Schema::dropIfExists('penyimpanan_tabungan');
        Schema::dropIfExists('penyimpanan_deposito');
        Schema::dropIfExists('penyimpanan_pembiayaan');
        Schema::dropIfExists('penyimpanan_bmt');
        Schema::dropIfExists('penyimpanan_maal');
//        Schema::dropIfExists('angsuran');

        Schema::create('penyimpanan_rekening', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_rekening');
            $table->string('periode');
            $table->string('saldo');
            $table->timestamps();
            $table->foreign('id_rekening')
                ->references('id')
                ->on('rekening')
                ->onDelete('cascade');
        });

        Schema::create('penyimpanan_tabungan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_tabungan');
            $table->string('status');
            $table->text('transaksi');
            $table->timestamps();
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_tabungan')
                ->references('id')
                ->on('tabungan')
                ->onDelete('cascade');
        });

        Schema::create('penyimpanan_deposito', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_deposito');
            $table->string('status');
            $table->text('transaksi');
            $table->timestamps();
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_deposito')
                ->references('id')
                ->on('deposito')
                ->onDelete('cascade');
        });

        Schema::create('penyimpanan_pembiayaan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_pembiayaan');
            $table->string('status');
            $table->text('transaksi');
            $table->timestamps();
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_pembiayaan')
                ->references('id')
                ->on('pembiayaan')
                ->onDelete('cascade');
        });

        Schema::create('penyimpanan_bmt', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_bmt');
            $table->string('status');
            $table->text('transaksi');
            $table->timestamps();
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_bmt')
                ->references('id')
                ->on('bmt')
                ->onDelete('cascade');
        });

        Schema::create('penyimpanan_maal', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_donatur');
            $table->unsignedInteger('id_maal');
            $table->string('status');
            $table->text('transaksi');
            $table->timestamps();
            $table->foreign('id_donatur')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_maal')
                ->references('id')
                ->on('maal')
                ->onDelete('cascade');
        });


//        Schema::create('angsuran', function (Blueprint $table) {
//            $table->increments('id');
//            $table->unsignedInteger('id_user');
//            $table->unsignedInteger('id_pembiayaan');
//            $table->string('keterangan');
//            $table->integer('angsuran');
//            $table->timestamps();
//            $table->foreign('id_user')
//                ->references('id')
//                ->on('users')
//                ->onDelete('cascade');
//            $table->foreign('id_pembiayaan')
//                ->references('id')
//                ->on('pembiayaan')
//                ->onDelete('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penyimpanan_rekening');
        Schema::dropIfExists('penyimpanan_tabungan');
        Schema::dropIfExists('penyimpanan_deposito');
        Schema::dropIfExists('penyimpanan_pembiayaan');
        Schema::dropIfExists('penyimpanan_bmt');
        Schema::dropIfExists('penyimpanan_maal');
//        Schema::dropIfExists('angsuran');
    }
}
