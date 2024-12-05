<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. In this case, we're setting the default to 's3'.
    |
    */

    'default' => env('FILESYSTEM_DISK', 's3'), 

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_REGION'), 
            'bucket' => env('AWS_BUCKET'),
            'use_path_style_endpoint' => true,
            'visibility' => 'public',
        ],


        'cloudinary' => [
            'driver' => 'cloudinary',
            'url' => env('CLOUDINARY_URL'),
        ],

        'tigris' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'endpoint' => env('AWS_ENDPOINT_URL_S3'),
            'region' => env('AWS_REGION', 'auto'),
            'bucket' => env('AWS_BUCKET', 'songdis'), 
            'url' => env('AWS_ENDPOINT_URL_S3'),
        ],

        'b2' => [
                'driver'     => 's3',
                'key'         => env('B2_KEY_ID'),      
                'secret'      => env('B2_APPLICATION_KEY'), 
                'bucket'      => env('B2_BUCKET_NAME'),   
                'endpoint'    => env('B2_ENDPOINT'), 
                'region'      => env('B2_REGION'), 
                'url'         => env('B2_URL'), 
            ],


    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Configure symbolic links created by the `storage:link` Artisan command.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
