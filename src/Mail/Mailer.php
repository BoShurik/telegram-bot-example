<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Mail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var string
     */
    private $from;

    public function __construct(MailerInterface $mailer, string $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    /**
     * @param string|string[] $to
     */
    public function send(string $subject, string $body, $to, array $attachments = [], ?string $from = null): void
    {
        $email = (new Email())
            ->from($from ?? $this->from)
            ->to(...(array) $to)
            ->subject($subject)
            ->html($body)
        ;

        $this->mailer->send($email);
    }
}
