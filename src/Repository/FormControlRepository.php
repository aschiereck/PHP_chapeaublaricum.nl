<?php

namespace App\Repository;

use App\Model\FormControl;
use Doctrine\ORM\EntityManagerInterface;

class FormControlRepository implements IFormControlRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $_em;

    /**
     * UserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->_em = $entityManager;
    }

    /**
     * @param FormControl $formControl
     */
    public function delete(FormControl $formControl)
    {
        $this->_em->remove($formControl);
        $this->_em->flush();
    }


    /**
     * @param FormControl $formControl
     */
    public function save(FormControl $formControl)
    {
        $this->_em->persist($formControl);
        $this->_em->flush();
    }

    /**
     * @return int
     */
    public function getMaxPosition()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('max(f.position)')
            ->from(FormControl::class, 'f');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $id
     * @return FormControl|object
     */
    public function findById($id)
    {
        return $this->_em->find(FormControl::class, $id);
    }
}