<?php

namespace App\Action\Admin\User;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\User;
use App\Model\UserRole;
use App\Repository\IUserRepository;
use App\Util\Encryption\IPasswordEncryption;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/user/edit/[/{id:\d+}]';
    /**
     * @var IUserRepository
     */
    private $_userRepository;
    /**
     * @var IPasswordEncryption
     */
    private $_passwordEncryption;

    /**
     * @param IUserRepository $userRepository
     * @param IPasswordEncryption $passwordEncryption
     */
    public function __construct(IUserRepository $userRepository, IPasswordEncryption $passwordEncryption)
    {
        $this->_userRepository = $userRepository;
        $this->_passwordEncryption = $passwordEncryption;
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
            $user = $this->_userRepository->findById($id);
            if(!$user)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $user = new User();
            $user->setRoleId(UserRole::ADMIN_ID);
        }

        if($this->isPostRequest())
        {
            $user->setName($this->getPostParameter('name'));
            $user->setEmail($this->getPostParameter('email'));

            if($this->getPostParameter('password'))
            {
                $user->setPassword($this->_passwordEncryption, $this->getPostParameter('password'));
            }

            if($this->getPostParameter('password') != $this->getPostParameter('passwordRepeat'))
            {
                $this->getFlash()->error('De wachtwoorden zijn niet gelijk');
            }
            else if ($user->isValid())
            {
                $this->_userRepository->save($user);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(IndexAction::ROUTE);
            }
            else
            {
                $invalidFields = $user->getValidationErrors();
            }
        }

        return [
            'user' => $user,
            'invalidFields' => $invalidFields
        ];
    }
}