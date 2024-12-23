<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToMusicUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('music_uploads', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('upload_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('music_uploads', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}

