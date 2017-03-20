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

use Mordilion\Configurable\Configuration\ConfigurableInterface;

/**
 * Mordilion\Configurable Configurable-Trait.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
trait Configurable
{
    /**
     * The configuration of the object.
     *
     * @var ConfigurationInterface
     */
    protected $configuration;


    /**
     * This method will add the provided configuration to the object configuration.
     *
     * @param mixed $configuration
     *
     * @return ConfigurableInterface
     */
    public function addConfiguration($configuration)
    {
        if (!$configuration instanceof ConfigurationInterface) {
            $configuration = new Configuration($configuration);
        }

        $config = $this->configuration;

        if ($config instanceof ConfigurationInterface) {
            $config->merge($configuration);
        } else {
            $config = $configuration;
        }

        $this->setConfiguration($config);
    }

    /**
     * This method returns the current configuration.
     *
     * @return ConfigurationInterface
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * This method will configure the object with the provided configuration.
     *
     * @param mixed $configuration
     *
     * @return ConfigurableInterface
     */
    public function setConfiguration($configuration)
    {
        if (!$configuration instanceof ConfigurationInterface) {
            $configuration = new Configuration($configuration);
        }

        if ($this->configuration instanceof ConfigurationInterface) {
            unset($this->configuration); // destroy the old one
        }

        $this->configuration = $configuration;
        $this->configuration->configure($this);
    }
}