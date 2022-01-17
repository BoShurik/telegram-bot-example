<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Post\Model;

class Post
{
    public function __construct(private string $description)
    {
    }

    public function getName(): string
    {
        $words = explode(' ', $this->getSentence());

        return implode(' ', \array_slice($words, 0, 2));
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSentence(): string
    {
        $length = strpos($this->description, '.');

        return substr($this->description, 0, $length !== false ? $length : null);
    }
}
