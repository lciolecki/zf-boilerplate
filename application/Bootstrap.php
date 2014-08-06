<?php

use \Doctrine\Common\Cache\ArrayCache,
    \Doctrine\Common\Cache\MemcachedCache,
    \DI\Bridge\ZendFramework1\Dispatcher,
    \DI\ContainerBuilder;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialization Doctrine EntityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function _initEntityManager()
    {
        $resource = $this->bootstrap('doctrine');

        $container = Zend_Registry::get('doctrine');
        Zend_Registry::set('em', $container->getEntityManager('default'));
        return $container->getEntityManager('default');
    }

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
//            $cache = new MemcachedCache();
//            $memcached = new Memcached();
//            $memcached->addServer('localhost', 11211);
//            $cache->setMemcached($memcached);
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
     * Initialization logger
     *
     * @return Zend_Log
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
            $em = Zend_Registry::get('em');
            $em->getConnection()->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\DebugStack());

            $cacheResource = $this->getPluginResource('cachemanager');
            $cacheManager = $cacheResource->getCacheManager();
            $cache = $cacheManager->getCache('data');
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

            //Zend_Controller_Front::getInstance()->registerPlugin(new ZFDebug_Controller_Plugin_Debug($options));
        }
    }
}

