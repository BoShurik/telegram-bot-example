<?php

namespace App\Help\Telegram\Command;

use BoShurik\TelegramBotBundle\Telegram\Command\HelpCommand as BaseCommand;
use TelegramBot\Api\Types\Update;

class HelpCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    public function isApplicable(Update $update)
    {
        return $update->getMessage() !== null;
    }
}