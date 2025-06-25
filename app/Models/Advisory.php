<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
    
    protected $appends = ['attachment_url'];

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }
}
