<?php
/**
 * User: boshurik
 * Date: 18.03.17
 * Time: 21:36
 */

namespace AppBundle\Telegram;

use Doctrine\Common\Cache\Cache;

class LocationHandler
{
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
     * @param integer $chat
     * @return bool
     */
    public function hasLocationCommand($chat)
    {
        return $this->cache->contains($chat);
    }

    /**
     * @param integer $chat
     * @return string|null
     */
    public function getLocationCommand($chat)
    {
        if ($this->hasLocationCommand($chat)) {
            return $this->cache->fetch($chat);
        }

        return null;
    }

    /**
     * @param integer $chat
     * @param string $id
     */
    public function setLocationCommand($chat, $id)
    {
        $this->cache->save($chat, $id, $this->lifetime);
    }

    /**
     * @param integer $chat
     */
    public function clearLocationCommand($chat)
    {
        $this->cache->delete($chat);
    }
}