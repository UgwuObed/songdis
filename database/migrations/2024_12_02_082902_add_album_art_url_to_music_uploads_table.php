<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlbumArtUrlToMusicUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('music_uploads', function (Blueprint $table) {
            $table->string('album_art_url')->nullable()->after('album_art_path');
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
            $table->dropColumn('album_art_url');
        });
    }
}
