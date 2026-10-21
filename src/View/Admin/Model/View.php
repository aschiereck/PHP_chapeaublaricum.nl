<?php

namespace App\View\Admin\Model;

use App\View\Admin\Helper\FormHelper;
use App\View\ImageHelper;
use Zend\View\Helper\HeadLink;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class View
 * @property HeadLink $headLink
 * @method FormHelper Form()
 * @method string adminUrl(string $routeName = null, array $routeParams = [], array $getParams = [])
 * @method string adminMenu($menu)
 * @method string __($string)
 * @method ImageHelper image()
 */
class View extends PhpRenderer
{
    /** @var  ViewModel */
    public $viewModel;
}