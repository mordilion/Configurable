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

use Mordilion\Configurable\Configuration\Reader\Decoder\SimpleXml;

/**
 * Mordilion\Configurable Xml-Class.
 *
 * @author Frederik Wauters <frederik.wauters@web.de>
 */
class Xml implements ReaderInterface
{
    /**
     * Callable to decode the XML string in an array.
     *
     * @var callable
     */
    private $decoder;

    /**
     * Decoder params
     *
     * @var array $params
     */
    private $decoderParams = array();


    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        if (class_exists('Symfony\Component\Serializer\Encoder\XmlEncoder')) {
            // Load from fully qualified Namespace (No use in case not installed)
            $decoder = new \Symfony\Component\Serializer\Encoder\XmlEncoder();

            // Set Symfony/XmlEncoder as decoder, which takes format as parameter
            $this->setDecoder(array($decoder, 'decode'))
                ->setDecoderParams(array('xml'));
        } else if (function_exists('simplexml_load_string')) {
            // Set simplexml_load_string as decoder, which don't need parameters
            $this->setDecoder(array(new SimpleXml(), 'decode'));
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
     * @return Xml
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
     * Sets decoder call params
     *
     * @param  array $params
     */
    public function setDecoderParams(array $params)
    {
        $this->decoderParams = $params;

        return $this;
    }

    /**
     * Returns decoder params
     *
     * @return array $decoderParams
     */
    public function getDecoderParams()
    {
        return $this->decoderParams;
    }


    /**
     * Decodes the provided $xml into an object or an array.
     *
     * @param string $xml
     *
     * @throws \RuntimeException if a decoder is not specified
     * @throws \RuntimeException if the provided xml is not valid
     *
     * @return array
     */
    private function decode($xml)
    {
        $decoder = $this->getDecoder();

        if ($decoder === null) {
            throw new \RuntimeException('You didn\'t specify a decoder.');
        }

        $data = call_user_func_array($decoder, array_merge(array($xml), $this->getDecoderParams()));

        if (!is_array($data) && !is_object($data)) {
            throw new \RuntimeException('The provided XML is not valid.');
        }

        return $data;
    }
}
