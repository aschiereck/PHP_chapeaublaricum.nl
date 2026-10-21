<?php

namespace App\Action\Evolved;

use App\Action\Admin\Dashboard\IndexAction;
use App\Model\User;
use App\Model\UserRole;
use App\Repository\ISiteRepository;
use App\Service\Evolved\IApi;
use App\Session\IFlashMessageHandler;
use App\Session\ISessionManager;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Stratigility\MiddlewareInterface;

class RemoteLoginAction implements MiddlewareInterface
{
    const ROUTE = '/beheer/evolved/remoteLogin/{token}';
    /**
     * @var IApi
     */
    private $api;
    /**
     * @var ISessionManager
     */
    private $sessionManager;
    /**
     * @var IFlashMessageHandler
     */
    private $flashMessageHandler;
    /**
     * @var ISiteRepository
     */
    private $siteRepository;

    /**
     * RemoteLoginAction constructor.
     * @param IApi $api
     * @param ISessionManager $sessionManager
     * @param IFlashMessageHandler $flashMessageHandler
     * @param ISiteRepository $siteRepository
     */
    public function __construct(IApi $api, ISessionManager $sessionManager, IFlashMessageHandler $flashMessageHandler, ISiteRepository $siteRepository)
    {
        $this->api = $api;
        $this->sessionManager = $sessionManager;
        $this->flashMessageHandler = $flashMessageHandler;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return RedirectResponse|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $token = $request->getAttribute('token');

        if($this->api->validateToken($token))
        {
            $user = new User();
            $user->setName('Admin');
            $user->setRoleId(UserRole::ADMIN_ID);

            $this->sessionManager->setActiveUser($user);
        }
        else
        {
            $this->flashMessageHandler->error('Inloggen mislukt');
        }

        $firstSite = $this->siteRepository->findFirstSite();
        return new RedirectResponse('/' . ADMIN_SUFFIX  . '/' . $firstSite->getId() . IndexAction::ROUTE);
    }
}