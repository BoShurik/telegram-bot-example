<?php
/**
 * User: boshurik
 * Date: 19.03.17
 * Time: 17:32
 */

namespace AppBundle\EventListener;

use AppBundle\Event\Order\OrderEvent;
use AppBundle\Mail\Mailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class OrderListener
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var string
     */
    private $to;


    public function __construct(Mailer $mailer, EngineInterface $templating, $to)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->to = $to;
    }

    public function onOrderSubmit(OrderEvent $event)
    {
        $order = $event->getOrder();
        $body = $this->templating->render('order/email.html.twig', [
            'order' => $order,
        ]);

        $this->mailer->composeAndSend('New order', $body, $this->to);
    }
}