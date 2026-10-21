<?php

namespace App\View\Frontend\Model;

use App\View\Frontend\Helper\ArticleHelper;
use App\View\Frontend\Helper\CalendarHelper;
use App\View\Frontend\Helper\FormHelper;
use App\View\Frontend\Helper\LayoutBlockHelper;
use App\View\Frontend\Helper\LinkBuilder;
use App\View\Frontend\Helper\MenuHelper;
use App\View\ImageHelper;
use DateTime;
use Zend\View\Helper\HeadLink;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class View
 * @property HeadLink $headLink
 * @method MenuHelper menu()
 * @method LinkBuilder link()
 * @method string sitemap(string $url, DateTime $modified)
 * @method LayoutBlockHelper block()
 * @method FormHelper form()
 * @method string meta(ViewModel $viewmodel)
 * @method ArticleHelper article()
 * @method ImageHelper image()
 * @method CalendarHelper calendar()
 * @method string translate(string $toTranslate)
 */
class View extends PhpRenderer
{
    /** @var  ViewModel */
    public $viewModel;
}