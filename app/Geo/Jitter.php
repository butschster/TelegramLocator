<?php

namespace App\Geo;

use MStaack\LaravelPostgis\Geometries\Point;

/**
 * Данный класс добавляет шум в координаты
 */
class Jitter
{
    const ONE_KM = 0.008983120447446;

    private float $variation;
    private Point $point;
    private int $precision;

    /**
     * @param Point $point Координата
     * @param int $meters Возможное отклонение координат относительно стартовой позиции в метрах
     * @param int $precision Точность исходящей координаты (кол-во знаков после запятой)
     */
    public function __construct(Point $point, int $meters = 10, int $precision = 10)
    {
        $this->variation = $meters / 1000 * static::ONE_KM;

        $this->point = $point;
        $this->precision = $precision;
    }

    /**
     * Создание новой точки с рандомным отклонением
     *
     * @return Point
     */
    public function make(): Point
    {
        return new Point(
            $this->randomize($this->point->getLat()),
            $this->randomize($this->point->getLng())
        );
    }

    protected function randomize(float $coordinate): float
    {
        $from = $coordinate - $this->variation;
        $to = $coordinate + $this->variation;

        return round(
            (mt_rand() / (mt_getrandmax() + 1)) * ($to - $from) + $from,
            $this->precision
        );
    }
}
