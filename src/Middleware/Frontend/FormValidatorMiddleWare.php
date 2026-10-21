<?php

namespace App\Middleware\Frontend;

use App\FormValidator;
use App\Util\Mail\IMailRenderer;
use App\Util\Mail\IMailSender;
use App\Model\Form;
use App\Model\FormControl;
use App\Repository\IFormRepository;
use App\View\Frontend\Helper\LinkBuilder;
use App\View\Frontend\Model\FormResult;
use App\View\Frontend\Model\ViewModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ReCaptcha\ReCaptcha;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Mail\Message;
use Zend\Mime\Mime;
use Zend\Mime\Part;
use Zend\Stratigility\MiddlewareInterface;

class FormValidatorMiddleWare implements MiddlewareInterface
{
    /**
     * @var IFormRepository
     */
    private $formRepository;
    /**
     * @var IMailSender
     */
    private $mailSender;
    /**
     * @var IMailRenderer
     */
    private $mailRenderer;
    /**
     * @var string
     */
    private $recaptchaSecret;
    /**
     * @var LinkBuilder
     */
    private $linkBuilder;

    /**
     * FormValidatorMiddleWare constructor.
     * @param IFormRepository $formRepository
     * @param IMailSender $mailSender
     * @param IMailRenderer $mailRenderer
     * @param LinkBuilder $linkBuilder
     * @param string $recaptchaSecret
     */
    public function __construct(IFormRepository $formRepository, IMailSender $mailSender, IMailRenderer $mailRenderer, LinkBuilder $linkBuilder, $recaptchaSecret)
    {
        $this->formRepository = $formRepository;
        $this->mailSender = $mailSender;
        $this->mailRenderer = $mailRenderer;
        $this->recaptchaSecret = $recaptchaSecret;
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $formId = (int)$this->getPostParameter($request, 'form');

        if($formId)
        {
            $form = $this->formRepository->findById($formId);
            if(!$form)
            {
                throw new \Exception('Form not found');
            }
            else
            {
                $captchaCode = $this->getPostParameter($request, 'g-recaptcha-response');
                $isCaptchaValid =  $form->isRecaptchaEnabled() ? $this->isCaptchaValid($captchaCode) : true;

                /** @var Viewmodel $viewModel */
                $viewModel = $request->getAttribute(ViewModel::class);
                $validator = new FormValidator($form, $request);
                if($validator->isValid() && $isCaptchaValid)
                {
                    $viewModel->formResults[] = new FormResult($formId, true);

                    $this->sendEmailToAdmin($form, $validator);
                    if($form->getSendResponseMessage())
                    {
                        $this->sendResponseEmail($form, $validator);
                    }

                    $redirect = $this->createRedirectResponse($request, $form);
                    if($redirect)
                    {
                        return $redirect;
                    }
                }
                else
                {
                    $viewModel->formResults[] = new FormResult($formId, false);
                }
            }
        }

        return $out($request, $response);
    }

    /**
     * @param string $captchaCode
     * @return bool
     */
    private function isCaptchaValid($captchaCode)
    {
        if($this->recaptchaSecret)
        {
            $recaptcha = new ReCaptcha($this->recaptchaSecret);
            $resp = $recaptcha->verify($captchaCode, $_SERVER['REMOTE_ADDR']);
            return $resp->isSuccess();
        }

        return true;
    }

    /**
     * @param Form $form
     * @param FormValidator $validator
     */
    private function sendEmailToAdmin(Form $form, FormValidator $validator)
    {
        $formData = [];
        foreach($form->getFormControls() as $control)
        {
            $formData[$control->getLabel()] = $validator->getPrettyValue($control);
        }

        $htmlPart = $this->mailRenderer->render('frontend::mail/form',[
            'form' => $form,
            'formData' => $formData
        ]);

        $mimeParts = [$htmlPart];

        foreach($form->getFormControls() as $formControl)
        {
            if($formControl->getType() == FormControl::TYPE_FILE)
            {
                $fileName = $_FILES[$formControl->getId()]['name'];
                $filePath = $_FILES[$formControl->getId()]['tmp_name'];


                $fileContents = fopen($filePath, 'r');
                $attachment = new Part($fileContents);
                $attachment->type = Mime::TYPE_OCTETSTREAM;
                $attachment->filename = $fileName;
                $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;

                $mimeParts[] = $attachment;
            }
        }

        $body = new \Zend\Mime\Message();
        $body->setParts($mimeParts);

        foreach(explode(';', $form->getReceiver()) as $receiver)
        {
            $message = new Message();
            $message->setSubject('Formulier van: ' . $_SERVER['HTTP_HOST']);
            $message->addTo(trim($receiver));
            $message->setBody($body);

            $this->mailSender->send($message);
        }
    }

    /**
     * @param Form $form
     * @param FormValidator $validator
     */
    private function sendResponseEmail(Form $form, FormValidator $validator)
    {
        $receiver = '';
        foreach($form->getFormControls() as $control)
        {
            if($control->getType() == FormControl::TYPE_EMAIL && !$receiver)
            {
                $receiver = $validator->getPrettyValue($control);
            }
        }

        if($receiver)
        {
            $part = $this->mailRenderer->render('frontend::mail/confirmation',[
                'message' => $form->getResponseEmailMessage(),
            ]);

            $mimeMessage = new \Zend\Mime\Message();
            $mimeMessage->addPart($part);

            $message = new Message();
            $message->setSubject($form->getResponseEmailTitle());
            $message->addTo(trim($receiver));
            $message->setBody($mimeMessage);
            $this->mailSender->send($message);
        }
    }

    /**
     * @param Request $request
     * @param Form $form
     * @return RedirectResponse
     */
    private function createRedirectResponse(Request $request, Form $form)
    {
        $url = $this->linkBuilder->page($form->getPage());

        if($url && $url != $request->getUri())
        {
            return new RedirectResponse($url);
        }

        return null;
    }

    /**
     * @param Request $request
     * @param $name
     * @param null $defaultValue
     * @return mixed|null
     */
    protected function getPostParameter(Request $request, $name, $defaultValue=null)
    {
        $params = $request->getParsedBody();
        return is_array($params) && isset($params[$name]) ? $params[$name] : $defaultValue;
    }
}