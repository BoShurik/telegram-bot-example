<?php

namespace App\Telegram\Location\Command;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

interface LocationCommandInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param BotApi $api
     * @param Update $update
     */
    public function locationExecute(BotApi $api, Update $update);
}