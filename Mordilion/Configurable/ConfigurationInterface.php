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
 * Mordilion\Configurable Configuration-Interface.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
interface ConfigurationInterface extends \IteratorAggregate, \Countable
{
    /**
     * Magic __get-Method.
     *
     * @param mixed $name
     *
     * @return mixed
     */
    public function __get($name);

    /**
     * Magic __set-Method.
     *
     * @param mixed $name
     * @param mixed $value
     *
     * @return void
     */
    public function __set($name, $value);

    /**
     * {@inheritdoc}
     */
    public function count();

    /**
     * Returns the value for the provided $key if exists.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * {@inheritdoc}
     */
    public function getIterator();

    /**
     * This method will load the provided $data into the configuration object.
     *
     * @param array $data
     *
     * @return ConfigurationInterface
     */
    public function load(array $data);

    /**
     * Sets the $value for the provided $key.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value);
}