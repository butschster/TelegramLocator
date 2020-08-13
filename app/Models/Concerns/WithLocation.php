<?php

namespace App\Models\Concerns;

use App\ValueObjects\Location;
use MStaack\LaravelPostgis\Eloquent\Builder;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use MStaack\LaravelPostgis\Geometries\GeometryInterface;
use MStaack\LaravelPostgis\Geometries\Point;

trait WithLocation
{
    use PostgisTrait;

    /**
     * @inheritDoc
     */
    protected $postgisFields = [
        'location',
    ];

    /**
     * @inheritDoc
     */
    protected $postgisTypes = [
        'location' => [
            'geomtype' => 'geography',
            'srid' => 4326,
        ],
    ];

    /**
     * Сортировка по ближайшим локациям
     *
     * @param Builder $query
     * @param Point $point
     */
    public function scopeOrderByNearest(Builder $query, Point $point)
    {
        $query->orderByRaw(
            "ST_Distance(location, ST_Point(?, ?))",
            [$point->getLng(), $point->getLat()]
        );
    }

    /**
     * @param Builder $query
     * @param Point $location
     * @param float $units in kilometers
     */
    public function scopeInRadius(Builder $query, Point $location, float $units): void
    {
        $longitude = $location->getLng();
        $latitude = $location->getLat();

        // Convert into meters
        $units *= 1000;

        $query->where(function (Builder $query) use($longitude, $latitude, $units) {
            $query
                ->whereNotNull("{$this->getTable()}.location")
                ->whereRaw("ST_Distance({$this->getTable()}.location, ST_Point({$longitude},{$latitude})) <= {$units}");
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Point $location
     * @return Builder
     */
    public function scopeWithDistance(Builder $query, $location)
    {
        $classQuery = $query->getQuery();
        if ($classQuery && !$classQuery->columns) {
            $query->select([$classQuery->from . '.*']);
        }

        if ($location) {
            if ($location instanceof Point) {
                $longitude = $location->getLng();
                $latitude = $location->getLat();
            } else {
                [$longitude, $latitude] = explode(",", $location);
            }
            $division = 1000;
            $q = "ST_Distance({$this->getLocationColumn()},ST_Point({$longitude},{$latitude}))/{$division}";
        } else {
            $q = "0";
        }

        return $query->selectSub($q, 'distance');
    }

    /**
     * @param Builder $query
     * @param Point $location
     * @param float $operator
     * @param float $units
     * @return Builder
     */
    public function scopeWhereDistance(Builder $query, $location, $operator, $units)
    {
        $classQuery = $query->getQuery();
        if ($classQuery && !$classQuery->columns) {
            $query->select([$classQuery->from . '.*']);
        }
        if ($location) {
            if ($location instanceof Point) {
                $longitude = $location->getLng();
                $latitude = $location->getLat();
            } else {
                [$latitude, $longitude] = $location;
            }
            $q = "ST_Distance({$this->getLocationColumn()},ST_Point({$longitude},{$latitude}))";
        } else {
            $q = "0";
        }

        return $query->whereRaw("$q {$operator} {$units}");
    }

    /**
     * @return string
     */
    private function getLocationColumn(): string
    {
        $column = 'location';

        return $this->getTable() . '.' . $column;
    }

    /**
     * Get latitude of location
     *
     * @return float
     */
    public function getLatAttribute(): ?float
    {
        if (!$this->location) {
            return null;
        }

        return $this->location->getLat();
    }

    /**
     * Get longtitude of location
     *
     * @return float
     */
    public function getLonAttribute(): ?float
    {
        if (!$this->location) {
            return null;
        }

        return $this->location->getLng();
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location): void
    {
        $this->location = $location->getPoint();
        $this->address = $location->getAddress();
    }

    /**
     * @param GeometryInterface $geometry
     * @param array $attrs
     *
     * @return string
     */
    public function asWKT(GeometryInterface $geometry, array $attrs)
    {
        if (app()->runningUnitTests()) {
            $wkb = pack('c', 1);
            $wkb .= pack('L', 1);
            $wkb .= pack('dd', $geometry->getLng(), $geometry->getLat());

            return $wkb;
        }

        switch (strtoupper($attrs['geomtype'])) {
            case 'GEOMETRY':
                return $this->geomFromText($geometry, $attrs['srid']);
                break;
            case 'GEOGRAPHY':
            default:
                return $this->geogFromText($geometry);
                break;
        }
    }

    /**
     * @param float $lat
     * @param float $lon
     * @return Point
     */
    protected function createLocationPoint(float $lat, float $lon): Point
    {
        return new Point($lat, $lon);
    }
}
