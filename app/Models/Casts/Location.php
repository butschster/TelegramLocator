<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use MStaack\LaravelPostgis\Geometries\Point as GeoPoint;

class Location implements CastsAttributes
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param array $value
     * @param array $attributes
     * @return mixed|GeoPoint
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return new GeoPoint(
            $value[0], $value[1]
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param GeoPoint $value
     * @param array $attributes
     * @return array|string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return [
            'location' => [
                $value->getLat(),
                $value->getLng()
            ]
        ];
    }
}
