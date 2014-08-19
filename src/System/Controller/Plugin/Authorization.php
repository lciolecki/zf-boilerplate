<?php

namespace System\Controller\Plugin;

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
    const USER_ADMIN = 'admin';

    /**
     * Instance of Zend_Controller_Action_Helper_Redirector
     *
     * @var \Zend_Controller_Action_Helper_Redirector
     */
    protected $redirector = null;

    /**
     *  Instance of construct
     */
    public function __construct()
    {
        $this->redirector = new \Zend_Controller_Action_Helper_Redirector();
    }

    /**
     * @see \Zend_Controller_Plugin_Abstract::preDispatch()
     * @param \Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        $acl = $this->getAcl();
        $role = $this->getUserRole();

        if (!$acl->has($request->getModuleName()) && !$this->getAuthUser()) {
            $this->getSystemSession()->goto = $request->getParams();
            return $this->redirector->gotoUrl($this->getView()->url(array(), 'login', true));
        } elseif (!$acl->has($request->getModuleName())) {
            throw new \Zend_Controller_Action_Exception(null, 404);
        }

        if (!$acl->isAllowed($role, $request->getModuleName(), $request->getControllerName())) {
            $this->getSystemSession()->goto = $request->getParams();
            return $this->redirector->gotoUrl($this->getView()->url(array(), 'login', true));
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
     * Get acl object
     *
     * @return \Zend_Acl
     */
    public function getAcl()
    {
        $acl = new \Zend_Acl();

        $acl->addRole(new \Zend_Acl_Role(self::USER_GUEST));
        $acl->addRole(new \Zend_Acl_Role(self::USER_ADMIN), self::USER_GUEST);

        $acl->addResource(new \Zend_Acl_Resource('default'));
        $acl->addResource(new \Zend_Acl_Resource('admin'));

        $acl->allow(self::USER_GUEST, 'default');
        $acl->allow(self::USER_GUEST, 'admin', 'login');

        $acl->allow(self::USER_ADMIN, 'admin');

        return $acl;
    }
}
