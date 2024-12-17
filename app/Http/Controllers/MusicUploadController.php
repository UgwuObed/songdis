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
            ]);
    
            $validatedData = $request->validate([
                'track_title' => 'nullable|string|max:255',
                'upload_type' => 'required|string|in:Single,Album/EP,EP',
                'primary_artist' => 'required|string|max:255',
                'primary_genre' => 'required|string|max:255',
                'explicit_content' => 'required|in:1,0',
                'platforms' => 'required|array|min:1',
                'audio_file_path' => $request->upload_type === 'Single' ? 'required|array|min:1' : 'nullable',
                'album_art_url' => 'required|url',
                'release_date' => 'nullable|date|after_or_equal:today',
                'pre_order_date' => 'nullable|date|before_or_equal:release_date',
                'upc_code' => 'nullable|string|max:12',
                'isrc_code' => 'nullable|string|max:12',
                'release_title' => $request->upload_type !== 'Single' ? 'nullable|string|max:255' : 'nullable',
                'songwriter_splits' => 'nullable|string|max:500',
                'credits' => 'nullable|string|max:1000',
                'genres_moods' => 'nullable|string|max:500',
            ]);
    
            if (in_array($validatedData['upload_type'], ['Album/EP', 'ep'])) {
                // Validation for tracks in an Album/EP
                $request->validate([
                    'tracks' => 'required|array|min:1',
                    'tracks.*.track_title' => 'required|string|max:255',
                    'tracks.*.audio_file_path' => 'required|url', 
                    'tracks.*.featured_artists' => 'nullable|string|max:500',
                    'tracks.*.producers' => 'nullable|string|max:500',
                    'tracks.*.lyrics' => 'nullable|string',
                ]);
            } else {
                // Validation for single track upload
                $request->validate([
                    'audio_file_path' => 'required|array|min:1', 
                    'lyrics' => 'nullable|string',
                    'featured_artists' => 'nullable|string|max:500',
                    'producers' => 'nullable|string|max:500',
                ]);
            }

            
            \Log::info('Validation passed for music upload');
    
            $user = $request->user();
            $musicUploads = [];
    
            if (in_array($validatedData['upload_type'], ['Album/EP', 'ep'])) {
                // Process tracks for Album/EP
                foreach ($request->tracks as $track) {
                    $musicUploads[] = MusicUpload::create([
                        'user_id' => $user->id,
                        'track_title' => $track['track_title'],
                        'primary_artist' => $validatedData['primary_artist'],
                        'featured_artists' => $track['featured_artists'] ?? null,
                        'producers' => $track['producers'] ?? null,
                        'explicit_content' => $validatedData['explicit_content'],
                        'audio_file_path' => json_encode([$track['audio_file_path']]),
                        'release_title' => $validatedData['release_title'] ?? null,
                        'primary_genre' => $validatedData['primary_genre'],
                        'secondary_genre' => $request->get('secondary_genre'),
                        'release_date' => $validatedData['release_date'] ?? null,
                        'album_art_url' => $validatedData['album_art_url'], 
                        'platforms' => json_encode($validatedData['platforms']),
                        'lyrics' => $track['lyrics'] ?? null,
                        'songwriter_splits' => $request->get('songwriter_splits'),
                        'credits' => $request->get('credits'),
                        'genres_moods' => $request->get('genres_moods'),
                        'pre_order_date' => $validatedData['pre_order_date'] ?? null,
                        'upc_code' => $validatedData['upc_code'] ?? null,
                        'isrc_code' => $validatedData['isrc_code'] ?? null,
                        'upload_type' => $validatedData['upload_type'],
                    ]);
                }
            } else {
                // Process single track upload
                $musicUploads[] = MusicUpload::create([
                    'user_id' => $user->id,
                    'track_title' => $validatedData['track_title'],
                    'primary_artist' => $validatedData['primary_artist'],
                    'featured_artists' => $request->get('featured_artists'),
                    'producers' => $request->get('producers'),
                    'explicit_content' => $validatedData['explicit_content'],
                    'audio_file_path' => json_encode($validatedData['audio_file_path']),
                    'release_title' => $validatedData['release_title'] ?? null,
                    'primary_genre' => $validatedData['primary_genre'],
                    'secondary_genre' => $request->get('secondary_genre'),
                    'release_date' => $validatedData['release_date'] ?? null,
                    'album_art_url' => $validatedData['album_art_url'], 
                    'platforms' => json_encode($validatedData['platforms']),
                    'lyrics' => $request->get('lyrics'),
                    'songwriter_splits' => $request->get('songwriter_splits'),
                    'credits' => $request->get('credits'),
                    'genres_moods' => $request->get('genres_moods'),
                    'pre_order_date' => $validatedData['pre_order_date'] ?? null,
                    'upc_code' => $validatedData['upc_code'] ?? null,
                    'isrc_code' => $validatedData['isrc_code'] ?? null,
                    'upload_type' => $validatedData['upload_type'],
                ]);
            }
    
            
            return response()->json([
                'success' => true,
                'message' => 'Music uploaded successfully',
                'data' => $musicUploads,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error uploading music', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error uploading music'], 500);
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
            ->select('release_title', 'release_date', 'album_art_url', 'primary_artist', 'primary_genre')
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
                ->get(['id', 'track_title', 'audio_file_path', 'album_art_url', 'featured_artists', 'producers', 'lyrics', 'explicit_content', 'songwriter_splits', 'credits', 'genres_moods', 'pre_order_date', 'upc_code', 'upload_type']);

            return [
                'release_title' => $album->release_title,
                'release_date' => $album->release_date,
                'album_art_path' => $album->album_art_path,
                'album_art_url' => $album->album_art_url,
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