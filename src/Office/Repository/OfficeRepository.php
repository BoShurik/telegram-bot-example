<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Office\Repository;

use App\Office\Model\Office;

class OfficeRepository
{
    /**
     * @var Office[]|null
     */
    private $offices;

    public function findNearest(float $latitude, float $longitude, int $count = 3): array
    {
        $this->init();

        $offices = [];
        foreach ((array) $this->offices as $office) {
            $offices[(string) $office->getDistance($latitude, $longitude)] = $office;
        }

        ksort($offices);

        return \array_slice($offices, 0, $count);
    }

    private function init()
    {
        if (null !== $this->offices) {
            return;
        }

        $this->offices = [
            new Office('Moscow', 55.7494733, 37.3523182),
            new Office('Saint Petersburg', 59.9390094, 29.5303031),
            new Office('Novosibirsk', 54.969655, 82.6692275),
            new Office('Yekaterinburg', 56.8135772, 60.3747574),
            new Office('Nizhny Novgorod', 56.2926609, 43.7866631),
            new Office('Vladimir', 56.1376417, 40.343441),
        ];
    }
}
