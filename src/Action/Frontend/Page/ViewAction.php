<?php

namespace App\Action\Frontend\Page;

use App\Action\Frontend\AbstractFrontendTemplateAction;
use App\Repository\IPageBlockMetaRepository;
use App\Repository\IPageRepository;
use App\Exception\NotFoundException;

class ViewAction extends AbstractFrontendTemplateAction
{
    /**
     * @var IPageRepository
     */
    private $pageRepository;
    /**
     * @var IPageBlockMetaRepository
     */
    private $pageBlockMetaRepository;

    /**
     * @param IPageRepository $pageRepository
     * @param IPageBlockMetaRepository $pageBlockMetaRepository
     */
    public function __construct(IPageRepository $pageRepository, IPageBlockMetaRepository $pageBlockMetaRepository)
    {
        $this->pageRepository = $pageRepository;
        $this->pageBlockMetaRepository = $pageBlockMetaRepository;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $page = $this->getPage();

        $this->getViewModel()->headTitle = $page->getHeadTitle();
        $this->getViewModel()->metaDescription = $page->getMetaDescription();
        $this->getViewModel()->allowSeoIndex = $page->allowSeoIndex();
        $this->getViewModel()->activePage = $page;

        return[
            'page' => $page,
            'meta' => $this->pageBlockMetaRepository
        ];
    }

    /**
     * @return \App\Model\Page
     * @throws NotFoundException
     */
    private function getPage()
    {
        $pageSlug = $this->getRouteParameter('pageSlug', '');
        if($pageSlug == '/')
        {
            $pageSlug = '';
        }

        $page = $this->pageRepository->findPageByUrl($this->getSite(), $pageSlug);
        if(!$page)
        {
            throw new NotFoundException();
        }
        return $page;
    }
}