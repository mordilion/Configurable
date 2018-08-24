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
 * Mordilion\Configurable Reader-Interface.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
interface ReaderInterface
{
    /**
     * Loads the configuration from file by the provided $filename.
     *
     * @param string $filename
     *
     * @throws \RuntimeException if the file is not readable
     *
     * @return array|false
     */
    public function loadFile($filename);

    /**
     * Loads the configuration from the provided $string.
     *
     * @param string $string
     *
     * @return array|false
     */
    public function loadString($string);
}