<?php

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
    /**
     * @var OfficeRepository
     */
    private $officeRepository;

    public function __construct(LocationHandler $locationHandler, OfficeRepository $officeRepository)
    {
        parent::__construct($locationHandler);

        $this->officeRepository = $officeRepository;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return '/offices';
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'Nearest offices';
    }

    /**
     * @inheritDoc
     */
    public function locationExecute(BotApi $api, Update $update)
    {
        $location = $update->getMessage()->getLocation();
        $offices = $this->getOffices($location);

        foreach ($offices as $office) {
            $reply = sprintf("*%s*\n*Distance*: _%s_ м",
                $office->getName(),
                number_format($office->getDistance($location->getLatitude(), $location->getLongitude()), 2, ',', ' ')
            );

            $api->sendMessage($update->getMessage()->getChat()->getId(), $reply, 'markdown');
            $api->sendLocation($update->getMessage()->getChat()->getId(), $office->getLatitude(), $office->getLongitude());
        }
    }

    /**
     * @param Location $location
     * @return Office[]
     */
    public function getOffices(Location $location)
    {
        $offices = $this->officeRepository->findNearest($location->getLatitude(), $location->getLongitude());

        return $offices;
    }
}