<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('music_uploads', function (Blueprint $table) {
            $table->enum('upload_type', ['Single', 'Album/EP'])->default('Single')->after('release_title');

            $table->string('upc_code')->nullable()->after('upload_type');
            
            $table->json('audio_files')->nullable()->after('explicit_content');
            
            $table->dropColumn('audio_file_path');
        });
    }
    
    public function down()
    {
        Schema::table('music_uploads', function (Blueprint $table) {
            $table->string('audio_file_path')->nullable();

            $table->dropColumn('audio_files');
            $table->dropColumn('upload_type');
            $table->dropColumn('upc_code');
        });
    }
};
