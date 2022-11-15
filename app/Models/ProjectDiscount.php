<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDiscount extends Model
{
    use HasFactory;

    protected $table = 'project_discounts';

    protected $primaryKey = 'id';

    protected $fillable = ['project_id', 'target', 'value', 'start_date', 'end_date'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
