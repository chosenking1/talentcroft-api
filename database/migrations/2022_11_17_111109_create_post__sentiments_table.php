<?php

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post__sentiments', function (Blueprint $table) {
            $table->id()->from(time());
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Post::class);
            $table->enum('sentiment', ['liked', 'disliked']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post__sentiments');
    }
};
