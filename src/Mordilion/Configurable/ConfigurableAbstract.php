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

use Mordilion\Configurable\ConfigurableInterface;

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
     * @var ConfigurationInterface
     */
    protected $configuration;


    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ConfigurationInterface $configuration)
    {
        $config = $this->configuration;

        if ($config instanceof ConfigurationInterface) {
            $config->merge($configuration);
        } else {
            $config = $configuration;
        }

        $this->setConfiguration($config);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
        $this->configuration->configure($this);
    }
}