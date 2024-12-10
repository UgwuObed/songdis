<?php

namespace Database\Seeders;


use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('plans')->insert([
            ['name' => 'Basic', 'duration' => 'Monthly', 'price' => 1999,
            'features' => json_encode([
                '1 Artist Account',
                'Unlimited Releases',
                'Fast Payments & Easy Withdrawals',
                'Lyrics Distribution',
                'Access to Artist Community Group',
                'Stream Links for Each Release',
                '24/7 Customer Support',
            ]),
        ], 
            ['name' => 'Growth', 'duration' => 'Monthly', 'price' => 9999,
            'features' => json_encode([
                'All Basic Plan Features',
                '3 Artist Accounts',
                'Cover Licensing',
                'Lyrics Syncing',
                'Profile Verification',
                'Visual Consultation',
                'Editorial Playlist Consideration',
            ]),
        ], 
            ['name' => 'Professional', 'duration' => 'Yearly', 'price' => 499999,
            'features' => json_encode([
                'All Features from Basic & Growth Plans',
                'Unlimited Artists & Releases',
                'Co-Management Support',
                'Billboard Chart Registration',
                'Advanced Visual Consultation',
                'Educational Resources',
            ]),
        ], 
        ]);
    }
    
}
