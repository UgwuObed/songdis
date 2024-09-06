<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ContactsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 1000) as $index) {
            $isNigerian = rand(0, 1) === 1;

            DB::table('contacts')->insert([
                'phone_number' => $isNigerian 
                    ? $faker->unique()->numerify('080########') 
                    : $faker->unique()->phoneNumber,  
                'message' => $faker->realText(50), 
            ]);
        }
    }
}
