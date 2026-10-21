<?php

namespace App\Auth;

use App\Action\Admin\Auth\LoginAction;
use App\Action\Admin\Branding\CssAction;
use App\Model\UserRole;
use Zend\Permissions\Acl\Role\GenericRole as Role;

class Acl extends \Zend\Permissions\Acl\Acl
{
    public function __construct()
    {
        $this->_initResources();
        $this->_initRoles();
        $this->_initPermissions();
    }

    private function _initRoles()
    {
        $this->addRole(new Role(UserRole::GUEST_ID));
        $this->addRole(new Role(UserRole::ADMIN_ID));
        $this->addRole(new Role(UserRole::DEVELOPER_ID));
    }

    private function _initResources()
    {
        $this->addResource(LoginAction::ROUTE);
        $this->addResource(CssAction::ROUTE);
    }

    private function _initPermissions()
    {
        $this->allow(UserRole::DEVELOPER_ID);
        $this->allow(UserRole::ADMIN_ID);

        //Guest
        $this->allow(UserRole::GUEST_ID, LoginAction::ROUTE);
        $this->allow(UserRole::GUEST_ID, CssAction::ROUTE);
    }
}