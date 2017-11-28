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

/**
 * Mordilion\Configurable Decoder-Interface.
 *
 * @author Frederik Wauters <frederik.wauters@web.de>
 */
interface DecoderInterface
{
    /**
     * Decodes provided string information into array
     *
     * @param string $string
     *
     * @return array|false
     */
    public function decode($string);
}
