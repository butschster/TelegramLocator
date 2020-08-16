<?php

namespace App\Models;

use App\Infrastructure\Telegram\User as TelegramUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    /**
     * Получение объекта пользователя через объект пользователя телеграма
     * @param TelegramUser $user
     * @return User|null
     */
    public static function findByTelegramUser(TelegramUser $user): ?self
    {
        return static::find($user->getHash());
    }

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'id'
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
