<?php

namespace App\Action\Evolved;

use App\Config\IEvolvedConfigProvider;
use App\Repository\IModuleRepository;
use App\Service\Facebook\IFacebookService;
use App\Service\GoogleAnalytics\IGoogleAnalyticsService;
use App\Service\Twitter\ITwitterService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Stratigility\MiddlewareInterface;

class InfoAction implements MiddlewareInterface
{
    const ROUTE = '/beheer/evolved/info';
    /**
     * @var IFacebookService
     */
    private $facebookService;
    /**
     * @var ITwitterService
     */
    private $twitterService;
    /**
     * @var IGoogleAnalyticsService
     */
    private $googleAnalyticsService;
    /**
     * @var IModuleRepository
     */
    private $moduleRepository;
    /**
     * @var IEvolvedConfigProvider
     */
    private $configProvider;

    /**
     * StatusAction constructor.
     * @param IFacebookService $facebookService
     * @param ITwitterService $twitterService
     * @param IGoogleAnalyticsService $googleAnalyticsService
     * @param IEvolvedConfigProvider $configProvider
     * @param IModuleRepository $moduleRepository
     */
    public function __construct(
        IFacebookService $facebookService,
        ITwitterService $twitterService,
        IGoogleAnalyticsService $googleAnalyticsService,
        IEvolvedConfigProvider $configProvider,
        IModuleRepository $moduleRepository
    )
    {
        $this->facebookService = $facebookService;
        $this->twitterService = $twitterService;
        $this->googleAnalyticsService = $googleAnalyticsService;
        $this->moduleRepository = $moduleRepository;
        $this->configProvider = $configProvider;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        return new JsonResponse([
            'versions' => [
                'Facebook Graph' => $this->facebookService->getVersion(),
                'Twitter OAuth' => $this->twitterService->getVersion(),
                'Google Analytics' => $this->googleAnalyticsService->getVersion(),
                'Evolved' => $this->configProvider->getEvolvedConfig()->getVersion()
            ],
            'modules' => $this->moduleRepository->getActiveModules()
        ]);
    }
}