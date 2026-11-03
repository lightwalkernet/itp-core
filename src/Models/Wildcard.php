<?php

namespace Itp\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Itp\Core\Database\Factories\WildcardFactory;
use Lightwalker\Sync\Concerns\Syncable;

/**
 * A "Why …?" shared with every user.
 */
#[Fillable(['why'])]
#[UseFactory(WildcardFactory::class)]
class Wildcard extends Model
{
    /** @use HasFactory<WildcardFactory> */
    use HasFactory, Syncable;
}
