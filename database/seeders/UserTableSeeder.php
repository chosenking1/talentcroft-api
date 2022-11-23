<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// use App\Models\Movie;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        User::factory()->count(5)->create();
        User::factory()->create([
            "first_name" =>"Jboss",
            "last_name" => "string",
            "email" => "stringing@mia.com",
            "phone_number" => "095854484883",
            "password" =>Hash::make('string1')
        ]);

    }
}
