<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Room
 *
 * @property string $uuid
 * @property string|null $title
 * @property string|null $description
 * @property string|null $location
 * @property string $telegram_token
 * @property string|null $password
 * @property bool $is_public
 * @property bool $is_anonymous
 * @property string $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $lat
 * @property-read float $lon
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Room inRadius(\MStaack\LaravelPostgis\Geometries\Point $location, $radius)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|Room newModelQuery()
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Room orderByNearest(\MStaack\LaravelPostgis\Geometries\Point $point)
 * @method static \MStaack\LaravelPostgis\Eloquent\Builder|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereDistance($location, $operator, $units)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereTelegramToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Room withDistance($location)
 */
	class Room extends \Eloquent {}
}

namespace App\Models\Room{
/**
 * App\Models\Room\Point
 *
 * @property \MStaack\LaravelPostgis\Geometries\Point $location
 * @method static \Illuminate\Database\Eloquent\Builder|Point filterByRoom(\App\Models\Room $room)
 * @method static \Illuminate\Database\Eloquent\Builder|Point newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Point newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Point notExpired()
 * @method static \Illuminate\Database\Eloquent\Builder|Point query()
 */
	class Point extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property string $id
 * @property string $username
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Room[] $rooms
 * @property-read int|null $rooms_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

