<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDiscountUsage extends Model
{
    use HasFactory;

    protected $table = 'project_discount_usages';

    protected $primaryKey = 'id';

    protected $fillable = ['project_id', 'user_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
