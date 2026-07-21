<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PlatformUserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['username', 'email', 'email_verified_at', 'password'])]
#[Hidden(['password', 'remember_token'])]
#[UseFactory(PlatformUserFactory::class)]
final class PlatformUser extends Authenticatable implements FilamentUser, HasName, MustVerifyEmail
{
    /** @use HasFactory<PlatformUserFactory> */
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return 'platform' === $panel->getId();
    }

    public function getFilamentName(): string
    {
        return $this->username;
    }

    /**
     * @return Attribute<string, string>
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn(string $value): string => Str::lower(mb_trim($value)),
        );
    }
}
