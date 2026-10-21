<?php

namespace App\Action\Admin\Team;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\ITeamMemberRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/team/delete/{id:\d+}';

    /**
     * @var ITeamMemberRepository
     */
    private $teamMemberRepository;

    /**
     * IndexAction constructor.
     * @param ITeamMemberRepository $teamMemberRepository
     */
    public function __construct(ITeamMemberRepository $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $teamMember = $this->teamMemberRepository->findById($id);
        if(!$teamMember)
        {
            throw new NotFoundException();
        }

        $this->teamMemberRepository->delete($teamMember);

        $this->getFlash()->success('Teamlid verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}