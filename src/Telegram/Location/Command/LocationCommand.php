<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        /** @var LocationCommandInterface $command */
        $command = $this->getLocationCommand($update->getMessage());
        $command->locationExecute($api, $update);
        $this->locationHandler->clearLocationCommand((string) $update->getMessage()->getChat()->getId());
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
        if (!$this->locationHandler->hasLocationCommand((string) $update->getMessage()->getChat()->getId())) {
            return false;
        }
        if (!$command = $this->getLocationCommand($update->getMessage())) {
            return false;
        }

        return true;
    }

    private function getLocationCommand(Message $message): ?LocationCommandInterface
    {
        $id = $this->locationHandler->getLocationCommand((string) $message->getChat()->getId());
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
