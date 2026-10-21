<?php

namespace App\Service\Serialization;

use App\Model\PageBlock\AbstractPageBlock;
use App\Repository\IPageBlockMetaRepository;
use App\Repository\IPageBlockRepository;
use App\Service\Serialization\PageBlock\IPageBlockSerializer;

class PageBlockSerializerService implements IPageBlockSerializerService
{
    /**
     * @var IPageBlockSerializerFactory
     */
    private $pageBlockSerializerFactory;
    /**
     * @var IPageBlockMetaRepository
     */
    private $pageModuleMetaRepository;
    /**
     * @var IPageBlockRepository
     */
    private $pageBlockRepository;

    /**
     * PageBlockSerializer constructor.
     * @param IPageBlockSerializerFactory $pageBlockSerializerFactory
     * @param IPageBlockMetaRepository $pageModuleMetaRepository
     * @param IPageBlockRepository $pageBlockRepository
     */
    public function __construct(IPageBlockSerializerFactory $pageBlockSerializerFactory, IPageBlockMetaRepository $pageModuleMetaRepository, IPageBlockRepository $pageBlockRepository)
    {
        $this->pageBlockSerializerFactory = $pageBlockSerializerFactory;
        $this->pageModuleMetaRepository = $pageModuleMetaRepository;
        $this->pageBlockRepository = $pageBlockRepository;
    }

    /**
     * @param AbstractPageBlock $pageBlock
     * @return string
     */
    public function toArray(AbstractPageBlock $pageBlock)
    {
        $serializer = $this->getSerializer(get_class($pageBlock));
        $values = $serializer->toArray($pageBlock);

        $moduleTag = $this->pageModuleMetaRepository->getModuleTagByModel($pageBlock);

        $values['id'] = $pageBlock->getId();
        $values['x'] = $pageBlock->getX();
        $values['y'] = $pageBlock->getY();
        $values['width'] = $pageBlock->getWidth();
        $values['pageBlockType'] = get_class($pageBlock);
        $values['editorTitle'] = $pageBlock->getEditorTitle();
        $values['moduleTag'] = $moduleTag;
        $values['template'] = $pageBlock->getTemplate();

        return $values;
    }

    /**
     * @param $values
     * @return AbstractPageBlock
     */
    public function fromArray($values)
    {
        $pageBlockType = isset($values['pageBlockType']) ? $values['pageBlockType'] : '';

        $pageBlock = null;
        $pageBlockId = (int)$values['id'];
        if($pageBlockId)
        {
            $pageBlock = $this->pageBlockRepository->findAbstractPageBlockById($values['id']);
        }

        $serializer = $this->getSerializer($pageBlockType);
        $pageBlock = $serializer->fromArray($values, $pageBlock);
        $pageBlock->setId($values['id']);
        $pageBlock->setX($values['x']);
        $pageBlock->setY($values['y']);
        $pageBlock->setWidth($values['width']);
        $pageBlock->setTemplate($values['template']);

        return $pageBlock;
    }

    /**
     * @param string $pageBlockType
     * @return IPageBlockSerializer
     */
    private function getSerializer($pageBlockType)
    {
        return $this->pageBlockSerializerFactory->getPageBlockSerializer($pageBlockType);
    }
}