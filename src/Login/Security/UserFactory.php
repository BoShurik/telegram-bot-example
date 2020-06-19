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

use BoShurik\TelegramBotBundle\Guard\UserFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFactory implements UserFactoryInterface
{
    public function createFromTelegram(array $data): UserInterface
    {
        return new User($data['username'], $data['id']);
    }
}
