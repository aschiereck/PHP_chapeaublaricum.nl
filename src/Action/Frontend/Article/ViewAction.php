<?php

namespace App\Action\Frontend\Article;

use App\Action\Frontend\AbstractFrontendTemplateAction;
use App\Repository\IArticleRepository;
use App\Exception\NotFoundException;
use App\View\ImageHelper;

class ViewAction extends AbstractFrontendTemplateAction
{
    /**
     * @var IArticleRepository
     */
    private $articleRepository;
    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @param IArticleRepository $articleRepository
     * @param ImageHelper $imageHelper
     */
    public function __construct(IArticleRepository $articleRepository, ImageHelper $imageHelper)
    {
        $this->articleRepository = $articleRepository;
        $this->imageHelper = $imageHelper;
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function execute()
    {
        $articleUrl = $this->getRouteParameter('articleSlug');
        $article = $this->articleRepository->getArticleByUrl($this->getSite(), $articleUrl);
        if(!$article)
        {
            throw new NotFoundException();
        }

        $this->getViewModel()->headTitle = $article->getHeadTitle();
        $this->getViewModel()->metaDescription = $article->getMetaDescription();
        $this->getViewModel()->allowSeoIndex = true;
        $this->getViewModel()->ogImageUrl = $this->imageHelper->getUrl($article->getArticleImage());
        $this->getViewModel()->activeArticle = $article;

        return [
            'article' => $article
        ];
    }
}