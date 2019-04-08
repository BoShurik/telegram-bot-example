<?php

namespace App\Hello\Telegram\Command;

use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

class HelloCommand extends AbstractCommand implements PublicCommandInterface
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return '/hello';
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'Example command';
    }

    /**
     * @inheritDoc
     */
    public function execute(BotApi $api, Update $update)
    {
        preg_match(self::REGEXP, $update->getMessage()->getText(), $matches);
        $who = !empty($matches[3]) ? $matches[3] : "World";

        $text = sprintf('Hello *%s*', $who);
        $api->sendMessage($update->getMessage()->getChat()->getId(), $text, 'markdown');
    }
}