<?php
/**
 * User: boshurik
 * Date: 18.03.17
 * Time: 21:21
 */

namespace AppBundle\Telegram\Command;

use BoShurik\TelegramBotBundle\Telegram\Command\HelpCommand as BaseCommand;
use TelegramBot\Api\Types\Message;

class HelpCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    public function isApplicable(Message $message)
    {
        return true;
    }
}