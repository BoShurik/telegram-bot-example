<?php

namespace App\Telegram\Location;

use Psr\Cache\CacheItemPoolInterface;

class LocationHandler
{
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
     * @param integer $chat
     * @return bool
     */
    public function hasLocationCommand($chat)
    {
        return $this->cache->hasItem((string)$chat);
    }

    /**
     * @param integer $chat
     * @return string|null
     */
    public function getLocationCommand($chat)
    {
        if (!$this->hasLocationCommand($chat)) {
            return null;

        }

        $item = $this->cache->getItem((string)$chat);

        return $item->get();
    }

    /**
     * @param integer $chat
     * @param string $id
     */
    public function setLocationCommand($chat, $id)
    {
        $item = $this->cache->getItem((string)$chat);
        $item->set($id);
        if ($this->lifetime > 0) {
            $item->expiresAfter($this->lifetime);
        }

        $this->cache->save($item);
    }

    /**
     * @param integer $chat
     */
    public function clearLocationCommand($chat)
    {
        $this->cache->deleteItem((string)$chat);
    }
}