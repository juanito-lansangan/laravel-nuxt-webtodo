<?php

namespace App\Models;

use App\Enums\TaskPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'archived_at',
        'completed_at',
    ];

    public function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'due_date' => 'datetime',
            'archived_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function scopeFilter(Builder $query, $request, $search, $dateFilters)
    {
        return $query->where(function ($query) use ($search) {
            $query
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })
        ->when($request->priority, function ($query, $filter) {
            $query->where('priority', $filter);
        })
        ->when($request->date_filter && in_array($request->date_filter, $dateFilters) && $request->date_from && $request->date_to,
            function ($query) use ($request) {
                $query
                    ->whereDate($request->date_filter, '>=', $request->date_from)
                    ->whereDate($request->date_filter, '<=', $request->date_to);
            }
        );
    }

    public function scopeSort(Builder $query, $request, $sortItems)
    {
        return $query->when($request->sort_by && in_array($request->sort_by, array_keys($sortItems)),
            function ($query) use ($request) {
                $query->orderBy($request->sort_by, $request->sort_order ?? 'ASC');
            }
        );
    }
}
