<?php
/**
 * User: boshurik
 * Date: 18.03.17
 * Time: 21:32
 */

namespace AppBundle\Telegram\Command;

use AppBundle\Telegram\LocationHandler;
use BoShurik\TelegramBotBundle\Telegram\Command\CommandInterface;
use BoShurik\TelegramBotBundle\Telegram\Command\CommandPool;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Location;
use TelegramBot\Api\Types\Message;

class LocationCommand implements CommandInterface
{
    /**
     * @var CommandPool
     */
    private $commandPool;

    /**
     * @var LocationHandler
     */
    private $locationHandler;

    public function __construct(CommandPool $commandPool, LocationHandler $locationHandler)
    {
        $this->commandPool = $commandPool;
        $this->locationHandler = $locationHandler;
    }

    /**
     * @inheritDoc
     */
    public function execute(BotApi $api, Message $message)
    {
        $command = $this->getLocationCommand($message);
        $command->locationExecute($api, $message);
        $this->locationHandler->clearLocationCommand($message->getChat()->getId());
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(Message $message)
    {
        if (!$message->getLocation() instanceof Location) {
            return false;
        }
        if (!$this->locationHandler->hasLocationCommand($message->getChat()->getId())) {
            return false;
        }
        if (!$command = $this->getLocationCommand($message)) {
            return false;
        }

        return true;
    }

    /**
     * @param Message $message
     * @return LocationCommandInterface|null
     */
    private function getLocationCommand(Message $message)
    {
        $id = $this->locationHandler->getLocationCommand($message->getChat()->getId());
        foreach ($this->commandPool->getCommands() as $command) {
            if (!$command instanceof LocationCommandInterface) {
                continue;
            }
            if ($command->getId() != $id) {
                continue;
            }

            return $command;
        }

        return null;
    }
}