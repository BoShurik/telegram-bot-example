<?php
/**
 * User: boshurik
 * Date: 18.03.17
 * Time: 21:29
 */

namespace AppBundle\Telegram\Command;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

interface LocationCommandInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param BotApi $api
     * @param Message $message
     */
    public function locationExecute(BotApi $api, Message $message);
}