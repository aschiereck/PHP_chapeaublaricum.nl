<?php

namespace App\Action\Admin\Team;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\TeamMember;
use App\Repository\Image\ITeamMemberImageRepository;
use App\Repository\ITeamMemberRepository;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/team/edit/[/{id:\d+}]';

    /**
     * @var ITeamMemberRepository
     */
    private $teamMemberRepository;
    /**
     * @var ITeamMemberImageRepository
     */
    private $teamMemberImageRepository;

    /**
     * IndexAction constructor.
     * @param ITeamMemberRepository $teamMemberRepository
     * @param ITeamMemberImageRepository $teamMemberImageRepository
     */
    public function __construct(ITeamMemberRepository $teamMemberRepository, ITeamMemberImageRepository $teamMemberImageRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
        $this->teamMemberImageRepository = $teamMemberImageRepository;
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
            $teamMember = $this->teamMemberRepository->findById($id);
            $teamMember->setSite($this->getSite());
            if(!$teamMember)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $teamMember = new TeamMember();
            $teamMember->setPosition($this->teamMemberRepository->getMaxPosition() + 1);
        }

        if($this->isPostRequest())
        {
            $teamMember->setName($this->getPostParameter('name'));
            $teamMember->setFunction($this->getPostParameter('function'));
            $teamMember->setSummary($this->getPostParameter('summary'));
            $teamMember->setMoreInformationUrl($this->getPostParameter('moreInformationUrl'));
            $teamMember->setTwitterUrl($this->getPostParameter('twitterUrl'));
            $teamMember->setLinkedinUrl($this->getPostParameter('linkedinUrl'));
            $teamMember->setFlickerUrl($this->getPostParameter('flickerUrl'));

            $imageData = $this->getFilesParameter('image');
            $uploadResult = $this->teamMemberImageRepository->createFromUpload($imageData);
            if($uploadResult->isUploadedWithoutErrors())
            {
                $teamMember->setImage($uploadResult->getImage());
            }
            else if($uploadResult->isFileUploaded())
            {
                $invalidFields['image'] = $uploadResult->getValidationErrors();
            }

            if ($teamMember->isValid() && (!$uploadResult->isFileUploaded() || $uploadResult->isValidImage()))
            {
                $this->teamMemberRepository->save($teamMember);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(IndexAction::ROUTE);
            }
            else
            {
                $invalidFields = array_merge(
                    $invalidFields,
                    $teamMember->getValidationErrors()
                );
            }
        }

        return [
            'teamMember' => $teamMember,
            'invalidFields' => $invalidFields
        ];
    }
}