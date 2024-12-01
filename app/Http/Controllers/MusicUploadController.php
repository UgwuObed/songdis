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
            \Log::info('Incoming request data', [
                'request' => $request->all(),
                'files' => $request->allFiles(),
            ]);
    
            $validatedData = $request->validate([
                'track_title' => 'nullable|string|max:255',
                'upload_type' => 'required|string|in:Single,Album/EP,EP',
                'primary_artist' => 'required|string|max:255',
                'primary_genre' => 'required|string|max:255',
                'explicit_content' => 'required|in:1,0',
                'platforms' => 'required|array|min:1',
                'album_art' => 'required|file|mimes:jpeg,jpg,png|max:5120',
                'release_date' => 'nullable|date|after_or_equal:today',
                'pre_order_date' => 'nullable|date|before_or_equal:release_date',
                'upc_code' => 'nullable|string|max:12',
                'release_title' => $request->upload_type !== 'Single' ? 'nullable|string|max:255' : 'nullable',
                'songwriter_splits' => 'nullable|string|max:500',
                'credits' => 'nullable|string|max:1000',
                'genres_moods' => 'nullable|string|max:500',
            ]);
    
            if (in_array($validatedData['upload_type'], ['Album/EP', 'ep'])) {
                // validation for tracks
                $request->validate([
                    'tracks' => 'required|array|min:1',
                    'tracks.*.track_title' => 'required|string|max:255',
                    'tracks.*.audio_file' => 'required|file|max:10240',
                    'tracks.*.featured_artists' => 'nullable|string|max:500',
                    'tracks.*.producers' => 'nullable|string|max:500',
                    'tracks.*.lyrics' => 'nullable|string',
                ]);
            } else {
                // Validation for single track upload
                $request->validate([
                    // 'track_title' => 'required|string|max:255',
                    // 'audio_file' => 'required|file|max:10240',
                    'lyrics' => 'nullable|string',
                    'featured_artists' => 'nullable|string|max:500',
                    'producers' => 'nullable|string|max:500',
                ]);
            }
    
            \Log::info('Validation passed for music upload');
    
            // Store album art
            $albumArtPath = $request->file('album_art')->store('uploads/art', 'public');
    
            $user = $request->user();
            $musicUploads = [];
    
            if (in_array($validatedData['upload_type'], ['Album/EP', 'ep'])) {
                // Process tracks for album/EP
                foreach ($request->tracks as $track) {
                    $audioFilePath = $track['audio_file']->store('uploads/audio', 'public');
    
                    $musicUploads[] = MusicUpload::create([
                        'user_id' => $user->id,
                        'track_title' => $track['track_title'],
                        'primary_artist' => $validatedData['primary_artist'],
                        'featured_artists' => $track['featured_artists'] ?? null,
                        'producers' => $track['producers'] ?? null,
                        'explicit_content' => $validatedData['explicit_content'],
                        'audio_file_path' => $audioFilePath,
                        'release_title' => $validatedData['release_title'] ?? null,
                        'primary_genre' => $validatedData['primary_genre'],
                        'secondary_genre' => $request->get('secondary_genre'),
                        'release_date' => $validatedData['release_date'] ?? null,
                        'album_art_path' => $albumArtPath,
                        'platforms' => json_encode($validatedData['platforms']),
                        'lyrics' => $track['lyrics'] ?? null,
                        'songwriter_splits' => $request->get('songwriter_splits'),
                        'credits' => $request->get('credits'),
                        'genres_moods' => $request->get('genres_moods'),
                        'pre_order_date' => $validatedData['pre_order_date'] ?? null,
                        'upc_code' => $validatedData['upc_code'] ?? null,
                        'upload_type' => $validatedData['upload_type'],
                    ]);
                }
            } else {

                if (!$request->hasFile('audio_file')) {
                    return response()->json(['error' => 'Audio file is required.'], 400);
                }

                \Log::info('Request data', ['files' => $request->allFiles(), 'data' => $request->all()]);

                // Process single track upload
                $audioFilePath = $request->file('audio_file')->store('uploads/audio', 'public');
    
                \Log::info('Uploaded Audio File', ['audio_file' => $request->file('audio_file')]);


                $musicUploads[] = MusicUpload::create([
                    'user_id' => $user->id,
                    'track_title' => $validatedData['track_title'],
                    'primary_artist' => $validatedData['primary_artist'],
                    'featured_artists' => $request->get('featured_artists'),
                    'producers' => $request->get('producers'),
                    'explicit_content' => $validatedData['explicit_content'],
                    'audio_file_path' => $audioFilePath,
                    'release_title' => $validatedData['release_title'] ?? null,
                    'primary_genre' => $validatedData['primary_genre'],
                    'secondary_genre' => $request->get('secondary_genre'),
                    'release_date' => $validatedData['release_date'] ?? null,
                    'album_art_path' => $albumArtPath,
                    'platforms' => json_encode($validatedData['platforms']),
                    'lyrics' => $request->get('lyrics'),
                    'songwriter_splits' => $request->get('songwriter_splits'),
                    'credits' => $request->get('credits'),
                    'genres_moods' => $request->get('genres_moods'),
                    'pre_order_date' => $validatedData['pre_order_date'] ?? null,
                    'upc_code' => $validatedData['upc_code'] ?? null,
                    'upload_type' => $validatedData['upload_type'],
                ]);
            }

    
            \Log::info('Music uploaded successfully', ['music_uploads' => $musicUploads]);
    
            return response()->json([
                'message' => 'Music uploaded successfully!',
                'data' => $musicUploads,
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error occurred', ['errors' => $e->errors()]);
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('An unexpected error occurred', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An unexpected error occurred. Please try again.'], 500);
        }
    }
    

    public function index(Request $request)
    {
        try {
            $query = MusicUpload::where('user_id', $request->user()->id);
    
            if ($request->has('filter')) {
                if ($request->filter === 'album_ep') {
                    $query->whereIn('upload_type', ['Album/EP', 'EP']);
                } elseif ($request->filter === 'single') {
                    $query->where('upload_type', 'Single');
                }
            }

            $count = $query->count();
    
            $uploads = $query->orderBy('created_at', 'desc')->paginate();
    
            return response()->json([
                'message' => 'Music uploads fetched successfully!',
                'count' => $count,
                'data' => $uploads, 
            ], 200);
        } catch (\Exception $e) {
            \Log::error('An error occurred while fetching music uploads', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching music uploads.'], 500);
        }
    }

 public function fetchAlbumWithTracks(Request $request, $releaseTitle)
    {
    try {
        $user = $request->user();

        $albumWithTracks = MusicUpload::where('user_id', $user->id)
            ->where('upload_type', 'Album/EP') 
            ->where('release_title', $releaseTitle)
            ->get()
            ->groupBy('release_title');

        if ($albumWithTracks->isEmpty()) {
            return response()->json([
                'message' => 'No album/EP found for the given release title.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Album/EP fetched successfully!',
            'data' => $albumWithTracks
        ], 200);
    } catch (\Exception $e) {
        \Log::error('An error occurred while fetching album with tracks', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'An error occurred while fetching the album.'], 500);
    }
}

public function fetchAllAlbumsWithTracks(Request $request)
{
    try {
        $user = $request->user();

        $albums = MusicUpload::where('user_id', $user->id)
            ->where('upload_type', 'Album/EP')
            ->select('release_title', 'release_date', 'album_art_path', 'primary_artist', 'primary_genre')
            ->distinct() 
            ->get();

        if ($albums->isEmpty()) {
            return response()->json([
                'message' => 'No albums/EPs found for this user.',
                'data' => []
            ], 404);
        }

        $albumsWithTracks = $albums->map(function ($album) use ($user) {
            $tracks = MusicUpload::where('user_id', $user->id)
                ->where('release_title', $album->release_title)
                ->get(['id', 'track_title', 'audio_file_path', 'featured_artists', 'producers', 'lyrics', 'explicit_content']);

            return [
                'release_title' => $album->release_title,
                'release_date' => $album->release_date,
                'album_art_path' => $album->album_art_path,
                'primary_artist' => $album->primary_artist,
                'primary_genre' => $album->primary_genre,
                'tracks' => $tracks,
            ];
        });

        return response()->json([
            'message' => 'Albums/EPs fetched successfully!',
            'data' => $albumsWithTracks,
        ], 200);
    } catch (\Exception $e) {
        \Log::error('An error occurred while fetching albums with tracks', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'An error occurred while fetching albums.'], 500);
    }
}

    
}    