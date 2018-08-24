<?php

/**
 * This file is part of the Mordilion\Configurable package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\Configurable\Configuration\Writer;

use Mordilion\Configurable\Configuration\ConfigurationInterface;

/**
 * Mordilion\Configurable Yaml-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Yaml implements WriterInterface
{
    /**
     * Callable to encode data into a YAML.
     *
     * @var callable
     */
    private $encoder;

    /**
     * Encoder parameters.
     *
     * @var array
     */
    private $encoderParameters = array();


    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        if (class_exists('Symfony\Component\Yaml\Yaml')) {
            $this->setEncoder(array('Symfony\Component\Yaml\Yaml', 'dump'));
        } else if (function_exists('spyc_dump')) {
            $this->setEncoder('spyc_dump');
        } else if (function_exists('yaml_emit')) {
            $this->setEncoder('yaml_emit');
        }
    }

    /**
     * Returns the current encoder.
     *
     * @return callable
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Returns the current encoder parameters.
     *
     * @return array
     */
    public function getEncoderParameters()
    {
        return $this->encoderParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function saveFile($configuration, $filename)
    {
        if (!is_writeable($filename)) {
            throw new \RuntimeException('The file "' . $filename . '" is not writeable.');
        }

        $content = $this->saveString($configuration);

        return file_put_contents($filename, $content) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function saveString($configuration)
    {
        return $this->encode($configuration);
    }

    /**
     * Sets the encoder.
     *
     * @param callable $encoder
     *
     * @return Yaml
     */
    public function setEncoder($encoder)
    {
        if (!is_callable($encoder)) {
            throw new \InvalidArgumentException('The provided encoder must be callable.');
        }

        $this->encoder = $encoder;

        return $this;
    }

    /**
     * Sets the encoder call parameters.
     *
     * @param array $parameters
     *
     * @return Yaml
     */
    public function setEncoderParameters(array $parameters)
    {
        $this->encoderParameters = $parameters;

        return $this;
    }

    /**
     * Encodes the provided $configuration into a YAML string.
     *
     * @param array|ConfigurationInterface $configuration
     *
     * @throws \InvalidArgumentException if the provided configuration is not an array or an instance of ConfigurationInterface
     * @throws \RuntimeException if a encoder is not specified
     * @throws \RuntimeException if the encoder returned not a valid YAML
     *
     * @return array
     */
    private function encode($configuration)
    {
        if ($configuration instanceof ConfigurationInterface) {
            $configuration = $configuration->toArray();
        }

        if (!is_array($configuration)) {
            throw new \InvalidArgumentException('The provided configuration is not an array or an instance of ConfigurationInterface.');
        }

        $encoder = $this->getEncoder();

        if ($encoder === null) {
            throw new \RuntimeException('You didn\'t specify a encoder.');
        }

        $result = call_user_func_array($encoder, array_merge(array($configuration), $this->getEncoderParameters()));

        if ($result !== false) {
            return $result;
        }

        throw new \RuntimeException('The provided YAML is not valid.');
    }
}
