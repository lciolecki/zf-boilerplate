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
     * Get view object
     *
     * @return \Zend_View
     */
    public function getView()
    {
        return \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
    }

    /**
     * Get cache data
     *
     * @return \Zend_Cache_Core
     */
    public function getCache()
    {
        return \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cachemanager')->getCache(self::CACHE_ID);
    }

    /**
     * Get system object
     *
     * @return \Extlib\System
     */
    public function getSystem()
    {
        return \Zend_Registry::get('di')->get('Extlib\System');
    }

    /**
     * Get system cookie object
     *
     * @return \Extlib\System\Cookie
     */
    public function getCookie()
    {
        return \Zend_Registry::get('di')->get('Extlib\System\Cookie');
    }

    /**
     * Get system session object
     *
     * @return \Zend_Session_Namespace
     */
    public function getSystemSession()
    {
        return \Zend_Registry::get('di')->get('System\Session');
    }

    /**
     * Get auth user object
     *
     * @return \stdClass
     */
    public function getAuthUser()
    {
        if (\Zend_Auth::getInstance()->hasIdentity()) {
            return \Zend_Auth::getInstance()->getIdentity();
        }

        return null;
    }
} 