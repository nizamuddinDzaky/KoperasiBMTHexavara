<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPenyimpananRekening extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('penyimpanan_rekening');
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
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penyimpanan_rekening');
        //
    }
}
