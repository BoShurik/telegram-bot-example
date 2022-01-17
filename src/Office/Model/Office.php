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
    public function __construct(private string $name, private float $latitude, private float $longitude)
    {
    }

    public function getDistance(float $latitude, float $longitude): float
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
