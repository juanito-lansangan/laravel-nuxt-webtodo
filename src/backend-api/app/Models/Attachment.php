<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    /** @use HasFactory<\Database\Factories\AttachmentFactory> */
    use HasFactory;

    protected $fillable = ['path', 'type'];

    protected $appends = ['file_url'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::url($this->path),
        );
    }
}
