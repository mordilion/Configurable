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

use Mordilion\Configurable\Configuration\ConfigurationInterface;
use Mordilion\Configurable\Configuration\Configuration;

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
     * @param boolean $configure
     *
     * @return ConfigurableInterface
     */
    public function addConfiguration($configuration, $configure = true)
    {
        if (!$configuration instanceof ConfigurationInterface) {
            $configuration = new Configuration($configuration);
        }

        $config = $this->getConfiguration();

        if ($config instanceof ConfigurationInterface) {
            $config->merge($configuration);
        } else {
            $config = $configuration;
        }

        $this->setConfiguration($config, $configure);
    }

    /**
     * Configures the object with the current configuration.
     *
     * @return void
     */
    public function configure()
    {
        $configuration = $this->getConfiguration();

        if ($configuration instanceof ConfigurationInterface) {
            $this->configuration->configure($this);
        }
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
     * @param boolean $configure
     *
     * @return ConfigurableInterface
     */
    public function setConfiguration($configuration, $configure = true)
    {
        if (!$configuration instanceof ConfigurationInterface) {
            $configuration = new Configuration($configuration);
        }

        unset($this->configuration); // destroy the old one

        $this->configuration = $configuration;

        if ($configure) {
            $this->configure();
        }
    }
}