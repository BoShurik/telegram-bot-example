<?php

namespace App\Order\Event;

use App\Order\Model\Order;
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