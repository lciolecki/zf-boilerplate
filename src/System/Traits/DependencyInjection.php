<?php

namespace System\Traits;

use \Interop\Container\ContainerInterface;

/**
 * ZF-Boilerplate trait Dependency Injection
 *
 * @category System
 * @package System\Traits
 * @copyright  Copyright (c) 2013 Łukasz Ciołecki (lciolecki)
 */
trait DependencyInjection
{
    /**
     * Instance of Dependency Injection object
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $di = null;

    /**
     * Get Dependency Injection object
     *
     * @return \Interop\Container\ContainerInterface
     */
    public function getDi()
    {
        if ($this->di === null) {
            $this->setDi(\Zend_Registry::get(\System\Consts::DI_NAMESPACE));
        }

        return $this->di;
    }

    /**
     * Set Dependency Injection object
     *
     * @param \Interop\Container\ContainerInterface
     * @return \System\Traits\DependencyInjection
     */
    public function setDi(ContainerInterface $di)
    {
        $this->di = $di;
        return $this;
    }
}