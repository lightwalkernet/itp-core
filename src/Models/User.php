<?php

namespace Itp\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Itp\Core\Database\Factories\UserFactory;
use Laravel\Sanctum\HasApiTokens;
use Lightwalker\Sync\Concerns\Syncable;

/**
 * An app user. Passwordless (email OTP), so there is no password or remember token.
 * Pull-only on the device: profile changes go through the API.
 */
#[Fillable(['email', 'first_name'])]
#[UseFactory(UserFactory::class)]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Syncable;

    /**
     * @return HasMany<Itp, $this>
     */
    public function itps(): HasMany
    {
        return $this->hasMany(Itp::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
