<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advisory extends Model
{
    protected $fillable = [
        'headline',
        'description',
        'content',
        'attachment',
        'is_latest',
        'is_archive',
        'created_by'
    ];
    public function user()
{
    return $this->belongsTo(User::class, 'created_by');
}
}
