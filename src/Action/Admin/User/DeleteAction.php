<?php

namespace App\Action\Admin\User;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\IUserRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/user/delete/{id:\d+}';

    /**
     * @var IUserRepository
     */
    private $_userRepository;

    /**
     * @param IUserRepository $userRepository
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->_userRepository = $userRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $user = $this->_userRepository->findById($id);
        if(!$user)
        {
            throw new NotFoundException();
        }

        $this->_userRepository->delete($user);

        $this->getFlash()->success('Gebruiker verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}