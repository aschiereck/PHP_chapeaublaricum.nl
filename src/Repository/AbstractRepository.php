<?php

namespace App\Repository;

use App\Exception\UniqueConstraintViolationException as LocalException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $_em;

    /**
     * AdminRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_em = $entityManager;
    }

    /**
     * @param $entity
     * @throws UniqueConstraintViolationException
     * @throws \Exception
     */
    protected function saveInternal($entity)
    {
        try
        {
            $this->_em->persist($entity);
            $this->_em->flush();
        }
        catch(UniqueConstraintViolationException $e)
        {
            if(preg_match( "%for key '(?P<key>.+)'%", $e->getMessage(), $match ) )
            {
                //remove idx or udx from field key
                $parts = explode('_', $match[ 'key' ]);

                $exception = new LocalException($e, $parts[0]);
                throw $exception;
            }

            throw $e;
        }
    }
}