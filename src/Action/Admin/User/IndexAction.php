<?php

namespace App\Action\Admin\User;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Repository\IUserRepository;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/user';

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
     * @return array
     */
    public function execute()
    {
         return [
            'users' => $this->_userRepository->findAll()
        ];
    }
}