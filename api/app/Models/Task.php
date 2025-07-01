<?php

namespace App\Models;

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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
