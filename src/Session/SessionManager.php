<?php

namespace App\Session;

use App\Model\User;
use Zend\Session\Container;

class SessionManager implements ISessionManager
{
    private $_container;

    /**
     * SessionManager constructor.
     */
    public function __construct()
    {
        $this->_container = new Container();
    }

    /**
     * @param User $user
     */
    public function setActiveUser($user)
    {
        $this->set('user', $user);
    }

    /**
     * @return User
     */
    public function getActiveUser()
    {
        return $this->get('user');
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->_container->offsetGet($key);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->_container->offsetSet($key, $value);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return $this->_container->offsetExists($key);
    }

}