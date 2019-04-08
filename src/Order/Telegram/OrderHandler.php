<?php

namespace App\Order\Telegram;

use App\Order\Model\Order;
use Psr\Cache\CacheItemPoolInterface;

class OrderHandler
{
    const PREFIX_ORDER = 'order_';
    const PREFIX_STEP = 'step_';

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var int
     */
    private $lifetime;

    public function __construct(CacheItemPoolInterface $cache, $lifetime = 0)
    {
        $this->cache = $cache;
        $this->lifetime = $lifetime;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasData($id)
    {
        $key = $this->getKey(self::PREFIX_STEP, $id);

        return $this->cache->hasItem($key);
    }

    /**
     * @param string $id
     */
    public function clearData($id)
    {
        $stepKey = $this->getKey(self::PREFIX_STEP, $id);
        $orderKey = $this->getKey(self::PREFIX_ORDER, $id);

        $this->cache->deleteItems([$stepKey, $orderKey]);
    }

    /**
     * @param string $id
     * @return int
     */
    public function getCurrentStep($id)
    {
        $key = $this->getKey(self::PREFIX_STEP, $id);
        if (!$this->cache->hasItem($key)) {
            return 0;
        }

        $item = $this->cache->getItem($key);

        return (int)$item->get();
    }

    /**
     * @param string $id
     * @param int $step
     */
    public function setCurrentStep($id, $step)
    {
        $key = $this->getKey(self::PREFIX_STEP, $id);

        $item = $this->cache->getItem($key);
        $item->set($step);
        if ($this->lifetime > 0) {
            $item->expiresAfter($this->lifetime);
        }

        $this->cache->save($item);
    }

    /**
     * @param string $id
     * @return Order
     */
    public function getOrder($id)
    {
        $key = $this->getKey(self::PREFIX_ORDER, $id);
        if (!$this->cache->hasItem($key)) {
            return $this->createOrder();
        }

        $item = $this->cache->getItem($key);

        return $item->get();
    }

    /**
     * @param string $id
     * @param Order $order
     */
    public function setOrder($id, Order $order)
    {
        $key = $this->getKey(self::PREFIX_ORDER, $id);

        $item = $this->cache->getItem($key);
        $item->set($order);
        if ($this->lifetime > 0) {
            $item->expiresAfter($this->lifetime);
        }

        $this->cache->save($item);
    }

    /**
     * @return Order
     */
    public function createOrder()
    {
        $order = new Order();

        return $order;
    }

    /**
     * @param string $prefix
     * @param string $id
     * @return string
     */
    private function getKey($prefix, $id)
    {
        return sprintf('%s%s', $prefix, $id);
    }
}