<?php

namespace App\Action\Admin\Article;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Exception\UniqueConstraintViolationException;
use App\Model\Article;
use App\Model\Module;
use App\Repository\IArticleRepository;
use App\Repository\Image\IArticleImageRepository;
use App\Repository\IModuleRepository;
use App\Service\Seo\IArticleCompletenessChecker;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/article/edit[/{id:\d+}]';

    /**
     * @var IArticleRepository
     */
    private $articleRepository;
    /**
     * @var IArticleImageRepository
     */
    private $articleImageRepository;
    /**
     * @var IArticleCompletenessChecker
     */
    private $completenessChecker;
    /**
     * @var IModuleRepository
     */
    private $moduleRepository;

    /**
     * @param IArticleRepository $articleRepository
     * @param IArticleImageRepository $articleImageRepository
     * @param IArticleCompletenessChecker $completenessChecker
     * @param IModuleRepository $moduleRepository
     */
    public function __construct(
        IArticleRepository $articleRepository,
        IArticleImageRepository $articleImageRepository,
        IArticleCompletenessChecker $completenessChecker,
        IModuleRepository $moduleRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->articleImageRepository = $articleImageRepository;
        $this->completenessChecker = $completenessChecker;
        $this->moduleRepository = $moduleRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $invalidFields = [];

        if($id)
        {
            $article = $this->articleRepository->findById($id);
            if(!$article)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $article = new Article();
            $article->setSite($this->getSite());
        }
        if($this->isPostRequest())
        {
            $article->setName($this->getPostParameter('name'));
            $article->setUrl($this->getPostParameter('url'));
            $article->setHeadTitle($this->getPostParameter('headTitle'));
            $article->setMetaDescription($this->getPostParameter('metaDescription'));
            $article->setContent($this->getPostParameter('content'));
            $article->setSummary($this->getPostParameter('summary'));
            $article->setModifiedDate(new \DateTime());
            $article->setFocusWords($this->getPostParameter('focusWords'));
            $article->setSeoCompleteness($this->completenessChecker->calculateCompletness($article));

            $imageData = $this->getFilesParameter('articleImage');
            $uploadResult = $this->articleImageRepository->createFromUpload($imageData);
            if($uploadResult->isUploadedWithoutErrors())
            {
                $article->setArticleImage($uploadResult->getImage());
            }
            else if($uploadResult->isFileUploaded())
            {
                $invalidFields['articleImage'] = $uploadResult->getValidationErrors();
            }

            if(!$article->isPublished())
            {
                $article->setPublishDate(new \DateTime($this->getPostParameter('date_submit') . ' ' . $this->getPostParameter('time_submit')));
                $article->setPublishOnFacebook($this->getPostParameter('publish_on_facebook', false));
                $article->setPublishOnTwitter($this->getPostParameter('publish_on_twitter', false));
            }

            if ($article->isValid() && (!$uploadResult->isFileUploaded() || $uploadResult->isValidImage()))
            {
                try
                {
                    $this->articleRepository->save($article);
                    $this->getFlash()->success('Gegevens opgeslagen');
                    return $this->redirectTo(EditAction::ROUTE, ['id'=>$article->getId()]);
                }
                catch(UniqueConstraintViolationException $e)
                {
                    $invalidFields[$e->getFieldName()] = ['De waarde voor dit veld moet uniek zijn maar is al in gebruik door een andere site'];
                }
            }
            else
            {
                $invalidFields = array_merge($invalidFields, $article->getValidationErrors());
            }
        }

        return [
            'article' => $article,
            'invalidFields' => $invalidFields,
            'isFacebookActive' => $this->moduleRepository->isEnabled(Module::FACEBOOK),
            'isTwitterActive' => $this->moduleRepository->isEnabled(Module::TWITTER)
        ];
    }
}