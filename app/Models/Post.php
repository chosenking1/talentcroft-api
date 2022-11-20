<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->user_id = auth()->id();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sentiments()
    {
        return $this->hasMany(Post_Sentiment::class);
    }

    public function getSentimentAttribute()
    {
        $sentiment = $this->sentiments()->where('user_id', auth()->id())->first();
        return  $sentiment ? $sentiment->sentiment : '';
    }

    public function getLikesAttribute()
    {
        return $this->sentiments()->likes()->count();
    }

    public function getDislikesAttribute()
    {
        return $this->sentiments()->dislikes()->count();
    }
}
