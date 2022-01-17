<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Help\Telegram\Command;

use BoShurik\TelegramBotBundle\Telegram\Command\HelpCommand as BaseCommand;
use TelegramBot\Api\Types\Update;

class HelpCommand extends BaseCommand
{
    public function isApplicable(Update $update): bool
    {
        return $update->getMessage() !== null;
    }
}
