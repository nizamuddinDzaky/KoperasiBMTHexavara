<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tabungan', function($table) {
            $table->string('status');
        });
        Schema::table('deposito', function($table) {
            $table->string('status');
        });
        Schema::table('pembiayaan', function($table) {
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tabungan', function($table) {
            $table->dropColumn('status');
        });
        Schema::table('deposito', function($table) {
            $table->dropColumn('status');
        });
        Schema::table('pembiayaan', function($table) {
            $table->dropColumn('status');
        });
    }
}
