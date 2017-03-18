<?php
/**
 * User: boshurik
 * Date: 18.03.17
 * Time: 21:41
 */

namespace AppBundle\Model;

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

    public function __construct($name, $latitude, $longitude)
    {
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @param float $latitude
     * @param float $longitude
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

        $a = pow(cos($latitudeToRad) * sin($longitudeDelta), 2) + pow(cos($latitudeFromRad) * sin($latitudeToRad) - sin($latitudeFromRad) * cos($latitudeToRad) * cos($longitudeDelta), 2);
        $b = sin($latitudeFromRad) * sin($latitudeToRad) + cos($latitudeFromRad) * cos($latitudeToRad) * cos($longitudeDelta);
        $angle = atan2(sqrt($a), $b);

        return $angle * $earthRadius;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}