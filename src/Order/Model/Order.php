<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Order\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Order
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(groups={"step1"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(groups={"step2"})
     * @Assert\Length(max="15", groups={"step2"})
     */
    private $phone;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(groups={"step3"})
     * @Assert\Email(groups={"step3"})
     */
    private $email;

    /**
     * @var string|null
     */
    private $message;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
