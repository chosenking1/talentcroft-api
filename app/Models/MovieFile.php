<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieFile extends Model
{
    use HasFactory;

    protected $table = 'movie_files';

    protected $primaryKey = 'id';

    protected $fillable = ['url', 'thumbnail', 'type', 'size',];

    public function project()
    {
        return $this->belongsTo(Movie::class);
    }
}
