<?php

namespace App\Models;

use App\Enums\PriorityTaskEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task query()
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'active',
        'deadline',
        'priority',
    ];

    protected $attributes = [
        'active' => true,
    ];

    protected $casts = [
        'priority' => PriorityTaskEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function syncTags(array $tags)
    {
        if (empty($tags)) return;
        
        $tagIds = collect($tags)
                ->map(fn ($title) => Tag::firstOrCreate(['title' => $title])->id)
                ->toArray();

        $this->tags()->sync($tagIds);
    }
}
