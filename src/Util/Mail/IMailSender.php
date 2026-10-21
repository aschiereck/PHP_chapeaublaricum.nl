<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 3-11-2016
 * Time: 0:12
 */
namespace App\Util\Mail;

use Zend\Mail\Message;

interface IMailSender
{
    /**
     * @param Message $message
     */
    public function send(Message $message);
}