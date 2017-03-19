<?php
/**
 * User: boshurik
 * Date: 19.03.17
 * Time: 16:59
 */

namespace AppBundle\Event\Order;

use AppBundle\Model\Order;
use Symfony\Component\EventDispatcher\Event;

class OrderEvent extends Event
{
    /**
     * @var Order
     */
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}