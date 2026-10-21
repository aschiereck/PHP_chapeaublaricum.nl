<?php

namespace App\Auth;

use App\Action\Admin\Auth\LoginAction;
use App\Middleware\Admin\SiteMiddleware;
use App\Model\UserRole;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouteResult;
use Zend\Permissions\Acl\AclInterface;
use Zend\Stratigility\MiddlewareInterface;

class AuthorizationMiddleware implements MiddlewareInterface
{

    /**
     * @var AclInterface
     */
    private $_acl;

    /**
     * AuthorizationMiddleWare constructor.
     * @param AclInterface $acl
     */
    public function __construct(AclInterface $acl)
    {
        $this->_acl = $acl;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param callable|null $out
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $userRole = $request->getAttribute(AuthenticationMiddleware::USER_ROLE_ATTRIBUTE);
        $resourceName = '*unknown*';

        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        if($routeResult)
        {
            $resourceName = $routeResult->getMatchedRouteName();
        }

        if($userRole == UserRole::ADMIN_ID || $userRole == UserRole::DEVELOPER_ID)
        {
            $isAllowed = true;
        }
        else
        {
            try
            {
                $isAllowed = $this->_acl->isAllowed($userRole, $resourceName);
            }
            //Zend ACL throws an InvalidArgumentException when a resource is not defined
            catch (\InvalidArgumentException $e)
            {
                $isAllowed = false;
            }
        }

        if(!$isAllowed)
        {
            return new RedirectResponse('/'.ADMIN_SUFFIX . '/' . $request->getAttribute(SiteMiddleware::SITE_ID) . LoginAction::ROUTE);
        }

        return $out($request, $response);
    }
}