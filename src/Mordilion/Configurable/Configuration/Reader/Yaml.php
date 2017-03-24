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
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        if (class_exists('Symfony\Component\Yaml\Yaml')) {
            $this->setDecoder(array('Symfony\Component\Yaml\Yaml', 'parse'));
        } else if (function_exists('spyc_load')) {
            $this->setDecoder('spyc_load');
        } else if (function_exists('yaml_parse')) {
            $this->setDecoder('yaml_parse');
        }
    }

    /**
     * Returns the current decoder.
     *
     * @return callable
     */
    public function getDecoder()
    {
        return $this->decoder;
    }

    /**
     * {@inheritdoc}
     */
    public function loadFile($filename)
    {
        if (!is_file($filename)) {
            throw new \InvalidArgumentException('The provided filename is not a valid filename.');
        }

        if (!is_readable($filename)) {
            throw new \RuntimeException('The file "' . $filename . '" is not readable.');
        }

        $content = file_get_contents($filename);

        return $this->loadString($content);
    }

    /**
     * {@inheritdoc}
     */
    public function loadString($string)
    {
        if (empty($string)) {
            return array();
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
    public function setDecoder($decoder)
    {
        if (!is_callable($decoder)) {
            throw new \InvalidArgumentException('The provided decoder must be callable.');
        }

        $this->decoder = $decoder;

        return $this;
    }

    /**
     * Decodes the provided $yaml into an object or an array.
     *
     * @param string $yaml
     *
     * @throws \RuntimeException if a decoder is not specified
     * @throws \RuntimeException if the provided yaml is not valid
     * @throws \RuntimeException if a exception was catched
     * @throws \RuntimeException if the decoding throwed some errors
     *
     * @return array
     */
    private function decode($yaml)
    {
        try {
            $decoder = $this->getDecoder();

            if ($decoder === null) {
                throw new \RuntimeException('You didn\'t specify a decoder.');
            }

            $data = call_user_func($decoder, $yaml);

            if (!is_array($data) && !is_object($data)) {
                throw new \RuntimeException('The provided YAML is not valid.');
            }

            if ($data !== false) {
                return $data;
            }
        } catch (Exception $e) {
            throw new \RuntimeException('Unable to parse the YAML string! :: ' . $e->getMessage());
        }

        throw new \RuntimeException('Unable to parse the YAML string! Is a YAML library configured?');
    }
}