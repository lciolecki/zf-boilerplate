<?php

namespace System\Controller\Plugin;

use System\Controller\Plugin\AbstractPlugin;

/**
 * ZF-Boilerplate module for checkout auhorization
 *
 * @category System
 * @package System\Controller
 * @subpackage System\Controller\Plugin
 * @copyright  Copyright (c) 2013 Łukasz Ciołecki (lciolecki)
 */
class Authorization extends AbstractPlugin
{
    /**
     * Instance of Zend_Controller_Action_Helper_Redirector
     *
     * @var Zend_Controller_Action_Helper_Redirector
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
     * (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::preDispatch()
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        $role = 'guest';
        if ($this->getAuthUser()) {
            $role = 'user';
        }

        $acl = $this->getAcl();
        if (!$acl->has($request->getModuleName()) && !$this->getAuthUser()) {
            return $this->redirector->gotoUrl($this->getView()->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), 'default', true));
        } elseif (!$acl->has($request->getModuleName())) {
            throw new \Zend_Controller_Action_Exception(null, 404);
        }


        if (!$acl->isAllowed($role, $request->getModuleName(), $request->getControllerName())) {
            $this->getSystemSession()->goto = $request->getParams();
            return $this->redirector->gotoUrl($this->getView()->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'index'), 'default', true));
        }
    }

    /**
     * Get acl object
     *
     * @return \Zend_Acl
     */
    public function getAcl()
    {
        $acl = new \Zend_Acl();

        $acl->addRole(new \Zend_Acl_Role('guest'));
        $acl->addRole(new \Zend_Acl_Role('user'), 'guest');
        $acl->addResource(new \Zend_Acl_Resource('default'));
        $acl->addResource(new \Zend_Acl_Resource('admin'));
        $acl->allow('guest', 'default');
        $acl->allow('guest', 'admin', 'login');
        $acl->allow('user', 'admin');

        return $acl;
    }
}
