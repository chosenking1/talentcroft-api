<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Post::get()->each->delete();
        Post::factory(2)->create();
        // User::factory(2)->hasPosts(2)->create();
        // User::factory(20)->create();
    }
}
