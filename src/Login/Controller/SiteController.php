<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Login\Controller;

use App\Login\Security\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TelegramBot\Api\BotApi;

#[Route(name: 'login_')]
class SiteController extends AbstractController
{
    #[Route(path: '/', name: 'public')]
    public function publicAction(): Response
    {
        return $this->render('login/site/public.html.twig');
    }

    #[Route(path: '/private', name: 'private')]
    #[IsGranted('ROLE_USER')]
    public function privateAction(BotApi $api): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException();
        }
        if ($user->getId()) {
            $api->sendMessage($user->getId(), 'Hello from private area!');
        }

        return $this->render('login/site/private.html.twig');
    }

    public function widgetAction(string $telegramBotName): Response
    {
        return $this->render('login/site/widget.html.twig', [
            'telegram_bot_name' => $telegramBotName,
        ]);
    }
}
