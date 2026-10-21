<?php

namespace App\Action\Admin\Team;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\ITeamMemberRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\EmptyResponse;

class SortAction extends AbstractAdminAction
{
    const ROUTE = '/team/sort';

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
     * @throws \Exception
     */
    protected function execute()
    {
        $newPositions = $this->getPostParameter('position');
        $memberIds = array_map('intval', explode(',', $newPositions));
        $counter = 1;
        foreach($memberIds as $memberId)
        {
            $slide = $this->teamMemberRepository->findById($memberId);
            if($slide)
            {
                $slide->setPosition($counter);
                $this->teamMemberRepository->save($slide);
            }
            else
            {
                throw new \Exception('Unknown team member');
            }

            $counter++;
        }

        return new EmptyResponse();
    }
}