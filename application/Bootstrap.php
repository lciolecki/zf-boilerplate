<?php

use \Doctrine\Common\Cache\ArrayCache,
    \DI\Bridge\ZendFramework1\Dispatcher,
    \DI\ContainerBuilder;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
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
    }
}

