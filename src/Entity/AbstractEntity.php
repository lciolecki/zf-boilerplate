<?php
namespace Entity;

use System\Traits\Doctrine,
    System\Traits\DependencyInjection;

/**
 * ZF-Boilerplate base Entity class
 *
 * @category Entity
 * @copyright  Copyright (c) 2014 Łukasz Ciołecki (lciolecki)
 */
abstract class AbstractEntity
{
    use Doctrine,
        DependencyInjection;
    /**
     * Instance of construct
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        if (count($data) !== 0) {
            $this->fromArray($data);
        }
    }

    /**
     * Magic call for set and get method
     *
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __call($method, $args)
    {
        $property = lcfirst(substr($method, 3));

        if (property_exists(\get_class($this), $property)) {
            switch (substr($method, 0, 3)) {
                case 'set':
                    $arg = null;
                    if (isset($args[0])) {
                        $arg = $args[0];
                    }

                    $this->$property = $arg;
                    return $this;
                case 'get':
                    return $this->$property;
            }
        }

        throw new \InvalidArgumentException("Call to undefined method " . get_class($this) . "::" . $method . "()");
    }

    /**
     * Setter
     *
     * @param string $property
     * @param miexd $value
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __set($property, $value)
    {
        $method = 'set' . ucfirst($property);

        if (method_exists($this, $method)) {
            return $this->$method($value);
        } else if (property_exists($this, $property)) {
            $this->$property = $value;
            return $this;
        }

        throw new \InvalidArgumentException(sprintf("Call to undefined property '%s'", $property));
    }

    /**
     * Getter
     *
     * @param type $property
     * @return miexd
     * @throws \InvalidArgumentException
     */
    public function __get($property)
    {
        $method = 'get' . ucfirst($property);

        if (method_exists($this, $method)) {
            return $this->$method();
        } else if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new \InvalidArgumentException(sprintf("Call to undefined property '%s'", $property));
    }

    /**
     * Fill object array data
     *
     * @param array $values
     * @return \Entity\AbstractEntity
     */
    public function fromArray(array $values)
    {
        foreach ($values as $property => $value) {
            if (property_exists($this, $property)) {
                $this->__set($property, $value);
            }
        }

        return $this;
    }

    /**
     * Return array of object field =>value
     *
     * @return array
     */
    public function toArray()
    {
        $data = array();
        foreach (get_object_vars($this) as $name => $value) {
            if ($value instanceof AbstractEntity) {
                $data[$name] = $value->toArray();
            } else {
                $data[$name] = $value;
            }
        }

        return $data;
    }
}