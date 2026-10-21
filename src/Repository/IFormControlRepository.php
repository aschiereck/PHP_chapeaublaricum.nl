<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28-10-2016
 * Time: 10:23
 */
namespace App\Repository;

use App\Model\FormControl;

interface IFormControlRepository
{
    /**
     * @param FormControl $formControl
     */
    public function delete(FormControl $formControl);

    /**
     * @param FormControl $formControl
     */
    public function save(FormControl $formControl);

    /**
     * @return int
     */
    public function getMaxPosition();

    /**
     * @param $id
     * @return FormControl
     */
    public function findById($id);
}