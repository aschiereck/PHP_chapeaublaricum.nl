<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 17-10-2016
 * Time: 14:16
 */
namespace App\Repository;

use App\Model\User;

interface IUserRepository
{
    /**
     * @param User $user
     */
    public function delete(User $user);

    /**
     * @param User $user
     */
    public function save(User $user);

    /**
     * @param $id
     * @return User
     */
    public function findById($id);

    /**
     * @param string $email
     * @return User
     */
    public function findByEmail($email);

    /**
     * @return User[]
     */
    public function findAll();
}