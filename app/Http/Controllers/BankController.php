<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\MusicUpload;
use App\Models\EmailTemplate;
use App\Services\ZeptoMailService;
use App\Services\EmailService;
use App\Mail\UploadEmail;
use Illuminate\Support\Facades\Mail;

class MusicUploadController extends Controller
{

    protected $zeptoMailService;

    public function __construct(ZeptoMailService $zeptoMailService)
    {
        $this->zeptoMailService = $zeptoMailService;
    }
    
    public function store(Request $request)
    {
        try {
            \Log::info('Incoming request data', [
                'request' => $request->all(),
            ]);
    
            $validatedData = $request->validate([
                'bank_name' => 'nullable|string|max:255',
                'account_number' => 'required|string|max:255',
                'account_name' => 'required|string|max:255',
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
    
            $template = EmailTemplate::where('name', 'music_upload_success')->first();
            if ($template) {
                $content = str_replace(
                    ['{{primary_artist}}', '{{release_date}}'],
                    [$validatedData['primary_artist'], $validatedData['release_date'] ?? 'N/A'],
                    $template->content
                );

                $this->zeptoMailService->sendEmail(
                    $user->email,
                    $user->first_name,
                    $template->subject,
                    $content
                );
            } else {
                Log::warning('Music upload email template not found.');
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

}