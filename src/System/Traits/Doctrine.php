<?php

namespace System\Traits;

use Bisna\Doctrine\Container,
    System\Consts,
    Extlib\System;

/**
 * ZF-Boilerplate trait Doctrine
 *
 * @category System
 * @package System\Traits
 * @copyright Copyright (c) 2014 Łukasz Ciołecki (lciolecki)
 */
trait Doctrine
{
    /**
     * Instance of doctrine container
     *
     * @var \Bisna\Doctrine\Container
     */
    protected $container = null;

    /**
     * Get EntityManager by name
     *
     * @param string $name
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm($name = Consts::DEFAULT_ENTITY_MANAGER)
    {
        return $this->getContainer()->getEntityManager($name);
    }

    /**
     * Set doctrine container
     *
     * @param \Bisna\Doctrine\Container $container
     * @return \System\Traits\Doctrine
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Get doctrine container
     *
     * @return \Bisna\Doctrine\Container
     */
    public function getContainer()
    {
        if ($this->container === null) {
            $this->setContainer(\Zend_Registry::get(Consts::DOCTRINE));
        }

        return $this->container;
    }
}