<?php

use Doctrine\Common\Cache\ArrayCache,
    Doctrine\Common\Cache\FilesystemCache,
    DI\Bridge\ZendFramework1\Dispatcher,
    DI\ContainerBuilder;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialization Dependency Injection
     *
     * @return \DI\ContainerInterface
     */
    protected function _initDiContainer()
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(APPLICATION_PATH . '/configs/config.php');
        $builder->addDefinitions(APPLICATION_PATH . '/configs/config.' . APPLICATION_ENV . '.php');

        if (APPLICATION_ENV === 'production') {
            $cache = new FilesystemCache(PROJECT_PATH . '/data/cache');
        } else {
            $cache = new ArrayCache();
        }

        $cache->setNamespace('DiConfigs');
        $builder->setDefinitionCache($cache);

        $dispatcher = new Dispatcher();
        $dispatcher->setContainer($builder->build());

        \Zend_Controller_Front::getInstance()->setDispatcher($dispatcher);
        \Zend_Registry::set(\System\Consts::DI_NAMESPACE, $dispatcher->getContainer());

        return $dispatcher->getContainer();
    }

    /**
     * Initialization Doctrine Container
     *
     * @return \Bisna\Doctrine\Container
     */
    public function _initDoctrineContainer()
    {
        $this->bootstrap('doctrine');
        return \Zend_Registry::get(\System\Consts::DOCTRINE);
    }

    /**
     * Initialization logger
     *
     * @return \Zend_Log
     */
    protected function _initLogger()
    {
        $this->bootstrap('log');
        $logger = $this->getResource('log');
        Zend_Registry::set('logger', $logger);
        return $logger;
    }

    /**
     * Initialization Zend Framework Debugger
     */
    protected function _initDebug()
    {
        if (APPLICATION_ENV === 'development') {
            $em = Zend_Registry::get(\System\Consts::DOCTRINE)->getEntityManager();
            $em->getConnection()->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\DebugStack());

            $cacheResource = $this->getPluginResource('cachemanager');
            $cacheManager = $cacheResource->getCacheManager();
            $cache = $cacheManager->getCache(\System\Consts::CACHE_ID_LONG);
            $cacheBackend = $cache->getBackend();

            $options = array(
                'plugins' => array(
                    'Variables',
                    'ZFDebug_Controller_Plugin_Debug_Plugin_Doctrine2' => array(
                        'entityManagers' => array($em),
                    ),
                    'Cache' => array('backend' => $cacheBackend),
                    'File' => array('basePath' => APPLICATION_PATH . '/application'),
                    'Exception',
                    'Html',
                    'Memory',
                    'Time',
                )
            );

            Zend_Controller_Front::getInstance()->registerPlugin(new ZFDebug_Controller_Plugin_Debug($options));
        }
    }
}

