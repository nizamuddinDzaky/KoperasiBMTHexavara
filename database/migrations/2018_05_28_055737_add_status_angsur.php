<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAngsur extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembiayaan', function($table) {
            $table->string('status_angsuran');
            $table->string('angsuran_ke');
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
        Schema::table('pembiayaan', function($table) {
            $table->dropColumn('status_angsuran');
            $table->dropColumn('angsuran_ke');
        });
        //
    }
}
