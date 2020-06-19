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

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

interface LocationCommandInterface
{
    public function getId(): string;

    public function locationExecute(BotApi $api, Update $update): void;
}
