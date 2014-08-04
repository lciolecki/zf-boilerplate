<?php

namespace System\Controller\Plugin;

use System\Controller\Plugin\AbstractPlugin;

/**
 * ZF-Boilerplate module for checkout localisation
 *
 * @category System
 * @package System\Controller
 * @subpackage System\Controller\Plugin
 * @copyright  Copyright (c) 2013 Łukasz Ciołecki (lciolecki)
 */
class Locale extends AbstractPlugin
{
    const DEFAULT_LANGUAGE = 'pl_PL';

    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::routeShutdown()
     */
    public function routeStartup(\Zend_Controller_Request_Abstract $request)
    {
        /* General translate */
        $translate = new \Zend_Translate(array(
            'adapter' => 'gettext',
            'content' =>  APPLICATION_PATH . '/resources/languages',
            'locale'  => null,
            'scan' => \Zend_Translate::LOCALE_DIRECTORY,
            'tag' => 'Zend_Translate',
            'cache' => $this->getCache()
        ));

        try {
            $locale = new \Zend_Locale(\Zend_Locale::BROWSER);

            if (!in_array($locale->getLanguage(), $translate->getList())) {
                $locale->setLocale(self::DEFAULT_LANGUAGE);
            }
        } catch (\Zend_Locale_Exception $exc) {
            $locale = new \Zend_Locale(self::DEFAULT_LANGUAGE);
        }

        $translate->getAdapter()->setLocale($locale);

        /* Validate translator */
        $validateTranslator = new \Zend_Translate(array(
            'adapter' => 'array',
            'content' =>  APPLICATION_PATH . '/resources/languages',
            'locale'  => $locale,
            'scan' => \Zend_Translate::LOCALE_DIRECTORY,
            'tag' => 'Validate_Translate',
            'cache' => $this->getCache()
        ));

        \Zend_Validate::setDefaultTranslator($validateTranslator);
        \Zend_Registry::set('Zend_Locale', $locale);
        \Zend_Registry::set('Zend_Translate', $translate);
        \Zend_Registry::set('Validate_Translate', $validateTranslator);

        $this->getView()->language = $locale->getLanguage();
        $this->getView()->headLink(array('rel' => 'alternate', 'hreflang' => $locale->getLanguage(), 'href' => $this->getSystem()->getDomain()->getAddress()), 'SET');

        parent::routeStartup($request);
    }
}
