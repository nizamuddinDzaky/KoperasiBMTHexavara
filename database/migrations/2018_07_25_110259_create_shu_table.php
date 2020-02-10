<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shu', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_rekening');
            $table->string('nama_shu');
            $table->string('persentase');
            $table->string('status');
            $table->timestamps();
        });
        Schema::create('penyimpanan_shu', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_shu');
            $table->string('periode');
            $table->text('transaksi');
            $table->timestamps();
            $table->foreign('id_shu')
                ->references('id')
                ->on('shu')
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
        Schema::dropIfExists('shu');
        Schema::dropIfExists('penyimpanan_shu');
    }
}
