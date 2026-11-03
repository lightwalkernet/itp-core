<?php

namespace Itp\Core\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Itp\Core\Schema\ItpContent;

/**
 * Fillable content columns and array casts for a model whose table uses `ItpContent::columns()`.
 *
 * @mixin Model
 */
trait HasItpContent
{
    public function initializeHasItpContent(): void
    {
        $this->mergeFillable(ItpContent::COLUMNS);

        $this->mergeCasts(array_fill_keys(ItpContent::JSON_COLUMNS, 'array'));
    }
}
