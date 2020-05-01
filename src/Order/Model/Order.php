<?php

namespace App\Order\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Order
{
    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"step1"})
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"step2"})
     * @Assert\Length(max="15", groups={"step2"})
     */
    private $phone;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"step3"})
     * @Assert\Email(groups={"step3"})
     */
    private $email;

    /**
     * @var string
     */
    private $message;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}