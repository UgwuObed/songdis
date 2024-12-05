<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAudioFilePathToJsonInMusicUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('music_uploads', function (Blueprint $table) {
            $table->json('audio_file_path')->change();
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
            $table->string('audio_file_path')->change();
        });
    }
}
