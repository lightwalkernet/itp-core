<?php

namespace Itp\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Itp\Core\Database\Factories\UniversalItpFactory;
use Itp\Core\Enums\UniversalItpKind;
use Itp\Core\Models\Concerns\HasItpContent;
use Lightwalker\Sync\Concerns\Syncable;

/**
 * An ITP shared with every user, edited in Studio.
 *
 * @property-read string $display_name
 */
#[Fillable(['slug', 'kind', 'sort_order', 'short_name', 'is_preview'])]
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
            'is_preview' => 'boolean',
        ];
    }

    /**
     * The name the app shows: the short name when set, otherwise the title.
     *
     * @return Attribute<string, never>
     */
    protected function displayName(): Attribute
    {
        return Attribute::get(fn (): string => filled($this->short_name) ? $this->short_name : $this->title);
    }

    /**
     * Only the ITPs playable before the user has signed in.
     *
     * @param  Builder<static>  $query
     */
    #[Scope]
    protected function preview(Builder $query): void
    {
        $query->where('is_preview', true);
    }
}
