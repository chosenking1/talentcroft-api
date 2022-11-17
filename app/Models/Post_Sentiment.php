<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_Sentiment extends Model
{
    use HasFactory;

    protected $table = 'post__sentiments';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Movie::class);
    }
}
