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
        return $this->castToArray((array) simplexml_load_string($xml));
    }

    /**
     * Recursively cast to array
     *
     * @param  mixed $input
     * @return array
     */
    private function castToArray($input)
    {
        $result = array();

        foreach($input as $key => $value){
            if($value instanceof \SimpleXMLElement){
                $result[$key] = $this->castToArray((array) $value);
            }elseif(is_array($value)){
                $result[$key] = $this->castToArray($value);
            }else{
                if(is_numeric($value)){
                    $result[$key] = $value + 0;
                }elseif($value == "true" || $value == "false"){
                    $result[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }else{
                    $result[$key] = $value;
                }
            }
        }       

        return $result;
    }
}

