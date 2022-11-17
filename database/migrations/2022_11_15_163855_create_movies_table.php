<?php

use App\Models\User;
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
        Schema::create('movies', function (Blueprint $table) {
            $table->id()->from(time());
            $table->integer('user_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('type')->default('movie');
            $table->string('status')->default('draft');
            $table->string('visibility')->default('private');
            $table->timestamp('release_date')->nullable();
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_to')->nullable();
            $table->float('amount', 20)->default(0);
            $table->string('currency', 3)->default('NGN');
            $table->boolean('has_discount')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
};
