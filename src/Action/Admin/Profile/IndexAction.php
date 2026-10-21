<?php

namespace App\Action\Admin\Profile;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Repository\IUserRepository;
use App\Util\Encryption\IPasswordEncryption;
use Psr\Http\Message\ResponseInterface as Response;

class IndexAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/profile';
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
     * @return Response|array
     */
    protected function execute()
    {
        if(!$this->getViewModel()->currentUser->getId())
        {
            $this->getFlash()->error('Deze actie is niet beschikbaar voor admin gebruikers');
            return $this->redirectTo(\App\Action\Admin\Dashboard\IndexAction::ROUTE);
        }

        $invalidFields = [];
        $user = $this->_userRepository->findById($this->getViewModel()->currentUser->getId());

        if($this->isPostRequest())
        {
            $currentPassword = $this->getPostParameter('currentPassword');
            $newPassword = $this->getPostParameter('newPassword');
            $repeatPassword = $this->getPostParameter('repeatPassword');
            $oldPassword = $user->getPassword();

            $user->setName($this->getPostParameter('name'));
            $user->setEmail($this->getPostParameter('email'));
            if($newPassword)
            {
                $user->setPassword($this->_passwordEncryption, $newPassword);
            }

            if(!$this->_passwordEncryption->isMatch($currentPassword, $oldPassword))
            {
                $this->getFlash()->error('Incorrect wachtwoord');
            }
            else if($newPassword != $repeatPassword)
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