<?php

namespace App\Action\Frontend;

use App\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\View\Model\ViewModel;

abstract class AbstractFrontendTemplateAction extends AbstractFrontendAction
{
    const DEFAULT_LAYOUT = 'frontend/default';
    const EMPTY_LAYOUT = 'frontend/empty';

    /**
     * @var string
     */
    protected $layoutName = self::DEFAULT_LAYOUT;

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $template = $this->getTemplate();

        try
        {
            $data = parent::__invoke($request, $response, $out = null);
            if ($data instanceof Response)
            {
                return $data;
            }
        }
        catch(NotFoundException $e)
        {
            $template = 'error::404.phtml';
        }

        $viewModel = $this->getViewModel();

        $layout = new ViewModel([
            'encoding' => 'utf-8',
        ]);
        $layout->setTemplate($this->layoutName);
        $layout->setVariable('viewModel', $viewModel);
        $data['layout'] = $layout;
        $data['viewModel'] = $viewModel;

        return new HtmlResponse($this->getTemplateRenderer()->render($template, $data));
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getTemplate()
    {
        $template = '';
        $className = get_class($this);
        $parts = explode('\\', $className);

        if(count($parts) >= 2)
        {
            $parts = array_reverse($parts);
            $folder = $parts[1];

            $actionEndsWithAction = (substr($parts[0], -6) === 'Action');
            if($actionEndsWithAction)
            {
                $fileName = substr($parts[0], 0, strlen($parts[0]) - 6);
                $template = strtolower('frontend::' . $folder . '/' . $fileName . '.phtml');
            }
        }

        return $template;
    }
}