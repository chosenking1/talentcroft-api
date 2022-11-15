<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CommentSentiment extends Model
{
    protected $fillable = ['user_id', 'sentiment'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = auth()->id();
        });
    }

    public function scopeLikes($query)
    {
        return $query->where('sentiment', 'liked');
    }

    public function scopeDislikes($query)
    {
        return $query->where('sentiment', 'disliked');
    }

    /**
     */
    public function getIsLikedAttribute()
    {
        return $this->sentiment === 'liked';
    }

    /**
     */
    public function getIsDislikedAttribute()
    {
        return $this->sentiment === 'disliked';
    }
}
