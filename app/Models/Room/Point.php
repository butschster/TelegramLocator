<?php

namespace App\Models\Room;

use App\Infrastructure\Telegram\User;
use App\Models\Room;
use BotMan\BotMan\Messages\Attachments\Location;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Jenssegers\Mongodb\Eloquent\Model;
use MStaack\LaravelPostgis\Geometries\Point as GeoPoint;

class Point extends Model
{
    /**
     * Получение списка точек, проставленных за последние 24 часа
     *
     * @param Room $room
     * @return Collection
     */
    public static function getForRoom(Room $room): Collection
    {
        return static::filterByRoom($room)->get();
    }

    /**
     * Получение точки для пользователя
     *
     * @param Room $room
     * @param User $user
     * @return Point
     */
    public static function findByUser(Room $room, User $user): Point
    {
        return static::filterByRoom($room)
            ->where('owner_hash', $user->getHash())
            ->firstOrFail();
    }

    /**
     * Получение кол-ва точек, проставленных за последние 24 часа
     *
     * @param Room $room
     * @return int
     */
    public static function countForRoom(Room $room): int
    {
        return static::filterByRoom($room)
            ->count();
    }

    /**
     * Добавление или обновление существующей точки для телеграм пользователя
     * @param Room $room
     * @param User $user
     * @param Location $location
     * @return static
     */
    public static function storeForRoom(Room $room, User $user, Location $location): self
    {
        return static::updateOrCreate([
            'room_uuid' => $room->uuid,
            'owner_hash' => $user->getHash(),
        ], [
            'location' => new GeoPoint($location->getLatitude(), $location->getLongitude()),
            'username' => $room->is_anonymous
                ? null
                : $user->getUsername()
        ]);
    }

    protected $collection = 'room_points';
    protected $connection = 'mongodb';
    protected $guarded = [];

    /**
     * Фильтрация точек по комнате
     *
     * @param Builder $builder
     * @param Room $room
     */
    public function scopeFilterByRoom(Builder $builder, Room $room): void
    {
        $builder
            ->where('room_uuid', $room->uuid)
            ->notExpired($room->points_lifetime);
    }

    /**
     * Фильтрация точек по дате обновления
     * @param Builder $builder
     * @param int $lifeTimeHours
     */
    public function scopeNotExpired(Builder $builder, int $lifeTimeHours = 0): void
    {
        if ($lifeTimeHours > 0) {
            $builder->where('updated_at', '>', now()->subHours($lifeTimeHours));
        }

        $builder->latest('updated_at');
    }

    /**
     * Добавление локации
     * @param GeoPoint $point
     */
    public function setLocationAttribute(GeoPoint $point): void
    {
        $this->attributes['location'] = [
            $point->getLat(), $point->getLng()
        ];
    }

    /**
     * Получение локации
     * @param array $data
     * @return GeoPoint
     */
    public function getLocationAttribute(array $data)
    {
        return new GeoPoint(
            $data[0], $data[1]
        );
    }
}
