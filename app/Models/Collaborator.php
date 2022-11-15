<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory;

    protected $table = 'collaborators';

    protected $primaryKey = 'id';

    protected $fillable = ['email', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
