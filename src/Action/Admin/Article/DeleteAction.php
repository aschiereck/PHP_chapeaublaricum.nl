<?php

namespace App\Action\Admin\Article;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\IArticleRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/article/delete/{id:\d+}';

    /**
     * @var IArticleRepository
     */
    private $articleRepository;

    /**
     * @param IArticleRepository $articleRepository
     */
    public function __construct(IArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $article = $this->articleRepository->findById($id);
        if(!$article)
        {
            throw new NotFoundException();
        }

        $this->articleRepository->delete($article);

        $this->getFlash()->success('Nieuwsbericht verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}