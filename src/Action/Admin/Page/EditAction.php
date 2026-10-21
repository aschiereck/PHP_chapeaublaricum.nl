<?php

namespace App\Action\Admin\Page;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Exception\UniqueConstraintViolationException;
use App\Repository\IPageBlockMetaRepository;
use App\Repository\IPageRepository;
use App\Service\Seo\IPageCompletenessChecker;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/page/edit/{id:\d+}';

    /**
     * @var IPageBlockMetaRepository|array
     */
    private $pageModuleRepository;
    /**
     * @var IPageCompletenessChecker
     */
    private $pageCompletenessChecker;
    /**
     * @var IPageBlockSerializerService
     */
    private $pageBlockSerializerService;
    /**
     * @var IPageRepository
     */
    private $pageRepository;

    /**
     * @param IPageRepository $pageRepository
     * @param IPageBlockMetaRepository $pageModuleRepository
     * @param IPageCompletenessChecker $pageCompletenessChecker
     * @param IPageBlockSerializerService $IPageBlockSerializerService
     */
    public function __construct(IPageRepository $pageRepository, IPageBlockMetaRepository $pageModuleRepository, IPageCompletenessChecker $pageCompletenessChecker, IPageBlockSerializerService $IPageBlockSerializerService)
    {
        $this->pageModuleRepository = $pageModuleRepository;
        $this->pageCompletenessChecker = $pageCompletenessChecker;
        $this->pageBlockSerializerService = $IPageBlockSerializerService;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $invalidFields = [];

        $page = $this->pageRepository->findById($id);
        if(!$page)
        {
            throw new NotFoundException();
        }

        if($this->isPostRequest())
        {
            $page->setName($this->getPostParameter('name'));
            $page->setUrl($this->getPostParameter('url'));
            $page->setHeadTitle($this->getPostParameter('headTitle'));
            $page->setMetaDescription($this->getPostParameter('metaDescription'));
            $page->setFocusWords($this->getPostParameter('focusWords'));
            $page->setAllowSeoIndex($this->getPostParameter('allowSeoIndex'));
            $page->setModifiedDate(new \DateTime());
            $page->setSeoCompleteness($this->pageCompletenessChecker->calculateCompletness($page));

            $pageBlocks = array();
            $pageBlocksAsArray = json_decode($this->getPostParameter('json'), true);

            foreach($pageBlocksAsArray as $pageBlockArray)
            {
                $pageBlock = $this->pageBlockSerializerService->fromArray($pageBlockArray);
                $pageBlock->setPage($page);
                $pageBlocks[] = $pageBlock;
            }

            $page->setPageBlocks($pageBlocks);

            if ($page->isValid())
            {
                try
                {
                    $this->pageRepository->save($page);
                    $this->getFlash()->success('Gegevens opgeslagen');
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

        $pageBlocksAsArray = [];
        foreach($page->getPageBlocks() as $pageBlock)
        {
            $pageBlocksAsArray[] = $this->pageBlockSerializerService->toArray($pageBlock);
        }

        return [
            'page' => $page,
            'json' => json_encode($pageBlocksAsArray),
            'invalidFields' => $invalidFields,
            'pageBuilderModules' => $this->pageModuleRepository->getAllPageBlockMetaInformation()
        ];
    }
}