<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public function __construct(CacheItemPoolInterface $cache, int $lifetime = 0)
    {
        $this->cache = $cache;
        $this->lifetime = $lifetime;
    }

    public function hasLocationCommand(string $chat): bool
    {
        return $this->cache->hasItem($chat);
    }

    public function getLocationCommand(string $chat): ?string
    {
        if (!$this->hasLocationCommand($chat)) {
            return null;
        }

        $item = $this->cache->getItem($chat);

        return $item->get();
    }

    public function setLocationCommand(string $chat, string $id): void
    {
        $item = $this->cache->getItem($chat);
        $item->set($id);
        if ($this->lifetime > 0) {
            $item->expiresAfter($this->lifetime);
        }

        $this->cache->save($item);
    }

    public function clearLocationCommand(string $chat): void
    {
        $this->cache->deleteItem($chat);
    }
}
