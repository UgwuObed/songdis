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
    DB::statement('ALTER TABLE music_uploads ALTER COLUMN audio_file_path TYPE json USING audio_file_path::json');
    
    Schema::table('music_uploads', function (Blueprint $table) {
        $table->json('audio_file_path')->nullable(false)->change();
    });
}

}
