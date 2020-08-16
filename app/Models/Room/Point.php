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
     * Получение списка точекЮ проставленных за последние 24 часа
     *
     * @param Room $room
     * @return Collection
     */
    public static function getForRoom(Room $room): Collection
    {
        return static::filterByRoom($room)
            ->notExpired()
            ->get();
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
        return static::where('room_uuid', $room->uuid)
            ->updateOrCreate([
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
        $builder->where('room_uuid', $room->uuid);
    }

    /**
     * Фильтрация точек по дате обновления за последниу 24 часа
     * @param Builder $builder
     */
    public function scopeNotExpired(Builder $builder): void
    {
        $builder->where('updated_at', '>', now()->subDay())
            ->latest('updated_at');
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
