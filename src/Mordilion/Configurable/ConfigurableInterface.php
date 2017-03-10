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

use Mordilion\Configurable\ConfigurationInterface;

/**
 * Mordilion\Configurable Configurable-Interface.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
interface ConfigurableInterface
{
    /**
     * This method will add the provided configuration to the object configuration.
     *
     * @param ConfigurationInterface $configuration
     *
     * @return ConfigurableInterface
     */
    public function addConfiguration(ConfigurationInterface $configuration);

    /**
     * This method returns the current configuration.
     *
     * @return ConfigurationInterface
     */
    public function getConfiguration();

    /**
     * This method will configure the object with the provided configuration.
     *
     * @param ConfigurationInterface $configuration
     *
     * @return ConfigurableInterface
     */
    public function setConfiguration(ConfigurationInterface $configuration);
}