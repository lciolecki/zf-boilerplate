<?php

namespace System\Controller;

use System\Traits\DependencyInjection;

/**
 * ZF-Boilerplate module for checkout auhorization
 *
 * @category System
 * @package System\Controller
 * @copyright  Copyright (c) 2014 Łukasz Ciołecki (lciolecki)
 */
class Action extends \Zend_Controller_Action
{
    use DependencyInjection;

    /**
     * Instance of system
     *
     * @Inject
     * @var \Extlib\System
     */
    protected $system = null;

    /**
     * Instance of system cookie
     *
     * @Inject
     * @var \Extlib\System\Cookie
     */
    protected $cookie = null;

    /**
     * Instance of system session
     *
     * @Inject
     * @var \Zend_Session_Namespace
     */
    protected $session = null;

    /**
     * Instance of logger object
     *
     * @var \Zend_Log
     */
    protected $logger = null;

    /**
     * Alias for assemble  Zend_Controller_Action_Helper_Url
     *
     * @param array $urlOptions
     * @param string $name
     * @param boolean $reset
     * @param boolean $encode
     * @return string
     */
    protected function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $router = \Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }

    /**
     * Get auth user
     *
     * @return \stdClass
     */
    public function getAuthUser()
    {
        return $this->getDi()->get('System\AuthUser');
    }

    /**
     * Get system cookie
     *
     * @return \Extlib\System\Cookie
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Get logger
     *
     * @return \Zend_Log
     */
    public function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = \Zend_Registry::get('logger');
        }

        return $this->logger;
    }

    /**
     * Get session object
     *
     * @return \Zend_Session_Namespace
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Get system object
     *
     * @return \Extlib\System
     */
    public function getSystem()
    {
        return $this->system;
    }
}