<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomepageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepage', function (Blueprint $table) {
            $table->increments('id');
            $table->text('gambar')->nullable();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('moto')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('gambar_moto')->nullable();
            $table->text('gambar_visi')->nullable();
            $table->text('gambar_misi')->nullable();
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
        Schema::dropIfExists('homepage');
    }
}
