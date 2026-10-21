<?php
namespace App\Util\Mail;

use Zend\Mime\Part;

interface IMailRenderer
{
    /**
     * @param string $path
     * @param array $data
     * @return Part
     */
    public function render($path, $data);

    /**
     * @return string
     */
    public function getLayout();

    /**
     * @param string $layout
     * @return MailRenderer
     */
    public function setLayout($layout);
}