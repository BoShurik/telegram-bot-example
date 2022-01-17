<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Office\Telegram\Command;

use App\Office\Model\Office;
use App\Office\Repository\OfficeRepository;
use App\Telegram\Location\Command\AbstractLocationCommand;
use App\Telegram\Location\LocationHandler;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Location;
use TelegramBot\Api\Types\Update;

class OfficesCommand extends AbstractLocationCommand implements PublicCommandInterface
{
    public function __construct(LocationHandler $locationHandler, private OfficeRepository $officeRepository)
    {
        parent::__construct($locationHandler);
    }

    public function getName(): string
    {
        return '/offices';
    }

    public function getDescription(): string
    {
        return 'Nearest offices';
    }

    public function locationExecute(BotApi $api, Update $update): void
    {
        $location = $update->getMessage()->getLocation();
        $offices = $this->getOffices($location);

        foreach ($offices as $office) {
            $reply = sprintf(
                "*%s*\n*Distance*: _%s_ м",
                $office->getName(),
                number_format($office->getDistance($location->getLatitude(), $location->getLongitude()), 2, ',', ' ')
            );

            $api->sendMessage($update->getMessage()->getChat()->getId(), $reply, 'markdown');
            $api->sendLocation($update->getMessage()->getChat()->getId(), $office->getLatitude(), $office->getLongitude());
        }
    }

    /**
     * @return Office[]
     */
    public function getOffices(Location $location): array
    {
        return $this->officeRepository->findNearest($location->getLatitude(), $location->getLongitude());
    }
}
