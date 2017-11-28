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
     * @return array  $conf
     */
    public function decode($xml)
    {
        $data = (array) simplexml_load_string($xml);

        // Recursively cast to array
        array_walk_recursive($data, function(&$item) {
            if($item instanceof \SimpleXMLElement){
                $item = (array) $item;
            }
        });        

        return $data;
    }
}

