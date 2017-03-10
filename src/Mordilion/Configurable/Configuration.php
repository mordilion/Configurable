<?php

/**
 * This file is part of the Mordilion\Configurable package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\Configurable;

/**
 * Mordilion\Configurable Configuration-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * The data of the configuration.
     *
     * @var array
     */
    protected $data = array();


    /**
     * {@inheritdoc}
     */
    public function __construct($data)
    {
        if (is_object($data)) {
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray(); // Zend_Config | Zend\Config\Config | ...
            }
        }

        if (!is_array($data)) {
            throw new \InvalidArgumentException('The provided data must be an array or an object with the toArray() method.');
        }

        $this->data = (array)$data;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }


    /**
     * {@inheritdoc}
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function __unset($name)
    {
        unset($this->data[name]);
    }

    /**
     * {@inheritdoc}
     */
    public function configure($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('The provided Object is not an Object!');
        }

        foreach ($this as $key => $value) {
            $method   = 'set' . ucfirst($key);
            $property = lcfirst($key);

            if (method_exists($object, $method)) {
                $object->$method($value);
            } else if (property_exists($object, $property)) {
                $object->$property = $value;
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function merge(ConfigurationInterface $configuration)
    {
        foreach ($configuration as $key => $value) {
            if (isset($this->data[$key])) {
                if ($value instanceof ConfigurationInterface && $this->data[$key] instanceof ConfigurationInterface) {
                    $this->data[$key]->merge($value);
                } else {
                    $this->set($key, $value);
                }
            } else {
                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $result = array();
        $data   = $this->data;

        foreach ($data as $key => $value) {
            if ($value instanceof self) {
                $result[$key] = $value->toArray();
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}