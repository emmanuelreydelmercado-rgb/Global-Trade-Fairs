<?php

namespace App\Mail;

use SendGrid;
use SendGrid\Mail\Mail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;

class SendGridApiTransport implements TransportInterface
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send(RawMessage $message, ?\Symfony\Component\Mailer\Envelope $envelope = null): ?SentMessage
    {
        // Convert to Email object if it's not already
        if (!$message instanceof Email) {
            throw new \Exception('Message must be an instance of Symfony\Component\Mime\Email');
        }

        $email = new Mail();
        
        // From address
        $from = $message->getFrom();
        if (!empty($from)) {
            $fromAddress = $from[0];
            $email->setFrom(
                $fromAddress->getAddress(),
                $fromAddress->getName() ?? ''
            );
        }
        
        // To addresses
        $to = $message->getTo();
        foreach ($to as $address) {
            $email->addTo(
                $address->getAddress(),
                $address->getName() ?? ''
            );
        }
        
        // Subject
        $email->setSubject($message->getSubject() ?? '');
        
        // Body - try HTML first, fallback to text
        $htmlBody = $message->getHtmlBody();
        if ($htmlBody) {
            $email->addContent("text/html", $htmlBody);
        } else {
            $textBody = $message->getTextBody();
            if ($textBody) {
                $email->addContent("text/plain", $textBody);
            }
        }

        // Send via SendGrid API
        $sendgrid = new SendGrid($this->apiKey);
        
        try {
            $response = $sendgrid->send($email);
            
            if ($envelope === null) {
                $envelope = \Symfony\Component\Mailer\Envelope::create($message);
            }
            
            return new SentMessage($message, $envelope);
        } catch (\Exception $e) {
            throw new \Symfony\Component\Mailer\Exception\TransportException(
                'SendGrid API error: ' . $e->getMessage(), 
                0, 
                $e
            );
        }
    }

    public function __toString(): string
    {
        return 'sendgrid_api';
    }
}
