<?php
/**
 * User: boshurik
 * Date: 03.10.19
 * Time: 19:09
 */

namespace App\Post\Model;

class Post
{
    /**
     * @var string
     */
    private $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        $words = explode(' ', $this->getSentence());

        return implode(' ', array_slice($words, 0, 2));
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSentence(): string
    {
        return substr($this->description, 0, strpos($this->description, '.'));
    }
}