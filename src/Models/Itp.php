<?php

namespace Itp\Core\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Itp\Core\Database\Factories\ItpFactory;
use Itp\Core\Models\Concerns\HasItpContent;
use Lightwalker\Sync\Concerns\Syncable;

/**
 * A user's finished, playable ITP: attunement plus companions in one row.
 */
#[UseFactory(ItpFactory::class)]
class Itp extends Model
{
    /** @use HasFactory<ItpFactory> */
    use HasFactory, HasItpContent, Syncable;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
