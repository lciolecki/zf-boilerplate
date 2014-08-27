<?php

namespace System\Controller\Plugin;

use Entity\User;

/**
 * ZF-Boilerplate module for check auhorization
 *
 * @category System
 * @package System\Controller
 * @subpackage System\Controller\Plugin
 * @copyright Copyright (c) 2014 Łukasz Ciołecki (lciolecki)
 */
class Authorization extends AbstractPlugin
{
    const USER_GUEST = 'guest';

    /**
     * @param \Zend_Controller_Request_Abstract $request
     * @throws \Zend_Controller_Action_Exception
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        $acl = $this->getAcl();
        $role = $this->getUserRole();

        if (!$acl->has($request->getModuleName()) && !$this->getAuthUser()) {
            $this->getSystemSession()->goto = $request->getParams();
            return $this->getRedirector()->gotoUrl($this->getView()->url(array(), 'login', true));
        } elseif (!$acl->has($request->getModuleName())) {
            throw new \Zend_Controller_Action_Exception(null, 404);
        }

        if (!$acl->isAllowed($role, $request->getModuleName(), $request->getControllerName())) {
            $this->getSystemSession()->goto = $request->getParams();
            return $this->getRedirector()->gotoUrl($this->getView()->url(array(), 'login', true));
        }

        return parent::preDispatch($request);
    }

    /**
     * Get user role name
     *
     * @return string
     */
    protected function getUserRole()
    {
        if ($this->getAuthUser() === null) {
            return self::USER_GUEST;
        }

        return $this->getAuthUser()->role;
    }

    /**
     * Get redirector
     *
     * @return \Zend_Controller_Action_Helper_Redirector
     */
    public function getRedirector()
    {
        return $this->getDi()->get('Zend_Controller_Action_Helper_Redirector');
    }

    /**
     * Get acl object
     *
     * @return \Zend_Acl
     */
    public function getAcl()
    {
        $acl = new \Zend_Acl();

        $acl->addRole(new \Zend_Acl_Role(self::USER_GUEST));
        $acl->addRole(new \Zend_Acl_Role(User::ROLE_ADMIN), self::USER_GUEST);

        $acl->addResource(new \Zend_Acl_Resource('default'));
        $acl->addResource(new \Zend_Acl_Resource('admin'));

        $acl->allow(self::USER_GUEST, 'default');
        $acl->allow(self::USER_GUEST, 'admin', 'login');

        $acl->allow(User::ROLE_ADMIN, 'admin');

        return $acl;
    }
}
