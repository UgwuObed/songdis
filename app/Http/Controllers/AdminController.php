<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicUpload;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{

    public function fetchAllSingles(Request $request)
    {
        try {
            if ($request->user()->account_type !== 'admin') {
                return response()->json([
                    'message' => 'Unauthorized access.',
                ], 403);
            }
    
            $singles = MusicUpload::where('upload_type', 'Single')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($single) {
                    return [
                        'id' => $single->id,
                        'track_title' => $single->track_title,
                        'primary_artist' => $single->primary_artist,
                        'featured_artists' => $single->featured_artists,
                        'producers' => $single->producers,
                        'audio_file_path' => $single->audio_file_path,
                        'primary_genre' => $single->primary_genre,
                        'secondary_genre' => $single->secondary_genre,
                        'release_date' => $single->release_date,
                        'album_art_url' => $single->album_art_url,
                        'platforms' => is_string($single->platforms) ? $single->platforms : json_encode($single->platforms),
                        'explicit_content' => $single->explicit_content,
                        'genres_moods' => is_string($single->genres_moods) ? $single->genres_moods : json_encode($single->genres_moods),
                        'credits' => $single->credits,
                        'lyrics' => $single->lyrics,
                        'songwriter_splits' => $single->songwriter_splits,
                        'pre_order_date' => $single->pre_order_date,
                        'upc_code' => $single->upc_code,
                        'isrc_code' => $single->isrc_code,
                        'upload_type' => $single->upload_type,
                        'user_id' => $single->user_id
                    ];
                });
    
            if ($singles->isEmpty()) {
                return response()->json([
                    'message' => 'No singles found in the database.',
                    'data' => [],
                ], 404);
            }
    
            return response()->json([
                'message' => 'Singles fetched successfully!',
                'data' => $singles,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('An error occurred while fetching singles', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching singles.'], 500);
        }
    }

    public function fetchAllAlbums(Request $request)
    {
        try {
            if ($request->user()->account_type !== 'admin') {
                return response()->json([
                    'message' => 'Unauthorized access.',
                ], 403);
            }
    
            $albums = MusicUpload::where('upload_type', 'Album/EP')
                ->select('id', 'release_title', 'release_date', 'album_art_url', 'primary_artist', 'primary_genre', 'user_id')
                ->distinct()
                ->get();
    
            if ($albums->isEmpty()) {
                return response()->json([
                    'message' => 'No albums/EPs found in the database.',
                    'data' => [],
                ], 404);
            }
    
            $albumsWithTracks = $albums->map(function ($album) {
                $tracks = MusicUpload::where('release_title', $album->release_title)
                    ->get(['id', 'track_title', 'primary_artist', 'featured_artists', 'producers', 'audio_file_path', 'primary_genre', 'secondary_genre', 'release_date', 'album_art_url', 'platforms', 'explicit_content', 'genres_moods', 'credits', 'lyrics', 'songwriter_splits', 'genres_moods', 'pre_order_date', 'upc_code', 'isrc_code', 'upload_type', 'user_id']);
    
                return [
                    'id' => $album->id, 
                    'release_title' => $album->release_title,
                    'release_date' => $album->release_date,
                    'album_art_url' => $album->album_art_url,
                    'primary_artist' => $album->primary_artist,
                    'primary_genre' => $album->primary_genre,
                    'tracks' => $tracks,
                    'user' => $album->user,
                ];
            });
    
            return response()->json([
                'message' => 'Albums/EPs fetched successfully!',
                'data' => $albumsWithTracks,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('An error occurred while fetching albums', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while fetching albums.'], 500);
        }
    }
    


 public function fetchSingleById($id, Request $request)
 {
     try {
         // Check for admin user
         if ($request->user()->account_type !== 'admin') {
             return response()->json([
                 'message' => 'Unauthorized access.',
             ], 403);
         }

         $single = MusicUpload::find($id);
         if (!$single) {
             return response()->json([
                 'message' => 'Single not found.',
             ], 404);
         }

         return response()->json([
             'message' => 'Single fetched successfully!',
             'data' => $single,
         ]);
     } catch (\Exception $e) {
         return response()->json([
             'message' => 'An error occurred: ' . $e->getMessage(),
         ], 500);
     }
 }

 public function fetchAlbumById($id, Request $request)
 {
     try {
         if ($request->user()->account_type !== 'admin') {
             return response()->json([
                 'message' => 'Unauthorized access.',
             ], 403);
         }

         $album = MusicUpload::find($id);
         if (!$album) {
             return response()->json([
                 'message' => 'Album not found.',
             ], 404);
         }

         return response()->json([
             'message' => 'Album fetched successfully!',
             'data' => $album,
         ]);
     } catch (\Exception $e) {
         return response()->json([
             'message' => 'An error occurred: ' . $e->getMessage(),
         ], 500);
     }
 }

}
