<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = [
            [
                'user_id' => '1668594555',
                'description' => 'This is the descriptionp of the post',
                'url'=>'https://www.talentcroft.com',

            ],
            [
                'user_id' => '1668594556',
                'description' => 'This is the descriptionp of the post for nos 2',
                'url'=>'https://www.rim.ng',

            ]
            ];

             foreach($posts as $key => $value){
                Post::create($value);
             }
    }

   
}
