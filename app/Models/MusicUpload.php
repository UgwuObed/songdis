<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'track_title', 'primary_artist', 'featured_artists',
        'producers', 'explicit_content', 'audio_file_path', 'release_title',
        'primary_genre', 'secondary_genre', 'release_date', 'album_art_path',
        'platforms', 'lyrics', 'songwriter_splits', 'credits', 'genres_moods',
        'pre_order_date', 'upc_code', 'upload_type'
    ];

    protected $casts = [
        'platforms' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}