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
        Schema::create('music_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('track_title');
            $table->string('primary_artist');
            $table->string('featured_artists')->nullable();
            $table->string('producers')->nullable();
            $table->boolean('explicit_content');
            $table->string('audio_file_path'); 
            $table->string('release_title');
            $table->string('primary_genre');
            $table->string('secondary_genre')->nullable();
            $table->date('release_date');
            $table->string('album_art_path')->nullable();
            $table->json('platforms'); 
            $table->text('lyrics')->nullable();
            $table->string('songwriter_splits')->nullable();
            $table->text('credits')->nullable();
            $table->string('genres_moods')->nullable();
            $table->date('pre_order_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_uploads');
    }
};
