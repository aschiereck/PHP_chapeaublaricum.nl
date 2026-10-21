<?php

namespace App;

use App\Model\Form;
use App\Model\FormControl;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Validator\EmailAddress;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;


class FormValidator
{
    /**
     * @var Form
     */
    private $form;
    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * FormValidator constructor.
     * @param Form $form
     * @param ServerRequestInterface $request
     */
    public function __construct(Form $form, ServerRequestInterface $request)
    {
        $this->form = $form;
        $this->request = $request;
    }


    /**
     * @param FormControl $control
     * @return string
     */
    public function getPrettyValue(FormControl $control)
    {
        $result = $this->getPostParameter($control->getId());

        if($control->isListType())
        {
            if(is_array($result))
            {
                $results = [];
                foreach($result as $r)
                {
                    $results[] = $control->getListOption($r);
                }
                $result = '<br/> -' . implode('<br/> -', $results);
            }
            else
            {
                $result = $control->getListOption($result);
            }
        }
        else if($control->getType() == FormControl::TYPE_FILE)
        {
            $result = isset($_FILES[$control->getId()]['name']) ? $_FILES[$control->getId()]['name'] : '';
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        foreach($this->form->getFormControls() as $control)
        {
            if($control->getType() == FormControl::TYPE_FILE)
            {
                $isAttemptedToUpload = isset($_FILES[$control->getId()]['name']) && $_FILES[$control->getId()]['name'];
                $isUploadSuccessful = isset($_FILES[$control->getId()]['error']) && $_FILES[$control->getId()]['error'] == 0;

                if(!$isAttemptedToUpload && $control->isRequired())
                {
                    return false;
                }
                else if(!$isUploadSuccessful)
                {
                    return false;
                }
            }
            else
            {
                $value = $this->getPostParameter($control->getId());
                $validatorChain = new ValidatorChain();

                if ($control->isRequired())
                {
                    $validatorChain->attach(new NotEmpty());
                }
                if ($control->getType() == FormControl::TYPE_EMAIL && $value != '')
                {
                    $validatorChain->attach(new EmailAddress());
                }
                if ($control->isListType() && $value != '')
                {
                    $listOptions = explode(PHP_EOL, $control->getListOptions());
                    $validatorChain->attach(new InArray(['haystack' => array_keys($listOptions)]));
                }

                if (!is_array($value))
                {
                    $value = [$value];
                }
                foreach ($value as $v)
                {
                    if (!$validatorChain->isValid($v))
                    {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param $name
     * @param null $defaultValue
     * @return mixed|null
     */
    protected function getPostParameter($name, $defaultValue=null)
    {
        $params = $this->request->getParsedBody();
        return is_array($params) && isset($params[$name]) ? $params[$name] : $defaultValue;
    }
}