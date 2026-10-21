<?php

namespace App\Action\Admin\Project;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Exception\UniqueConstraintViolationException;
use App\Model\Project;
use App\Repository\Image\IProjectImageRepository;
use App\Repository\IPhotoAlbumRepository;
use App\Repository\IProjectRepository;
use App\Service\Seo\IProjectCompletenessChecker;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/project/[{id:\d+}]';
    /**
     * @var IProjectRepository
     */
    private $projectRepository;
    /**
     * @var IPhotoAlbumRepository
     */
    private $photoAlbumRepository;
    /**
     * @var IProjectCompletenessChecker
     */
    private $completenessChecker;
    /**
     * @var IProjectImageRepository
     */
    private $projectImageRepository;

    /**
     * DeleteAction constructor.
     * @param IProjectRepository $projectRepository
     * @param IPhotoAlbumRepository $photoAlbumRepository
     * @param IProjectCompletenessChecker $completenessChecker
     * @param IProjectImageRepository $projectImageRepository
     */
    public function __construct(
        IProjectRepository $projectRepository,
        IPhotoAlbumRepository $photoAlbumRepository,
        IProjectCompletenessChecker $completenessChecker,
        IProjectImageRepository $projectImageRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->photoAlbumRepository = $photoAlbumRepository;
        $this->completenessChecker = $completenessChecker;
        $this->projectImageRepository = $projectImageRepository;
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
            $project = $this->projectRepository->findById($id);
            if(!$project)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $project = new Project();
            $project->setSite($this->getSite());
            $project->setPosition($this->projectRepository->getMaxPosition() + 1);
        }

        if($this->isPostRequest())
        {
            $project->setName($this->getPostParameter('name'));
            $project->setUrl($this->getPostParameter('url'));
            $project->setSummary($this->getPostParameter('summary'));
            $project->setHeadTitle($this->getPostParameter('headTitle'));
            $project->setMetaDescription($this->getPostParameter('metaDescription'));
            $project->setContent($this->getPostParameter('content'));
            $project->setModifiedDate(new \DateTime());
            $project->setFocusWords($this->getPostParameter('focusWords'));
            $project->setSeoCompleteness($this->completenessChecker->calculateCompletness($project));

            $photoAlbumId = (int)$this->getPostParameter('photoAlbumId');
            $album = $this->photoAlbumRepository->findById($photoAlbumId);
            $project->setPhotoAlbum($album);

            $imageData = $this->getFilesParameter('image');
            $uploadResult = $this->projectImageRepository->createFromUpload($imageData);
            if ($uploadResult->isUploadedWithoutErrors())
            {
                $project->setImage($uploadResult->getImage());
            }
            else if ($uploadResult->isFileUploaded())
            {
                $invalidFields['image'] = $uploadResult->getValidationErrors();
            }


            if ($project->isValid() && (!$uploadResult->isFileUploaded() || $uploadResult->isValidImage()))
            {
                try
                {
                    $this->projectRepository->save($project);
                    $this->getFlash()->success('Gegevens opgeslagen');
                    return $this->redirectTo(IndexAction::ROUTE);
                }
                catch(UniqueConstraintViolationException $e)
                {
                    $invalidFields[$e->getFieldName()] = ['De waarde voor dit veld moet uniek zijn maar is al in gebruik door een andere site'];
                }
            }
            else
            {
                $invalidFields = array_merge(
                    $invalidFields,
                    $project->getValidationErrors()
                );
            }
        }

        return [
            'project' => $project,
            'invalidFields' => $invalidFields,
            'photoAlbums' => $this->photoAlbumRepository->findList($this->getSite())
        ];
    }
}