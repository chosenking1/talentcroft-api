<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Featured extends Model
{
    protected $fillable = ['project_id', 'expired_at'];
    protected $casts = ['expired_at' => 'datetime'];
    protected $hidden = ['id', 'update_at', 'created_at'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param $query
     * @param mixed|null $asAt
     * @return mixed
     */
    public function scopeActive($query, mixed $asAt = null)
    {
        $value = $asAt ?: now();
        return $query->whereDate('expired_at', '>=', $value);
    }

    /**
     * @param $query
     * @param null $asAt
     * @return mixed
     */
    public function scopeExpired($query, mixed $asAt = null): mixed
    {
        $value = $asAt ?: now();
        return $query->whereDate('expired_at', '<=', $value);
    }
}
