<?php

namespace System\Controller\Plugin;

/**
 * ZF-Boilerplate system base zf controller plugin
 *
 * @category System
 * @package System_Controller
 * @subpackage System_Controller_Plugin
 * @copyright  Copyright (c) 2013 Łukasz Ciołecki (lciolecki)
 */
class AbstractPlugin extends \Zend_Controller_Plugin_Abstract
{
    /**
     * Default cache data namespace
     */
    const CACHE_ID = 'data';

    /**
     * System session namespace
     */
    const SESSION_NAMESPACE = 'system-session';

    /**
     * Instance of view object
     *
     * @var Zend_View
     */
    protected $view = null;

    /**
     * Instance of cache object
     *
     * @var \Zend_Cache_Core
     */
    protected $cache = null;

    /**
     * Instance of system object
     *
     * @var \Extlib\System
     */
    protected $system = null;

    /**
     * Instance of session
     *
     * @var \Zend_Session_Namespace
     */
    protected $session = null;

    /**
     * Instance of system cookie
     *
     * @var \Extlib\System\Cookie
     */
    protected $cookie = null;

    /**
     * Instance of auth user
     *
     * @var \stdClass
     */
    protected $user = null;

    /**
     * Instance of construct
     */
    public function __construct()
    {
        $this->session = new \Zend_Session_Namespace(self::SESSION_NAMESPACE);
        $this->system = \Extlib\System::getInstance();
        $this->cookie = new \Extlib\System\Cookie();

        if (\Zend_Auth::getInstance()->hasIdentity()) {
            $this->user = \Zend_Auth::getInstance()->getIdentity();
        }
    }

    /**
     * Get view
     *
     * @return \Zend_View
     */
    public function getView()
    {
        if (null === $this->view) {
            $this->view = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        }

        return $this->view;
    }

    /**
     * Get cache
     *
     * @return \Zend_Cache_Core
     */
    public function getCache()
    {
        if ($this->cache === null) {
            $this->cache = \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cachemanager')->getCache(self::CACHE_ID);
        }

        return $this->cache;
    }

    /**
     * Set cache
     *
     * @param \Zend_Cache_Core $cache
     * @return \System\Controller\Plugin\Locale
     */
    public function setCace(\Zend_Cache_Core $cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * Set system object
     *
     * @param \Extlib\System $system
     * @return \System\Controller\Plugin\Locale
     */
    public function setSystem($system)
    {
        $this->system = $system;
        return $this;
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

    /**
     * Set Cookie object
     *
     * @param \Extlib\System\Cookie $cookie
     * @return \System\Controller\Plugin\Locale
     */
    public function setCookie(\Extlib\System\Cookie $cookie)
    {
        $this->cookie = $cookie;
        return $this;
    }

    /**
     * Get Cookieobject
     *
     * @return \Extlib\System\Cookie
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Set session object
     *
     * @param \Zend_Session_Namespace $session
     * @return \System\Controller\Plugin\Locale
     */
    public function setSession(\Zend_Session_Namespace $session)
    {
        $this->session = $session;
        return $this;
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
     * Set auth user
     *
     * @param \stdClass $user
     * @return \System\Controller\Plugin\Locale
     */
    public function setUser(\stdClass $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get auth user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }
} 