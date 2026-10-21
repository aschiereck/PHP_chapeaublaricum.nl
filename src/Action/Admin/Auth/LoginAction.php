<?php

namespace App\Action\Admin\Auth;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Action\Admin\Dashboard\IndexAction;
use App\Util\Encryption\IPasswordEncryption;
use App\Repository\IUserRepository;
use App\Service\Evolved\IApi;
use App\Session\ISessionManager;
use App\View\Admin\Model\Menu;

class LoginAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/';

    /**
     * @var IUserRepository
     */
    private $_userRepository;
    /**
     * @var ISessionManager
     */
    private $_sessionManager;
    /**
     * @var IPasswordEncryption
     */
    private $_passwordEncryption;
    /**
     * @var IApi
     */
    private $api;

    /**
     * @param IUserRepository $userRepository
     * @param ISessionManager $sessionManager
     * @param IPasswordEncryption $passwordEncryption
     * @param IApi $api
     */
    public function __construct(
        IUserRepository $userRepository,
        ISessionManager $sessionManager,
        IPasswordEncryption $passwordEncryption,
        IApi $api
    )
    {
        $this->layoutName = AbstractAdminTemplateAction::LOGIN_LAYOUT;
        $this->_userRepository = $userRepository;
        $this->_sessionManager = $sessionManager;
        $this->_passwordEncryption = $passwordEncryption;
        $this->api = $api;
    }

    /**
     * @return array|\Zend\Diactoros\Response\RedirectResponse
     */
    public function execute()
    {
        if($this->isPostRequest())
        {
            $email = $this->getPostParameter('email');
            $password = $this->getPostParameter('password');

            if($email && $password)
            {
                $user = $this->_userRepository->findByEmail($email);

                if ($user && $this->_passwordEncryption->isMatch($password, $user->getPassword()))
                {
                    if($this->api->verifyHost($_SERVER['HTTP_HOST']))
                    {
                        $user->setLastLogin(new \DateTime());
                        $this->_userRepository->save($user);
                        $this->_sessionManager->setActiveUser($user);
                        return $this->redirectTo(IndexAction::ROUTE);
                    }
                }
            }

            $this->getFlash()->error('Geen toegang');
        }

        $branding = $this->api->getBranding();

        return [
            'logoUrl' => $branding->getLoginLogoUrl(),
            'loginText' => $branding->getLoginText()
        ];
    }

    /**
     * @return string
     */
    protected function getTitle()
    {
        return 'Login';
    }

    /**
     * @return string
     */
    protected function getActiveMenuItem()
    {
        return Menu::MENU_DASHBOARD;
    }
}