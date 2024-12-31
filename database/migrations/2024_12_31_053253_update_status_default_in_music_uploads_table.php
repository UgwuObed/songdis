<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusDefaultInMusicUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('music_uploads')
            ->where('status', 'pending')
            ->update(['status' => 'Pending']);

       
        Schema::table('music_uploads', function (Blueprint $table) {
            $table->string('status')->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('music_uploads')
            ->where('status', 'Pending')
            ->update(['status' => 'pending']);

        Schema::table('music_uploads', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }
}