<?php

namespace App\Action\Image;

use App\Model\Image\AbstractImage;
use claviska\SimpleImage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\TextResponse;
use Zend\Stratigility\MiddlewareInterface;

class ResizerAction implements MiddlewareInterface
{
    const RESIZE_TYPE_BEST_FIT = 'b';
    const RESIZE_TYPE_EXACT = 'e';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var array
     */
    private $allowedDimensions;

    /**
     * ResizerAction constructor.
     * @param EntityManagerInterface $entityManager
     * @param array $allowedDimensions
     */
    public function __construct(EntityManagerInterface $entityManager, array $allowedDimensions)
    {
        $this->entityManager = $entityManager;
        $this->allowedDimensions = $allowedDimensions;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     * @throws \Exception
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $imageId = $request->getAttribute('imageId');
        $resizeType = $request->getAttribute('resizeType');
        $width = $request->getAttribute('width');
        $height = $request->getAttribute('height');

        if ($this->isValidResizeType($resizeType) && $imageId && $width && $height)
        {
            if ($this->isValidDimension($width, $height))
            {
                /** @var AbstractImage $image */
                $image = $this->entityManager->find(AbstractImage::class, $imageId);

                $destinationFileName = $resizeType . '-' . $width . '-' . $height . '-' . $image->getFileName();

                if(!is_dir($image->getCacheFolderPath()))
                {
                    mkdir($image->getCacheFolderPath(), 0775, true);
                }

                $this->createImage($image, $width, $height, $resizeType, $image->getCacheFolderPath() . $destinationFileName);

                return new RedirectResponse($request->getUri());
            }
            else
            {
                return new TextResponse('Invalid dimensions', 400);
            }
        }
        else
        {
            return new TextResponse('Invalid arguments', 400);
        }
    }

    /**
     * @param $width
     * @param $height
     * @return bool
     * @throws \Exception
     */
    private function isValidDimension($width, $height)
    {
        foreach ($this->allowedDimensions as $dimension)
        {
            if(!isset($dimension[0]) || !isset($dimension[1]))
            {
                throw new \Exception('Invalid dimension configuration');
            }

            if($dimension[0] == $width && $dimension[1] == $height)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @param AbstractImage $image
     * @param $width
     * @param $height
     * @param $resizeType
     * @param $destination
     */
    private function createImage($image, $width, $height, $resizeType, $destination)
    {
        $simpleImage = new SimpleImage($image->getPath());

        if($resizeType == self::RESIZE_TYPE_EXACT)
        {
            $simpleImage->thumbnail($width, $height);
        }
        else
        {
            $simpleImage->bestFit($width, $height);
        }

        $simpleImage->toFile($destination, null, 100);
    }

    /**
     * @param $resizeType
     * @return bool
     */
    private function isValidResizeType($resizeType)
    {
        return in_array($resizeType, [
            self::RESIZE_TYPE_BEST_FIT,
            self::RESIZE_TYPE_EXACT
        ]);
    }
}