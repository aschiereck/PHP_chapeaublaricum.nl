<?php

namespace App\Util\Mail;

use Psr\Log\LoggerInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class MailSender implements IMailSender
{
    const PROTOCOL_SSL = 'ssl';
    const PROTOCOL_TLS = 'tls';
    const PROTOCOL_SMTP = 'smtp';

    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $port;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $from;
    /**
     * @var string
     */
    private $fromName;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $protocol;

    /**
     * MailSender constructor.
     * @param LoggerInterface $logger
     * @param string $host
     * @param string $port
     * @param string $protocol
     * @param string $username
     * @param string $password
     * @param string $from
     * @param string $fromName
     * @throws \Exception
     */
    public function __construct(LoggerInterface $logger, $host, $port, $protocol, $username, $password, $from, $fromName)
    {
        if(!in_array($protocol, [self::PROTOCOL_SMTP, self::PROTOCOL_SSL, self::PROTOCOL_TLS]))
        {
            throw new \Exception('Unknown protocol: ' . $protocol);
        }

        $this->logger = $logger;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->from = $from;
        $this->fromName = $fromName;
        $this->protocol = $protocol;
    }

    /**
     * @param Message $message
     */
    public function send(Message $message)
    {
        foreach($message->getTo() as $recipient)
        {
            $this->logger->debug('Sending mail to: ' . $recipient->getEmail());
        }

        $transport = $this->createTransport();
        $message->setFrom($this->from, $this->fromName);
        $transport->send($message);
    }


    /**
     * @return Smtp
     */
    private function createTransport()
    {
        $smtpOptions = new SmtpOptions();
        $smtpOptions->setHost($this->host);
        $smtpOptions->setPort($this->port);
        $smtpOptions->setConnectionConfig([
            'username' => $this->username,
            'password' => $this->password
        ]);

        $smtpOptions->setConnectionClass('Login');
        if(in_array($this->protocol, [self::PROTOCOL_SSL, self::PROTOCOL_TLS]))
        {
            $smtpOptions->setConnectionConfig([
                'ssl' => $this->protocol,
            ]);
        }

        $transport = new Smtp($smtpOptions);
        return $transport;
    }
}