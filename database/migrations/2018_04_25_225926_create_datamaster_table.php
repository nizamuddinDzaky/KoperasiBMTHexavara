<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatamasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tabungan');
        Schema::dropIfExists('deposito');
        Schema::dropIfExists('pembiayaan');
        Schema::dropIfExists('bmt');
        Schema::dropIfExists('maal');
        Schema::dropIfExists('pengajuan');
        Schema::dropIfExists('rekening');

        Schema::create('rekening', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_rekening')->unique();
            $table->string('id_induk');
            $table->string('nama_rekening');
            $table->string('tipe_rekening');
            $table->string('katagori_rekening');
            $table->text('detail');
            $table->timestamps();
        });

        Schema::create('pengajuan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_rekening');
            $table->string('jenis_pengajuan');
            $table->string('status');
            $table->string('kategori');
            $table->text('detail');
            $table->timestamps();
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_rekening')
                ->references('id')
                ->on('rekening')
                ->onDelete('cascade');
        });

        Schema::create('tabungan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_tabungan')->unique();
            $table->unsignedInteger('id_rekening');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_pengajuan');
            $table->string('jenis_tabungan');
            $table->text('detail');
            $table->text('status');
            $table->timestamps();
            $table->foreign('id_rekening')
                ->references('id')
                ->on('rekening')
                ->onDelete('cascade');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_pengajuan')
                ->references('id')
                ->on('pengajuan')
                ->onDelete('cascade');
        });

        Schema::create('deposito', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_deposito')->unique();
            $table->unsignedInteger('id_rekening');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_pengajuan');
            $table->string('jenis_deposito');
            $table->text('detail');
            $table->date('tempo');
            $table->text('status');
            $table->timestamps();
            $table->foreign('id_rekening')
                ->references('id')
                ->on('rekening')
                ->onDelete('cascade');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_pengajuan')
                ->references('id')
                ->on('pengajuan')
                ->onDelete('cascade');
        });


        Schema::create('pembiayaan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_pembiayaan')->unique();
            $table->unsignedInteger('id_rekening');
            $table->unsignedInteger('id_user');
            $table->unsignedInteger('id_pengajuan');
            $table->string('jenis_pembiayaan');
            $table->text('detail');
            $table->date('tempo');
            $table->text('status');
            $table->string('status_angsuran');
            $table->string('angsuran_ke');
            $table->timestamps();
            $table->foreign('id_rekening')
                ->references('id')
                ->on('rekening')
                ->onDelete('cascade');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('id_pengajuan')
                ->references('id')
                ->on('pengajuan')
                ->onDelete('cascade');
        });

        Schema::create('bmt', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_bmt')->unique();
            $table->unsignedInteger('id_rekening');
            $table->string('nama');
            $table->string('saldo');
            $table->text('detail');
            $table->timestamps();
            $table->foreign('id_rekening')
                ->references('id')
                ->on('rekening')
                ->onDelete('cascade');
        });

        Schema::create('maal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_maal')->unique();
            $table->unsignedInteger('id_rekening');
            $table->string('nama_kegiatan');
            $table->date('tanggal_pelaksanaan');
            $table->string('status');
            $table->text('detail');
            $table->timestamps();
            $table->foreign('id_rekening')
                ->references('id')
                ->on('rekening')
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabungan');
        Schema::dropIfExists('deposito');
        Schema::dropIfExists('pembiayaan');
        Schema::dropIfExists('bmt');
        Schema::dropIfExists('maal');
        Schema::dropIfExists('pengajuan');
        Schema::dropIfExists('rekening');
    }
}
