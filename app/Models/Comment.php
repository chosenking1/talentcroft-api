<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = ['comment', 'project_id', 'user_id'];

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id','id');
    }

    /**
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id','id');
    }

    /**
     * @return HasMany
     */
    public function sentiments(): HasMany
    {
        return $this->hasMany(CommentSentiment::class);
    }
    /**
     * @return Attribute
     */
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
}
