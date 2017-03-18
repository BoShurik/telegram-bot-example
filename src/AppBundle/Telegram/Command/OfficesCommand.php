<?php
/**
 * User: boshurik
 * Date: 18.03.17
 * Time: 21:34
 */

namespace AppBundle\Telegram\Command;

use AppBundle\Model\Office;
use AppBundle\Repository\OfficeRepository;
use AppBundle\Telegram\LocationHandler;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Location;
use TelegramBot\Api\Types\Message;

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
    public function locationExecute(BotApi $api, Message $message)
    {
        $location = $message->getLocation();
        $offices = $this->getOffices($location);

        foreach ($offices as $office) {
            $reply = sprintf("*%s*\n*Расстояние*: _%s_ м",
                $office->getName(),
                number_format($office->getDistance($location->getLatitude(), $location->getLongitude()), 2, ',', ' ')
            );

            $api->sendMessage($message->getChat()->getId(), $reply, 'markdown');
            $api->sendLocation($message->getChat()->getId(), $office->getLatitude(), $office->getLongitude());
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