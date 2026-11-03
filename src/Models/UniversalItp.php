<?php

namespace Itp\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Itp\Core\Database\Factories\UniversalItpFactory;
use Itp\Core\Enums\UniversalItpKind;
use Itp\Core\Models\Concerns\HasItpContent;
use Lightwalker\Sync\Concerns\Syncable;

/**
 * An ITP shared with every user, edited in Studio.
 */
#[Fillable(['slug', 'kind', 'sort_order'])]
#[UseFactory(UniversalItpFactory::class)]
class UniversalItp extends Model
{
    /** @use HasFactory<UniversalItpFactory> */
    use HasFactory, HasItpContent, Syncable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => UniversalItpKind::class,
            'sort_order' => 'integer',
        ];
    }
}
