<?php

namespace App\Mail;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var string $from
     */
    private $from;

    public function __construct(\Swift_Mailer $mailer, $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string|array $to
     * @param array $attachments
     * @param string|null $from
     * @return \Swift_Message
     */
    public function composeMessage($subject, $body, $to, $attachments = [], $from = null)
    {
        if (is_null($from)) {
            $from = $this->from;
        }

        /** @var \Swift_Message $message */
        $message = new \Swift_Message();
        $message
            ->setSubject($subject)
            ->setTo($to)
            ->setFrom($from)
            ->setBody($body, 'text/html', 'utf-8');

        if ($attachments) {
            foreach ($attachments as $attachment) {
                if ($attachment instanceof \Swift_Attachment) {
                    $message->attach($attachment);
                } else {
                    $message->attach(\Swift_Attachment::fromPath($attachment));
                }
            }
        }

        return $message;
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string|array $to
     * @param array $attachments
     * @param string|null $from
     * @return int
     */
    public function composeAndSend($subject, $body, $to, $attachments = [], $from = null)
    {
        return $this->mailer->send($this->composeMessage($subject, $body, $to, $attachments, $from));
    }

    /**
     * @param \Swift_Message $message
     * @return int
     */
    public function send(\Swift_Message $message)
    {
        return $this->mailer->send($message);
    }
}