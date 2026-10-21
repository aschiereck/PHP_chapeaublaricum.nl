<?php

namespace App\Service\Serialization;

use App\Model\PageBlock\PageBlockCalendar;
use App\Model\PageBlock\PageBlockCarousel;
use App\Model\PageBlock\PageBlockCustomTemplate;
use App\Model\PageBlock\PageBlockForm;
use App\Model\PageBlock\PageBlockNewsArchive;
use App\Model\PageBlock\PageBlockPhotoAlbum;
use App\Model\PageBlock\PageBlockProjectList;
use App\Model\PageBlock\PageBlockRecentArticles;
use App\Model\PageBlock\PageBlockSlider;
use App\Model\PageBlock\PageBlockTeam;
use App\Model\PageBlock\PageBlockUpcomingEvents;
use App\Repository\ICalendarRepository;
use App\Repository\ICarouselRepository;
use App\Repository\IFormRepository;
use App\Repository\ILinkRepository;
use App\Repository\Image\IPageBlockImageRepository;
use App\Repository\IPageRepository;
use App\Repository\IPhotoAlbumRepository;
use App\Repository\IProjectRepository;
use App\Repository\ISliderRepository;
use App\Service\Serialization\PageBlock\IPageBlockSerializer;
use App\Service\Serialization\PageBlock\PageBlockCalendarSerializer;
use App\Service\Serialization\PageBlock\PageBlockCarouselSerializer;
use App\Service\Serialization\PageBlock\PageBlockCustomTemplateSerializer;
use App\Service\Serialization\PageBlock\PageBlockFormSerializer;
use App\Service\Serialization\PageBlock\PageBlockNewsArchiveSerializer;
use App\Service\Serialization\PageBlock\PageBlockPhotoAlbumSerializer;
use App\Service\Serialization\PageBlock\PageBlockProjectListSerializer;
use App\Service\Serialization\PageBlock\PageBlockRecentArticlesSerializer;
use App\Service\Serialization\PageBlock\PageBlockSliderSerializer;
use App\Service\Serialization\PageBlock\PageBlockTeamSerializer;
use App\Service\Serialization\PageBlock\PageBlockUpcomingEventsSerializer;
use Exception;
use Interop\Container\ContainerInterface;

class PageBlockSerializerFactory implements IPageBlockSerializerFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PageBlockSerializerFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $pageBlockType
     * @return IPageBlockSerializer
     * @throws Exception
     */
    public function getPageBlockSerializer($pageBlockType)
    {
        switch ($pageBlockType)
        {
            case PageBlockSlider::class:
                return new PageBlockSliderSerializer(
                    $this->container->get(ISliderRepository::class)
                );
            case PageBlockForm::class:
                return new PageBlockFormSerializer(
                    $this->container->get(IFormRepository::class)
                );
            case PageBlockPhotoAlbum::class:
                return new PageBlockPhotoAlbumSerializer(
                    $this->container->get(IPhotoAlbumRepository::class)
                );
            case PageBlockRecentArticles::class:
                return new PageBlockRecentArticlesSerializer();
            case PageBlockTeam::class:
                return new PageBlockTeamSerializer();
            case PageBlockCustomTemplate::class:
                return new PageBlockCustomTemplateSerializer(
                    $this->container->get(ILinkRepository::class),
                    $this->container->get(IPageBlockImageRepository::class),
                    $this->container->get(IPageRepository::class)
                );
            case PageBlockNewsArchive::class:
                return new PageBlockNewsArchiveSerializer();
            case PageBlockProjectList::class:
                return new PageBlockProjectListSerializer(
                    $this->container->get(IProjectRepository::class)
                );
            case PageBlockCarousel::class:
                return new PageBlockCarouselSerializer(
                    $this->container->get(ICarouselRepository::class)
                );
            case PageBlockCalendar::class:
                return new PageBlockCalendarSerializer(
                    $this->container->get(ICalendarRepository::class)
                );
            case PageBlockUpcomingEvents::class:
                return new PageBlockUpcomingEventsSerializer();
            default:
                throw new Exception('Unknown pageBlock type: ' . get_class($pageBlockType));
        }
    }
}