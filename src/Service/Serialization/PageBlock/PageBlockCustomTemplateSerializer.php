<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\Link\ExternalLink;
use App\Model\Link\PageLink;
use App\Model\PageBlock\PageBlockCustomTemplate;
use App\Repository\ILinkRepository;
use App\Repository\Image\IPageBlockImageRepository;
use App\Repository\IPageRepository;

class PageBlockCustomTemplateSerializer implements IPageBlockSerializer
{
    /**
     * @var ILinkRepository
     */
    private $linkRepository;
    /**
     * @var IPageBlockImageRepository
     */
    private $pageBlockImageRepository;
    /**
     * @var IPageRepository
     */
    private $pageRepository;

    /**
     * @param ILinkRepository $linkRepository
     * @param IPageBlockImageRepository $pageBlockImageRepository
     * @param IPageRepository $pageRepository
     */
    public function __construct(ILinkRepository $linkRepository, IPageBlockImageRepository $pageBlockImageRepository, IPageRepository $pageRepository)
    {
        $this->linkRepository = $linkRepository;
        $this->pageBlockImageRepository = $pageBlockImageRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param PageBlockCustomTemplate $pageBlock
     * @return string
     * @throws \Exception
     */
    public function toArray($pageBlock)
    {
        $data = [
            'text'=> $pageBlock->getText(),
            'richtext' => $pageBlock->getContent(),
            'imageId' => $pageBlock->getImage() ? $pageBlock->getImage()->getId() : null
        ];


        if($pageBlock->getLink())
        {
            if($pageBlock->getLink()->getId())
            {
                $data['linkId'] = $pageBlock->getLink()->getId();
            }
            else if(get_class($pageBlock->getLink()) == PageLink::class)
            {
                /** @var PageLink $pageLink */
                $pageLink = $pageBlock->getLink();
                $data['linkType'] = $pageLink->getType();
                $data['linkPageId'] = $pageLink->getPage()->getId();
            }
            else if(get_class($pageBlock->getLink()) == ExternalLink::class)
            {
                /** @var ExternalLink $externalLink */
                $externalLink = $pageBlock->getLink();
                $data['linkType'] = $externalLink->getType();
                $data['linkUrl'] = $externalLink->getUrl();
            }
            else
            {
                throw new \Exception('Unknown link configuration');
            }
        }

        return $data;
    }

    /**
     * @param array $values
     * @param PageBlockCustomTemplate $pageBlock
     * @return PageBlockCustomTemplate $pageBlock
     * @throws \Exception
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockCustomTemplate();
        $link = null;

        if(isset($values['linkId']))
        {
            $link = $this->linkRepository->findById($values['linkId']);
        }
        else if(isset($values['linkType']))
        {
            switch($values['linkType'])
            {
                case ExternalLink::TYPE:
                    $link = new ExternalLink();
                    $link->setUrl($values['linkUrl']);
                    break;
                case PageLink::TYPE:
                    $link = new PageLink();
                    $pageId = $values['linkPageId'];
                    $page = $this->pageRepository->findById($pageId);
                    $link->setPage($page);
                    break;
                default:
                    throw new \Exception('Unknown link type');
            }
        }

        $imageId = (int)$values['imageId'];
        $text = $values['text'];
        $richText = $values['richtext'];
        $template = $values['template'];

        if($imageId)
        {
            $pageBlock->setImage($this->pageBlockImageRepository->findById($imageId));
        }

        $pageBlock->setLink($link);
        $pageBlock->setText($text);
        $pageBlock->setContent($richText);
        $pageBlock->setTemplate($template);

        return $pageBlock;
    }
}
