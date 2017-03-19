<?php
/**
 * User: boshurik
 * Date: 19.03.17
 * Time: 16:35
 */

namespace AppBundle\Telegram;

use AppBundle\Model\Order;
use Doctrine\Common\Cache\Cache;

class OrderHandler
{
    const PREFIX_ORDER = 'order_';
    const PREFIX_STEP = 'step_';

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var int
     */
    private $lifetime;

    public function __construct(Cache $cache, $lifetime = 0)
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

        return $this->cache->contains($key);
    }

    /**
     * @param string $id
     */
    public function clearData($id)
    {
        $stepKey = $this->getKey(self::PREFIX_STEP, $id);
        $orderKey = $this->getKey(self::PREFIX_ORDER, $id);

        $this->cache->delete($stepKey);
        $this->cache->delete($orderKey);
    }

    /**
     * @param string $id
     * @return int
     */
    public function getCurrentStep($id)
    {
        $key = $this->getKey(self::PREFIX_STEP, $id);
        if (!$this->cache->contains($key)) {
            return 0;
        }

        return $this->cache->fetch($key);
    }

    /**
     * @param string $id
     * @param int $step
     */
    public function setCurrentStep($id, $step)
    {
        $key = $this->getKey(self::PREFIX_STEP, $id);
        $this->cache->save($key, $step, $this->lifetime);
    }

    /**
     * @param string $id
     * @return Order
     */
    public function getOrder($id)
    {
        $key = $this->getKey(self::PREFIX_ORDER, $id);
        if (!$this->cache->contains($key)) {
            return $this->createOrder();
        }

        return $this->cache->fetch($key);
    }

    /**
     * @param string $id
     * @param Order $order
     */
    public function setOrder($id, Order $order)
    {
        $key = $this->getKey(self::PREFIX_ORDER, $id);
        $this->cache->save($key, $order, $this->lifetime);
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