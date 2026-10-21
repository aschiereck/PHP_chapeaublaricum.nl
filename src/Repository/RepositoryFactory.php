<?php

namespace App\Repository;

use App\Repository\Image\ArticleImageRepository;
use App\Repository\Image\CarouselImageRepository;
use App\Repository\Image\IArticleImageRepository;
use App\Repository\Image\ICarouselImageRepository;
use App\Repository\Image\IPageBlockImageRepository;
use App\Repository\Image\IPhotoImageRepository;
use App\Repository\Image\IProjectImageRepository;
use App\Repository\Image\IRichTextImageRepository;
use App\Repository\Image\ISlideImageRepository;
use App\Repository\Image\ITeamMemberImageRepository;
use App\Repository\Image\PageBlockImageRepository;
use App\Repository\Image\PhotoImageRepository;
use App\Repository\Image\ProjectImageRepository;
use App\Repository\Image\RichTextImageRepository;
use App\Repository\Image\SlideImageRepository;
use App\Repository\Image\TeamMemberImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class RepositoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return object
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(EntityManagerInterface::class);
        $config = $container->get('config');

        switch($requestedName)
        {
            case ISiteRepository::class :
                return new SiteRepository($entityManager);
            case IProjectRepository::class:
                return new ProjectRepository($entityManager);
            case IUserRepository::class:
                return new UserRepository($entityManager);
            case ISliderRepository::class:
                return new SliderRepository($entityManager);
            case ISlideRepository::class:
                return new SlideRepository($entityManager);
            case IPageRepository::class:
                return new PageRepository($entityManager);
            case ILayoutTextBlockRepository::class:
                return new LayoutTextBlockRepository($entityManager);
            case IAttachmentRepository::class:
                return new AttachmentRepository($entityManager);
            case IMenuItemRepository::class:
                return new MenuItemRepository($entityManager);
            case IArticleRepository::class:
                return new ArticleRepository($entityManager);
            case IFormRepository::class:
                return new FormRepository($entityManager);
            case IFormControlRepository::class :
                return new FormControlRepository($entityManager);
            case IPageBlockRepository::class :
                return new PageBlockRepository($entityManager);
            case ILinkRepository::class :
                return new LinkRepository($entityManager);
            case IModuleRepository::class :
                return new ModuleRepository(
                    $config['active_modules']
                );
            case IPageBlockMetaRepository::class :
                return new PageBlockMetaRepository(
                    $container->get(IModuleRepository::class),
                    $container->get(ICustomBlockTemplateRepository::class),
                    $config['page_blocks']
                );
            case ICustomBlockTemplateRepository::class:
                return new CustomBlockTemplateRepository(
                    isset($config['custom_blocks']) ? $config['custom_blocks'] : []
                );
            case ISiteSettingRepository::class :
                return new SiteSettingRepository($entityManager);
            case IPhotoAlbumRepository::class :
                return new PhotoAlbumRepository($entityManager);
            case IPhotoRepository::class :
                return new PhotoRepository($entityManager);
            case ITeamMemberRepository::class :
                return new TeamMemberRepository($entityManager);
            case ICarouselRepository::class :
                return new CarouselRepository($entityManager);
            case ICarouselItemRepository::class :
                return new CarouselItemRepository($entityManager);
            case ICalendarRepository::class :
                return new CalendarRepository($entityManager);
            //Images
            case ISlideImageRepository::class:
                return new SlideImageRepository($entityManager);
            case IRichTextImageRepository::class:
                return new RichTextImageRepository($entityManager);
            case IArticleImageRepository::class :
                return new ArticleImageRepository($entityManager);
            case IPhotoImageRepository::class :
                return new PhotoImageRepository($entityManager);
            case IPageBlockImageRepository::class :
                return new PageBlockImageRepository($entityManager);
            case ITeamMemberImageRepository::class :
                return new TeamMemberImageRepository($entityManager);
            case ICarouselImageRepository::class :
                return new CarouselImageRepository($entityManager);
            case IProjectImageRepository::class :
                return new ProjectImageRepository($entityManager);
            default :
                throw new \Exception('Unknown repository: ' . $requestedName);
        }
    }
}