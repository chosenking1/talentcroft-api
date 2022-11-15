<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';

    protected $fillable = ['name', 'description', 'type', 'tags', 'status', 'visibility', 'release_date',
        'available_from', 'available_to', 'amount', 'currency', 'has_discount'];
    protected $searchable = ['name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->hasMany(MovieFile::class);
    }


    protected $casts = ['tags' => 'array'];
}
