<?php

namespace App\Models;

use App\Infrastructure\Telegram\User as TelegramUser;
use App\Models\Concerns\UsesUuid;
use App\Models\Concerns\WithLocation;
use App\Models\Room\Point;
use App\RoomSignatureManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MStaack\LaravelPostgis\Geometries\Point as GeoPoint;

class Room extends Model
{
    use UsesUuid, WithLocation;

    /**
     * Получение списка публичных ближайших комнат в заданном радиусе
     * @param GeoPoint $point
     * @param int $meters
     * @return Collection
     */
    public static function nearest(GeoPoint $point, int $meters = 1000): Collection
    {
        $points = Point::where('location', 'near', [
            '$geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    $point->getLng(), // longitude
                    $point->getLat(), // latitude
                ],
            ],
            '$maxDistance' => $meters,
        ])->distinct('room_uuid')->get()->map(function (Point $point) {
            return $point->attributes[0] ?? null;
        })->filter();

        if ($points->isEmpty()) {
            return new Collection();
        }

        return static::whereIn('uuid', $points)->public()->get();
    }

    protected $guarded = ['uuid'];

    protected $casts = [
        'points_lifetime' => 'int',
        'jitter' => 0
    ];

    /**
     * Фильтрация только публичных комнат
     * @param Builder $builder
     */
    public function scopePublic(Builder $builder): void
    {
        $builder->where('is_public', true);
    }

    /**
     * Получение даты последней активности в комнате
     * Это может быть дата последней добавленной метки, либо дата последнего обновления информации в комнате
     *
     * @return Carbon|null
     */
    public function lastActivity(): ?Carbon
    {
        $lastPoint = Point::filterByRoom($this)
            ->first();

        if ($lastPoint) {
            return $lastPoint->updated_at;
        }

        return $this->updated_at;
    }

    /**
     * Владелец комнаты
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Проверка на наличие пароля
     * @return bool
     */
    public function hasPassword(): bool
    {
        return !empty($this->attributes['password']);
    }

    /**
     * Проверка являеется ли пользователь владельцем
     * @param TelegramUser $user
     * @return bool
     */
    public function isOwner(TelegramUser $user): bool
    {
        return $this->user_id === $user->getHash();
    }

    /**
     * Проверка на наличие доступа
     * @param TelegramUser $user
     * @return bool
     */
    public function hasAccess(TelegramUser $user): bool
    {
        if (!$this->hasPassword()) {
            return true;
        }

        if ($this->isOwner($user)) {
            return true;
        }

        return DB::table('room_users')
            ->where('room_uuid', $this->uuid)
            ->where('user_hash', $user->getHash())
            ->exists();
    }

    /**
     * Добавление пользователя в комнату.
     *
     * @param TelegramUser $user
     */
    public function addUser(TelegramUser $user): void
    {
        DB::table('room_users')
            ->insert([
                'room_uuid' => $this->uuid,
                'user_hash' => $user->getHash()
            ]);
    }

    public function signature(string $hash): string
    {
        return app(RoomSignatureManager::class)->createSign($this->getKey(), $hash);
    }
}
