<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 17-10-2016
 * Time: 23:35
 */
namespace App\Session;

use App\Model\User;

interface ISessionManager
{
    /**
     * @param User $user
     */
    public function setActiveUser($user);

    /**
     * @return User
     */
    public function getActiveUser();


    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param $value
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @return bool
     */
    public function exists($key);
}