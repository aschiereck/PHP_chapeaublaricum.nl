<?php

namespace App\Action\Admin;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\View\Model\ViewModel;

abstract class AbstractAdminTemplateAction extends AbstractAdminAction
{
    const DEFAULT_LAYOUT = 'admin/default';
    const LOGIN_LAYOUT = 'admin/login';
    const EMPTY_LAYOUT = 'admin/empty';

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
        $data = parent::__invoke($request, $response, $out = null);
        if ($data instanceof Response)
        {
            return $data;
        }

        return $this->createHtmlResponse(
            $this->layoutName,
            $this->getTemplate(),
            $data
        );
    }

    /**
     * @param string $layoutName
     * @param string $template
     * @param string $data
     * @return HtmlResponse
     */
    protected function createHtmlResponse($layoutName, $template, $data)
    {
        $layout = new ViewModel([
            'encoding' => 'utf-8',
        ]);
        $layout->setTemplate($layoutName);
        $layout->setVariable('viewModel', $this->getViewModel());

        $data['layout'] = $layout;
        $data['viewModel'] = $this->getViewModel();

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
                $template = strtolower('admin::' . $folder . '/' . $fileName . '.phtml');
            }
        }

        return $template;
    }
}