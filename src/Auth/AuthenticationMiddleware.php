<?php

namespace App\Auth;

use App\Model\UserRole;
use App\Session\ISessionManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    const USER_ROLE_ATTRIBUTE = 'user_role_id';
    const USER_ATTRIBUTE = 'user';

    /**
     * @var ISessionManager
     */
    private $_sessionManager;

    /**
     * AuthMiddleware constructor.
     * @param ISessionManager $sessionManager
     */
    public function __construct(ISessionManager $sessionManager)
    {
        $this->_sessionManager = $sessionManager;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $user = $this->_sessionManager->getActiveUser();

        if($user)
        {
            $roleId = $user->getRoleId();
        }
        else
        {
            $roleId = UserRole::GUEST_ID;
        }

        $request = $request->withAttribute(self::USER_ATTRIBUTE, $user);
        $request = $request->withAttribute(self::USER_ROLE_ATTRIBUTE, $roleId);

        return $out($request, $response);
    }
}