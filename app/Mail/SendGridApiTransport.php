<?php

namespace App\Mail;

use SendGrid;
use SendGrid\Mail\Mail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
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
        $email = new Mail();
        
        // Parse the email message
        $messageString = $message->toString();
        
        // Extract headers
        if (method_exists($message, 'getHeaders')) {
            $headers = $message->getHeaders();
            
            // From
            if ($headers->has('from')) {
                $from = $headers->get('from')->getBody();
                if (preg_match('/<(.+?)>/', $from, $matches)) {
                    $email->setFrom($matches[1]);
                } else {
                    $email->setFrom($from);
                }
            }
            
            // To
            if ($headers->has('to')) {
                $to = $headers->get('to')->getBody();
                if (preg_match('/<(.+?)>/', $to, $matches)) {
                    $email->addTo($matches[1]);
                } else {
                    $email->addTo($to);
                }
            }
            
            // Subject
            if ($headers->has('subject')) {
                $email->setSubject($headers->get('subject')->getBody());
            }
        }
        
        // Body
        if (method_exists($message, 'getBody')) {
            $body = $message->getBody();
            if ($body) {
                $bodyString = $body->bodyToString();
                $email->addContent("text/html", $bodyString);
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
            throw new \Symfony\Component\Mailer\Exception\TransportException('SendGrid API error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function __toString(): string
    {
        return 'sendgrid_api';
    }
}
