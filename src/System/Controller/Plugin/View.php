<?php

namespace System\Controller\Plugin;

/**
 * ZF-Boilerplate module for view data
 *
 * @category System
 * @package System\Controller
 * @subpackage System\Controller\Plugin
 * @copyright  Copyright (c) 2014 Łukasz Ciołecki (lciolecki)
 */
class View extends AbstractPlugin
{
    /**
     * (non-PHPdoc)
     * @see \Zend_Controller_Plugin_Abstract::preDispatch()
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        $this->getView()->user = $this->getAuthUser();
        $this->getView()->system = $this->getSystem();

        parent::preDispatch($request);
    }
}
