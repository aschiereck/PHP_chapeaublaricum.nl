<?php

namespace App\View\Frontend\Helper;

use App\View\Frontend\Model\ViewModel;
use Zend\View\Helper\AbstractHelper;

class MetaHelper extends AbstractHelper
{
    /**
     * @var LinkBuilder
     */
    private $linkBuilder;

    /**
     * MetaHelper constructor.
     * @param LinkBuilder $linkBuilder
     */
    public function __construct(LinkBuilder $linkBuilder)
    {
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * @param ViewModel $viewModel
     * @return string
     */
    public function __invoke(ViewModel $viewModel)
    {
        $metaHeaders = [
            'description' => $viewModel->metaDescription,
            'robots' => $viewModel->allowSeoIndex ? 'index, follow' : 'noindex, nofollow',
            'og:image' => $this->linkBuilder->getBaseUrl() . $viewModel->ogImageUrl
        ];

        $headers = '';

        foreach($metaHeaders as $name => $content)
        {
            $headers .= $this->createMetaTag($name, $content);
        }

        return $headers;
    }

    private function createMetaTag($name, $content)
    {
        return '<meta name="'.$name.'" content="'.htmlspecialchars($content).'">';
    }
}