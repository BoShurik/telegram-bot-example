<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Office\Model;

class Office
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    public function __construct(string $name, float $latitude, float $longitude)
    {
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return float
     */
    public function getDistance($latitude, $longitude)
    {
        $earthRadius = 6371000; // Meters

        $latitudeFromRad = deg2rad($latitude);
        $longitudeFromRad = deg2rad($longitude);
        $latitudeToRad = deg2rad($this->latitude);
        $longitudeToRad = deg2rad($this->longitude);

        $longitudeDelta = $longitudeToRad - $longitudeFromRad;

        $a = (cos($latitudeToRad) * sin($longitudeDelta)) ** 2 + (cos($latitudeFromRad) * sin($latitudeToRad) - sin($latitudeFromRad) * cos($latitudeToRad) * cos($longitudeDelta)) ** 2;
        $b = sin($latitudeFromRad) * sin($latitudeToRad) + cos($latitudeFromRad) * cos($latitudeToRad) * cos($longitudeDelta);
        $angle = atan2(sqrt($a), $b);

        return $angle * $earthRadius;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
