<?php
/**
 * User: boshurik
 * Date: 18.03.17
 * Time: 21:10
 */

namespace AppBundle\Telegram\Command;

use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

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
    public function execute(BotApi $api, Message $message)
    {
        preg_match(self::REGEXP, $message->getText(), $matches);
        $who = !empty($matches[3]) ? $matches[3] : "World";

        $text = sprintf('Hello *%s*', $who);
        $api->sendMessage($message->getChat()->getId(), $text, 'markdown');
    }
}