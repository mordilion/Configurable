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

use Mordilion\Configurable\Configuration;

/**
 * Mordilion\Configurable Configuration-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class ConfigurationFactory
{
    /**
     * Returns a new Configuration object, based on the provided object.
     *
     * @param mixed $object
     *
     * @throws InvalidArgumentException If the provided object is not a supported object.
     *
     * @return Configuration
     */
    public static function build($object)
    {
        if (is_array($object)) {
            return self::buildByArray($object);
        } else if ($object instanceof Zend_Config) {
            return self::buildByArray($object->toArray());
        } else if ($object instanceof Zend\Config\Config) {
            return self::buildByArray($object->toArray());
        } else {
            throw new InvalidArgumentException('The provided object is not supported!');
        }
    }

    public static function buildByArray(array $data)
    {
        $configuration = new Configuration();
        $configuration->load($data);

        return $configuration;
    }
}