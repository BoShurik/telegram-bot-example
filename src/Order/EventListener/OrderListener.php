<?php

namespace App\Order\EventListener;

use App\Order\Event\OrderEvent;
use App\Order\Event\OrderEvents;
use App\Mail\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class OrderListener implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $to;

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            OrderEvents::SUBMIT => 'onOrderSubmit',
        ];
    }

    public function __construct(Mailer $mailer, Environment $twig, $to)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->to = $to;
    }

    public function onOrderSubmit(OrderEvent $event)
    {
        $order = $event->getOrder();
        $body = $this->twig->render('order/email.html.twig', [
            'order' => $order,
        ]);

        $this->mailer->composeAndSend('New order', $body, $this->to);
    }
}