<?php

/**
 * This file is part of the Mordilion\Configurable package.
 *
 * For the full copzright and license information, please view the
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
    public function __get($name)
    {
        return $this->get($name);
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
        return new ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }
}