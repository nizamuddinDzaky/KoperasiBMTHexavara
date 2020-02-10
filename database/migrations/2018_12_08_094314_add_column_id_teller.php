<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIdTeller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maal', function($table) {
            $table->string('teller');
        });
        Schema::table('pengajuan', function($table) {
            $table->string('teller');
        });
        Schema::table('penyimpanan_bmt', function($table) {
            $table->string('teller');
        });
        Schema::table('penyimpanan_deposito', function($table) {
            $table->string('teller');
        });
        Schema::table('penyimpanan_maal', function($table) {
            $table->string('teller');
        });
        Schema::table('penyimpanan_pembiayaan', function($table) {
            $table->string('teller');
        });
        Schema::table('penyimpanan_tabungan', function($table) {
            $table->string('teller');
        });
        Schema::table('penyimpanan_wajib_pokok', function($table) {
            $table->string('teller');
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
        Schema::table('maal', function($table) {
            $table->dropColumn('teller');
        });
        Schema::table('pengajuan', function($table) {
            $table->dropColumn('teller');
        });
        Schema::table('penyimpanan_bmt', function($table) {
            $table->dropColumn('teller');
        });
        Schema::table('penyimpanan_deposito', function($table) {
            $table->dropColumn('teller');
        });
        Schema::table('penyimpanan_maal', function($table) {
            $table->dropColumn('teller');
        });
        Schema::table('penyimpanan_pembiayaan', function($table) {
            $table->dropColumn('teller');
        });
        Schema::table('penyimpanan_tabungan', function($table) {
            $table->dropColumn('teller');
        });
        Schema::table('penyimpanan_wajib_pokok', function($table) {
            $table->dropColumn('teller');
        });
        //
    }
}
