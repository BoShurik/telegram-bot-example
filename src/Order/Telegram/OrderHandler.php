<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Order\Telegram;

use App\Order\Model\Order;
use Psr\Cache\CacheItemPoolInterface;

class OrderHandler
{
    public const PREFIX_ORDER = 'order_';
    public const PREFIX_STEP = 'step_';

    public function __construct(private CacheItemPoolInterface $cache, private int $lifetime = 0)
    {
    }

    public function hasData(string $id): bool
    {
        $key = $this->getKey(self::PREFIX_STEP, $id);

        return $this->cache->hasItem($key);
    }

    public function clearData(string $id): void
    {
        $stepKey = $this->getKey(self::PREFIX_STEP, $id);
        $orderKey = $this->getKey(self::PREFIX_ORDER, $id);

        $this->cache->deleteItems([$stepKey, $orderKey]);
    }

    public function getCurrentStep(string $id): int
    {
        $key = $this->getKey(self::PREFIX_STEP, $id);
        if (!$this->cache->hasItem($key)) {
            return 0;
        }

        $item = $this->cache->getItem($key);

        return (int) $item->get();
    }

    public function setCurrentStep(string $id, int $step): void
    {
        $key = $this->getKey(self::PREFIX_STEP, $id);

        $item = $this->cache->getItem($key);
        $item->set($step);
        if ($this->lifetime > 0) {
            $item->expiresAfter($this->lifetime);
        }

        $this->cache->save($item);
    }

    public function getOrder(string $id): Order
    {
        $key = $this->getKey(self::PREFIX_ORDER, $id);
        if (!$this->cache->hasItem($key)) {
            return $this->createOrder();
        }

        $item = $this->cache->getItem($key);

        return $item->get();
    }

    public function setOrder(string $id, Order $order): void
    {
        $key = $this->getKey(self::PREFIX_ORDER, $id);

        $item = $this->cache->getItem($key);
        $item->set($order);
        if ($this->lifetime > 0) {
            $item->expiresAfter($this->lifetime);
        }

        $this->cache->save($item);
    }

    public function createOrder(): Order
    {
        return new Order();
    }

    private function getKey(string $prefix, string $id): string
    {
        return sprintf('%s%s', $prefix, $id);
    }
}
