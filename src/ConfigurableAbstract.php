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

use Mordilion\Configurable\ConfigurableInterface;
use Mordilion\Configurable\Configuration;
use Mordilion\Configurable\ConfigurationFactory;

/**
 * Mordilion\Configurable Configurable-Abstract-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
abstract class ConfigurableAbstract implements ConfigurableInterface
{
    /**
     * The configuration of the object.
     *
     * @var Configuration
     */
    protected $configuration;


    /**
     * This method returns the current configuration.
     *
     * @return mixed
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
        $newConfiguration = ConfigurationFactory::build($configuration);

        if ($this->configuration instanceof Configuration) {
            $this->configuration->merge($newConfiguration);
        } else {
            $this->configuration = $newConfiguration;
        }

        foreach ($this->configuration as $key => $value) {
            $method   = 'set' . ucfirst($key);
            $property = lcfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            } else if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}