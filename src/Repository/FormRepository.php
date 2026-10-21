<?php

namespace App\Repository;

use App\Model\Form;
use App\Model\Site;
use Doctrine\ORM\EntityManagerInterface;

class FormRepository implements IFormRepository
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
     * @param Form $form
     */
    public function delete(Form $form)
    {
        $this->_em->remove($form);
        $this->_em->flush();
    }


    /**
     * @param Form $form
     */
    public function save(Form $form)
    {
        $this->_em->persist($form);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return Form|object
     */
    public function findById($id)
    {
        return $this->_em->find(Form::class, $id);
    }

    /**
     * @return Form[]
     */
    public function findAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('form')
            ->from(Form::class, 'form');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site)
    {
        $forms = [];
        foreach($site->getForms() as $form)
        {
            $forms[$form->getId()] = $form->getName();
        }
        return $forms;
    }

    /**
     * @return int
     */
    public function countAll()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('count(form.id)')
            ->from(Form::class, 'form');

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}