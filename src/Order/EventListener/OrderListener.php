<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Order\EventListener;

use App\Mail\Mailer;
use App\Order\Event\OrderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class OrderListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OrderEvent::class => 'onOrderSubmit',
        ];
    }

    public function __construct(private Mailer $mailer, private Environment $twig, private string $to)
    {
    }

    public function onOrderSubmit(OrderEvent $event): void
    {
        $order = $event->getOrder();
        $body = $this->twig->render('order/email.html.twig', [
            'order' => $order,
        ]);

        $this->mailer->send('New order', $body, $this->to);
    }
}
