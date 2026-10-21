<?php

namespace App\Action\Admin\Page;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\UniqueConstraintViolationException;
use App\Model\Page;
use App\Repository\IPageRepository;
use App\Service\Seo\IPageCompletenessChecker;
use Psr\Http\Message\ResponseInterface as Response;

class CreateAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/page/create';

    /**
     * @var IPageCompletenessChecker
     */
    private $pageCompletenessChecker;
    /**
     * @var IPageRepository
     */
    private $pageRepository;

    /**
     * @param IPageRepository $pageRepository
     * @param IPageCompletenessChecker $pageCompletenessChecker
     */
    public function __construct(IPageRepository $pageRepository, IPageCompletenessChecker $pageCompletenessChecker)
    {
        $this->pageCompletenessChecker = $pageCompletenessChecker;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return Response|array
     */
    protected function execute()
    {
        $invalidFields = [];

        $page = new Page();
        $page->setSite($this->getSite());

        if($this->isPostRequest())
        {
            $page->setName($this->getPostParameter('name'));
            $page->setUrl($this->getPostParameter('url'));
            $page->setHeadTitle($this->getPostParameter('headTitle'));
            $page->setMetaDescription($this->getPostParameter('metaDescription'));
            $page->setAllowSeoIndex($this->getPostParameter('allowSeoIndex'));
            $page->setFocusWords($this->getPostParameter('focusWords'));
            $page->setModifiedDate(new \DateTime());
            $page->setSeoCompleteness($this->pageCompletenessChecker->calculateCompletness($page));

            if ($page->isValid())
            {
                try
                {
                    $this->pageRepository->save($page);
                    $this->getFlash()->success('Pagina aangemaakt');
                    return $this->redirectTo(EditAction::ROUTE, ['id'=>$page->getId()]);
                }
                catch(UniqueConstraintViolationException $e)
                {
                    $invalidFields[$e->getFieldName()] = ['De waarde voor dit veld moet uniek zijn maar is al in gebruik door een andere site'];
                }
            }
            else
            {
                $invalidFields = $page->getValidationErrors();
            }
        }

        return [
            'page' => $page,
            'invalidFields' => $invalidFields
        ];
    }
}