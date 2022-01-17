<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Login\Security;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class UserManager
{
    public function __construct(private CacheItemPoolInterface $cache)
    {
    }

    public function save(User $user): void
    {
        $item = $this->getCacheItem($user->getId());
        $item->set($user);
        $this->cache->save($item);
    }

    public function find(string $id): ?User
    {
        $item = $this->getCacheItem($id);
        if ($item->isHit()) {
            return $item->get();
        }

        return null;
    }

    private function getCacheItem(string $id): CacheItemInterface
    {
        return $this->cache->getItem(sprintf('user-%s', $id));
    }
}
