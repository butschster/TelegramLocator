<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use App\Models\Room\Point;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    use UsesUuid;

    protected $guarded = ['uuid'];

    public function points(): HasMany
    {
        return $this->hasMany(Point::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasPassword(): bool
    {
        return !empty($this->attributes['password']);
    }

    public function hasAccess(string $userHash): bool
    {
        if (!$this->hasPassword()) {
            return true;
        }

        if ($this->user_id === $userHash) {
            return true;
        }

        return DB::table('room_users')
            ->where('room_uuid', $this->uuid)
            ->where('user_hash', $userHash)
            ->exists();
    }

    public function addUser(string $userHash): void
    {
        DB::table('room_users')
            ->insert([
                'room_uuid' => $this->uuid,
                'user_hash' => $userHash
            ]);
    }
}
