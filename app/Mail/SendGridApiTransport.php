<?php

namespace App\Mail;

use Illuminate\Mail\Transport\Transport;
use SendGrid;
use SendGrid\Mail\Mail;
use Swift_Mime_SimpleMessage;

class SendGridApiTransport extends Transport
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send(\Symfony\Component\Mime\RawMessage $message, ?\Symfony\Component\Mailer\Envelope $envelope = null): ?\Symfony\Component\Mailer\SentMessage
    {
        $email = new Mail();
        
        // Get message details
        $messageStr = $message->toString();
        
        // Parse headers and body
        $headers = $message->getHeaders();
        
        // From
        $from = $headers->get('From');
        if ($from) {
            $fromAddresses = $from->getAddresses();
            if (!empty($fromAddresses)) {
                $fromAddr = reset($fromAddresses);
                $email->setFrom($fromAddr->getAddress(), $fromAddr->getName());
            }
        }
        
        // To
        $to = $headers->get('To');
        if ($to) {
            $toAddresses = $to->getAddresses();
            foreach ($toAddresses as $addr) {
                $email->addTo($addr->getAddress(), $addr->getName());
            }
        }
        
        // Subject
        $subject = $headers->get('Subject');
        if ($subject) {
            $email->setSubject($subject->getBodyAsString());
        }
        
        // Body
        $body = $message->getBody();
        if ($body) {
            $email->addContent("text/html", $body->bodyToString());
        }

        // Send via SendGrid API
        $sendgrid = new SendGrid($this->apiKey);
        
        try {
            $response = $sendgrid->send($email);
            return new \Symfony\Component\Mailer\SentMessage($message, $envelope ?? \Symfony\Component\Mailer\Envelope::create($message));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}
