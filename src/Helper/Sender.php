<?php

namespace App\Helper;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Sender
{

    public function __construct(private MailerInterface $mailer)
    {
    }

    public function sendEmail(string $subject, string $text, string $dest): void
    {
        $email = (new Email())
            ->subject($subject)
            ->text($text)
            ->from('no_reply@serie.com')
            ->to($dest);

        $this->mailer->send($email);
    }

}