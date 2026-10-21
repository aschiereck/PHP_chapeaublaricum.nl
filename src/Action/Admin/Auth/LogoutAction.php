<?php

namespace App\Action\Admin\Auth;

use App\Action\Admin\AbstractAdminAction;
use App\Session\ISessionManager;
use Zend\Diactoros\Response\RedirectResponse;

class LogoutAction extends AbstractAdminAction
{
    const ROUTE = '/auth/logout';

    /**
     * @var ISessionManager
     */
    private $_sessionManager;

    /**
     * @param ISessionManager $sessionManager
     */
    public function __construct( ISessionManager $sessionManager)
    {
        $this->_sessionManager = $sessionManager;
    }

    /**
     * @return RedirectResponse
     */
    public function execute()
    {
        $this->_sessionManager->setActiveUser(null);
        return new RedirectResponse(LoginAction::ROUTE);
    }
}