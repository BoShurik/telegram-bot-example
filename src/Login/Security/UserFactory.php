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

use BoShurik\TelegramBotBundle\Authenticator\UserFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFactory implements UserFactoryInterface
{
    public function __construct(private UserManager $userManager)
    {
    }

    public function createFromTelegram(array $data): UserInterface
    {
        $user = new User($data['username'], $data['id']);
        $this->userManager->save($user);

        return $user;
    }
}
