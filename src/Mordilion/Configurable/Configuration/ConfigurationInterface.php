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
 * Mordilion\Configurable Configuration-Interface.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
interface ConfigurationInterface extends \IteratorAggregate, \Countable
{
    /**
     * Constructor.
     *
     * @param array|obejct $data
     *
     * @throws \InvalidArgumentException if the provided data are not an array or not an object with the toArray() method
     */
    public function __construct($data);

    /**
     * Magic __get-Method.
     *
     * @param mixed $name
     *
     * @return mixed
     */
    public function __get($name);

    /**
     * Magic __isset-Method.
     *
     * @param mixed $name
     *
     * @return bool
     */
    public function __isset($name): bool;

    /**
     * Magic __set-Method.
     *
     * @param mixed $name
     * @param mixed $value
     *
     * @return void
     */
    public function __set($name, $value): void;

    /**
     * Magic __unset-Method.
     *
     * @param mixed $name
     * @param mixed $valu
     *
     * @return void
     */
    public function __unset($name): void;

    /**
     * Configures the provided object with the current data.
     *
     * @param Object $object
     *
     * @throws InvalidArgumentException If the provided $object is not an object.
     *
     * @return ConfigurationInterface
     */
    public function configure(Object $object): ConfigurationInterface;

    /**
     * {@inheritdoc}
     */
    public function count(): int;

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
     * Merges the current configuration with the provided configuration.
     *
     * @param ConfigurationInterface $configuration
     *
     * @return ConfigurationInterface
     */
    public function merge(ConfigurationInterface $configuration): ConfigurationInterface;

    /**
     * Sets the $value for the provided $key.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return ConfigurationInterface
     */
    public function set($key, $value): ConfigurationInterface;

    /**
     * Returns the current configuration as an array.
     *
     * @return array
     */
    public function toArray(): array;
}