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
 * Mordilion\Configurable Configurable-Interface.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
interface ConfigurableInterface
{
    /**
     * This method returns the current configuration.
     *
     * @return mixed
     */
    public function getConfiguration();

    /**
     * This method will configure the object with the provided configuration.
     *
     * @param mixed $configuration
     *
     * @return ConfigurableInterface
     */
    public function setConfiguration($configuration);
}