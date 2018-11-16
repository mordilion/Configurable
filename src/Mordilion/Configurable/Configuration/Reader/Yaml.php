<?php

/**
 * This file is part of the Mordilion\Configurable package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\Configurable\Configuration\Reader;

/**
 * Mordilion\Configurable Yaml-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Yaml implements ReaderInterface
{
    /**
     * Callable to decode the YAML string in an array.
     *
     * @var callable
     */
    private $decoder;

    /**
     * Decoder parameters.
     *
     * @var array
     */
    private $decoderParameters = [];


    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        if (function_exists('yaml_parse')) {
            $this->setDecoder('yaml_parse');
        }

        if (function_exists('spyc_load')) {
            $this->setDecoder('spyc_load');
        }

        if (class_exists('Symfony\Component\Yaml\Yaml')) {
            $this->setDecoder(array('Symfony\Component\Yaml\Yaml', 'parse'));
        }
    }

    /**
     * Returns the current decoder.
     *
     * @return callable|null
     */
    public function getDecoder(): ?callable
    {
        return $this->decoder;
    }

    /**
     * Returns the current decoder parameters.
     *
     * @return array
     */
    public function getDecoderParameters(): array
    {
        return $this->decoderParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function loadFile(string $filename): array
    {
        if (!is_readable($filename)) {
            throw new \RuntimeException('The file "' . $filename . '" is not readable.');
        }

        $content = file_get_contents($filename);

        return $this->loadString($content);
    }

    /**
     * {@inheritdoc}
     */
    public function loadString(string $string): array
    {
        if (empty($string)) {
            return [];
        }

        return $this->decode($string);
    }

    /**
     * Sets the decoder.
     *
     * @param callable $decoder
     *
     * @return Yaml
     */
    public function setDecoder(callable $decoder): Yaml
    {
        $this->decoder = $decoder;

        return $this;
    }

    /**
     * Sets the decoder call parameters.
     *
     * @param array
     *
     * @return Yaml
     */
    public function setDecoderParameters(array $parameters): Yaml
    {
        $this->decoderParameters = $parameters;

        return $this;
    }

    /**
     * Decodes the provided $yaml into an object or an array.
     *
     * @param string $yaml
     *
     * @throws \RuntimeException if a decoder is not specified
     * @throws \RuntimeException if the provided yaml is not valid
     *
     * @return array
     */
    private function decode(string $yaml): array
    {
        $decoder = $this->getDecoder();

        if ($decoder === null) {
            throw new \RuntimeException('You didn\'t specify a decoder.');
        }

        $data = call_user_func_array($decoder, array_merge([$yaml], $this->getDecoderParameters()));

        if (!is_array($data) && !is_object($data)) {
            throw new \RuntimeException('The provided YAML is not valid.');
        }

        return $data;
    }
}