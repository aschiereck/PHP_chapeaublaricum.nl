<?php
namespace App\Repository;

use App\Model\Form;
use App\Model\Site;

interface IFormRepository
{
    /**
     * @param Form $form
     */
    public function delete(Form $form);

    /**
     * @param Form $form
     */
    public function save(Form $form);

    /**
     * @param $id
     * @return Form
     */
    public function findById($id);

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site);
}