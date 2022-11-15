<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = ['name', 'description', 'type', 'tags', 'category', 'status', 'is_private', 'visibility', 'release_date',
        'available_from', 'available_to', 'amount', 'currency', 'has_discount'];
    protected $searchable = ['name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function discounts()
    {
        return $this->hasMany(ProjectDiscount::class);
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }
    public function file()
    {
        return $this->hasOne(ProjectFile::class);
    }

    public function collaborators()
    {
        return $this->hasMany(Collaborator::class);
    }

    public function tickets()
    {
        return $this->hasMany(ProjectTicket::class);
    }

    public function featured()
    {
        return $this->hasMany(Featured::class);
    }

    protected $casts = ['tags' => 'array'];
}
