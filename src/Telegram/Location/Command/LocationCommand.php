<?php

namespace App\Telegram\Location\Command;

use App\Telegram\Location\LocationHandler;
use BoShurik\TelegramBotBundle\Telegram\Command\CommandInterface;
use BoShurik\TelegramBotBundle\Telegram\Command\CommandRegistry;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Location;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

class LocationCommand implements CommandInterface
{
    /**
     * @var CommandRegistry
     */
    private $commandRegistry;

    /**
     * @var LocationHandler
     */
    private $locationHandler;

    public function __construct(CommandRegistry $commandRegistry, LocationHandler $locationHandler)
    {
        $this->commandRegistry = $commandRegistry;
        $this->locationHandler = $locationHandler;
    }

    /**
     * @inheritDoc
     */
    public function execute(BotApi $api, Update $update)
    {
        $command = $this->getLocationCommand($update->getMessage());
        $command->locationExecute($api, $update);
        $this->locationHandler->clearLocationCommand($update->getMessage()->getChat()->getId());
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(Update $update)
    {
        if (!$update->getMessage()) {
            return false;
        }
        if (!$update->getMessage()->getLocation() instanceof Location) {
            return false;
        }
        if (!$this->locationHandler->hasLocationCommand($update->getMessage()->getChat()->getId())) {
            return false;
        }
        if (!$command = $this->getLocationCommand($update->getMessage())) {
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
        foreach ($this->commandRegistry->getCommands() as $command) {
            if (!$command instanceof LocationCommandInterface) {
                continue;
            }
            if ($command->getId() !== $id) {
                continue;
            }

            return $command;
        }

        return null;
    }
}