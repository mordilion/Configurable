<?php

/**
 * This file is part of the Mordilion\Configurable package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\Configurable\Configuration\Reader\Decoder;

use Mordilion\Configurable\Configuration\Reader\Decoder\DecoderInterface;

/**
 * Mordilion\Configurable Decoder class.
 *
 * @author Frederik Wauters <frederik.wauters@web.de>
 */
class SimpleXml implements DecoderInterface
{
    /**
     * Simple XML decoder
     *
     * @param  string $xml
     *
     * @return array
     */
    public function decode(string $xml): array
    {
        return $this->castToArray((array)simplexml_load_string($xml));
    }

    /**
     * Recursively cast to array
     *
     * @param  mixed $input
     *
     * @return array
     */
    private function castToArray($input): array
    {
        $result = array();

        foreach ($input as $key => $value) {
            $result[$key] = $this->castValue($value);
        }

        return $result;
    }

    /**
     * Cast the given $value to an array possible type.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function castValue($value)
    {
        if ($value instanceof \SimpleXMLElement) {
            return $this->castToArray((array)$value);
        } 

        if (is_array($value)) {
            return $this->castToArray($value);
        }

        if (is_numeric($value)) {
            return $value + 0;
        } 

        if (is_string($value) && in_array(strtolower($value), array('true', 'yes', 'on', 'false', 'no', 'off'))) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return $value;
    }
}

