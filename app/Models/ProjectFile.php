<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    use HasFactory;

    protected $table = 'project_files';

    protected $primaryKey = 'id';

    protected $fillable = ['project_id', 'name', 'url', 'thumbnail', 'type', 'size', 'preview'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
