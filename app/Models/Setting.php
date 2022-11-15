<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $primaryKey = 'id';

    protected $fillable = ['enable_email', 'push_notification', 'in_app_notifications'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
