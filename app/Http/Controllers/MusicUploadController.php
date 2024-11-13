<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\MusicUpload;

class MusicUploadController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'track_title' => 'required|string',
                'primary_artist' => 'required|string',
                'audio_file' => 'required|file|max:10240',
                'release_title' => 'required|string',
                'primary_genre' => 'required|string',
                'explicit_content' => 'required|in:1,0',
                'platforms' => 'required|array|min:1',
                'album_art' => 'required|file|mimes:jpeg,jpg,png', 
            ]);
            \Log::info('Data validated successfully', $validatedData);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['errors' => $e->errors()], 422);
        }

        $user = $request->user();
   
        // Handle file uploads to cloud
        $audioFilePath = $request->file('audio_file')->store('uploads/audio', 'public'); 
        $albumArtPath = $request->hasFile('album_art') 
            ? $request->file('album_art')->store('uploads/art', 'public') 
            : null;
    
        $musicUpload = MusicUpload::create([
            'user_id' => $user->id,
            'track_title' => $request->track_title,
            'primary_artist' => $request->primary_artist,
            'featured_artists' => $request->featured_artists,
            'producers' => $request->producers,
            'explicit_content' => $request->explicit_content,
            'audio_file_path' => $audioFilePath,
            'release_title' => $request->release_title,
            'primary_genre' => $request->primary_genre,
            'secondary_genre' => $request->secondary_genre,
            'release_date' => $request->release_date,
            'album_art_path' => $albumArtPath,
            'platforms' => json_encode($request->platforms),
            'lyrics' => $request->lyrics,
            'songwriter_splits' => $request->songwriter_splits,
            'credits' => $request->credits,
            'genres_moods' => $request->genres_moods,
            'pre_order_date' => $request->pre_order_date,
        ]);
    

        return response()->json(['message' => 'Music uploaded successfully!', 'data' => $musicUpload], 201);
    }
    
}
