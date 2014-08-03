<?php

namespace System\Controller\Plugin;

use System\Controller\Plugin\AbstractPlugin;

/**
 * ZF-Boilerplate module plugin
 *
 * @category System
 * @package System_Controller
 * @subpackage System_Controller_Plugin
 * @copyright  Copyright (c) 2013 Łukasz Ciołecki (lciolecki)
 */
class Module extends AbstractPlugin
{
    /**
     * Array of modules
     *
     * @var string
     */
    protected $modules;

    /**
     * __construct() - instance of construct
     */
    public function __construct()
    {
        $this->modules = \Zend_Controller_Front::getInstance()->getControllerDirectory();
    }

    /**
     * @param \Zend_Controller_Request_Abstract $request
     * @return mixed
     * @throws \Zend_Controller_Dispatcher_Exception
     */
    public function routeShutdown(\Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();

        if (!isset($this->modules[$module])) {
            throw new \Zend_Controller_Dispatcher_Exception('Invalid module specified (' . $module . ')');
        }

        $frontController = \Zend_Controller_Front::getInstance();
        $bootstrap = $frontController->getParam('bootstrap');
        $application = $bootstrap->getApplication();
        $path = $this->modules[$module];
        $class = ucfirst($module) . '_Bootstrap';

        if (\Zend_Loader::loadFile('Bootstrap.php', dirname($path)) && class_exists($class)) {
            $bootstrap = new $class($application);
            $bootstrap->bootstrap();

            $frontController->setParam('module_bootstrap', $bootstrap);
        }

        $errorPlugin = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler');
        $errorPlugin->setErrorHandlerModule($request->getModuleName());

        return parent::routeShutdown($request);
    }

    /**
     * (non-PHPdoc)
     * @see \Zend_Controller_Plugin_Abstract::preDispatch()
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $this->normalizeName($request->getModuleName());
        $controllerName = ucfirst($this->normalizeName($request->getControllerName())) . 'Controller';
        $controllerPath = $this->modules[$moduleName] . DIRECTORY_SEPARATOR . $controllerName. '.php';

        if (!file_exists($controllerPath)) {
            $this->configureDirectories($request->getModuleName());
        } else {
            $method = $this->normalizeName($request->getActionName()) . 'Action';
            $classDefine = file_get_contents($controllerPath);

            if (!strpos($classDefine, $method)) {
                $this->configureDirectories($request->getModuleName());
            }
        }

        $this->getView()->addHelperPath(PROJECT_PATH . '/modules/' . $request->getModuleName() . '/views/helpers', 'Simplepanel\View\Helper');
        $this->getView()->addScriptPath(PROJECT_PATH . '/modules/' . $request->getModuleName() . '/views/scripts');
    }

    /**
     * Normalize method for name of module/controller/action
     *
     * @param string $name
     * @return string
     */
    protected function normalizeName($name)
    {
        $elements = explode('-', $name);

        if (1 === count($elements)) {
            return $name;
        }

        $name = $elements[0];
        for ($i = 1; $i < count($elements); $i++) {
            $name .= ucfirst($elements[$i]);
        }

        return $name;
    }

    /**
     * Method configuration module directiories (controller/views/forms/helpers/etc..)
     *
     * @param string $modul
     */
    protected function configureDirectories($module)
    {
        \Zend_Controller_Front::getInstance()->setControllerDirectory(
            APPLICATION_PATH . '/modules/' . $module . '/controllers',
            $module
        );

        new \Zend_Application_Module_Autoloader(array(
            'namespace' => ucfirst($module),
            'basePath' => APPLICATION_PATH . '/modules/' . $module,
            'resourceTypes' => array (
                'form' => array(
                    'path' => 'forms',
                    'namespace' => 'Form',
                ),
            )
        ));

        $this->getView()->addHelperPath(APPLICATION_PATH . '/modules/' . $module . '/views/helpers', 'Simplepanel\View\Helper');
        $this->getView()->addScriptPath(APPLICATION_PATH . '/modules/' . $module . '/views/scripts');
    }
}