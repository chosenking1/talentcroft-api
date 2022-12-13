<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieList extends Model
{
    use HasFactory;

    protected $table = 'movie_lists';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = ['content' => 'array'];

}
