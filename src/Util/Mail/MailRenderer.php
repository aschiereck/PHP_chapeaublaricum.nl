<?php

namespace App\Util\Mail;

use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Mime\Part;
use Zend\View\Model\ViewModel;

class MailRenderer implements IMailRenderer
{
    const LAYOUT = 'frontend/mail/default';

    /**
     * @var string
     */
    private $layout = self::LAYOUT;

    /**
     * @var TemplateRendererInterface
     */
    private $templateRenderer;

    /**
     * MailRenderer constructor.
     * @param TemplateRendererInterface $templateRenderer
     */
    public function __construct(TemplateRendererInterface $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * @param string $path
     * @param array $data
     * @return Part
     */
    public function render($path, $data)
    {
        $data['layout'] = $this->createLayout();
        $body = $this->templateRenderer->render($path, $data);

        $mimePart = new Part($body);
        $mimePart->setType("text/html");

        return $mimePart;
    }

    /**
     * @return ViewModel
     */
    private function createLayout()
    {
        $layout = new ViewModel([
            'encoding' => 'utf-8',
        ]);
        $layout->setTemplate($this->layout);
        return $layout;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     * @return MailRenderer
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }
}