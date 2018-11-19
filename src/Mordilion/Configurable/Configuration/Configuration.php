<?php

/**
 * This file is part of the Mordilion\Configurable package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\Configurable\Configuration;

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
    protected $data = [];


    /**
     * {@inheritdoc}
     */
    public function __construct($data = [])
    {
        if (is_object($data) && !$data instanceof \Traversable) {
            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } 

            if (is_object($data)) {
                $data = get_object_vars($data);
            }
        }

        if ($data instanceof \Traversable) {
            $data = iterator_to_array($data);
        }

        if (!is_array($data)) {
            throw new \InvalidArgumentException('The provided data must be an array or traversable.');
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
    public function __isset($name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function __set($name, $value): void
    {
        $this->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function __unset($name): void
    {
        unset($this->data[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function configure(Object $object): ConfigurationInterface
    {
        foreach ($this as $key => $value) {
            $method = 'set' . ucfirst($key);
            $property = lcfirst($key);
            $reflection = new \ReflectionClass($object);

            if ($reflection->hasMethod($method)) {
                $reflectionMethod = $reflection->getMethod($method);
                $reflectionMethod->setAccessible(true);
                $reflectionMethod->invoke($object, $value);
                continue;
            }

            if ($reflection->hasProperty($property) || isset($object->$property)) {
                $reflectionProperty = $reflection->getProperty($property);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($object, $value);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
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
    public function merge(ConfigurationInterface $configuration): ConfigurationInterface
    {
        foreach ($configuration as $key => $value) {
            if (isset($this->data[$key]) 
                && $value instanceof ConfigurationInterface 
                && $this->data[$key] instanceof ConfigurationInterface
            ) {
                $configuration = $this->data[$key];
                $configuration->merge($value);

                $value = $configuration;
            }

            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value): ConfigurationInterface
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $result = array();
        $data = $this->data;

        foreach ($data as $key => $value) {
            if ($value instanceof ConfigurationInterface) {
                $value = $value->toArray();
            }
            
            $result[$key] = $value;
        }

        return $result;
    }
}