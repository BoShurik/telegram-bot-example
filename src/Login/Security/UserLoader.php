<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Login\Security;

use BoShurik\TelegramBotBundle\Guard\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLoader implements UserLoaderInterface
{
    public function loadByTelegramId(string $id): ?UserInterface
    {
        return null; // We dont have storage for users
    }
}
