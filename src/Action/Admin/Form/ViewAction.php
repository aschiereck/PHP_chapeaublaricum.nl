<?php

namespace App\Action\Admin\Form;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Config\IGoogleRecaptchaConfigProvider;
use App\Exception\NotFoundException;
use App\Model\Form;
use App\Repository\IFormRepository;
use App\Repository\IPageRepository;

class ViewAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/form/view/{id:\d+}';

    /**
     * @var IFormRepository
     */
    private $formRepository;

    /**
     * @var Form
     */
    private $form;
    /**
     * @var IPageRepository
     */
    private $pageRepository;
    /**
     * @var bool
     */
    private $isRecaptchaEnabled;
    /**
     * @var IGoogleRecaptchaConfigProvider
     */
    private $configProvider;

    /**
     * @param IFormRepository $formRepository
     * @param IPageRepository $pageRepository
     * @param IGoogleRecaptchaConfigProvider $configProvider
     * @internal param bool $isRecaptchaEnabled
     */
    public function __construct(IFormRepository $formRepository, IPageRepository $pageRepository, IGoogleRecaptchaConfigProvider $configProvider)
    {
        $this->formRepository = $formRepository;
        $this->pageRepository = $pageRepository;
        $this->isRecaptchaEnabled = $configProvider->getGoogleRecaptchaConfig()->isRecaptchaEnabled();
        $this->configProvider = $configProvider;
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function execute()
    {
        $id = $this->getRouteParameter('id');
        $this->form = $this->formRepository->findById($id);
        $invalidFields = [];

        if(!$this->form)
        {
            throw new NotFoundException();
        }

        if($this->isPostRequest())
        {
            $this->form->setName($this->getPostParameter('name'));
            $this->form->setReceiver($this->getPostParameter('receiver'));
            $this->form->setSendResponseMessage($this->getPostParameter('sendResponseEmail'));
            $this->form->setResponseEmailTitle($this->getPostParameter('responseEmailTitle'));
            $this->form->setResponseEmailMessage($this->getPostParameter('responseEmailMessage'));
            $this->form->setIsRecaptchaEnabled($this->getPostParameter('isRecaptchaEnabled'));
            $pageId = $this->getPostParameter('page');
            $this->form->setPage($this->pageRepository->findById($pageId));

            if ($this->form->isValid())
            {
                $this->formRepository->save($this->form);
                $this->getFlash()->success('Gegevens opgeslagen');
            }
            else
            {
                $invalidFields = $this->form->getValidationErrors();
            }
        }

        return [
            'form' => $this->form,
            'invalidFields' => $invalidFields,
            'pages'=>$this->pageRepository->findList($this->getSite()),
            'isRecaptchaEnabled' => $this->isRecaptchaEnabled
        ];
    }
}