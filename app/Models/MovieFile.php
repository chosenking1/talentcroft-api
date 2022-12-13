<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieFile extends Model
{
    use HasFactory;

    protected $table = 'movie_files';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $casts = ['thumbnails' => 'array'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
