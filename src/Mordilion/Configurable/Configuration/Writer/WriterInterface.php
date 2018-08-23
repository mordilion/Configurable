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

/**
 * Mordilion\Configurable Writer-Interface.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
interface WriterInterface
{
    /**
     * Saves the $configuration into the file by the provided $filename.
     *
     * @param array|Mordilion\Configurable\Configuration\ConfigurationInterface $configuration
     * @param string $filename
     *
     * @throws \RuntimeException if the file is not writeable
     *
     * @return boolean
     */
    public function saveFile($configuration, $filename);

    /**
     * Saves the $configuration into a string.
     *
     * @param array|Mordilion\Configurable\Configuration\ConfigurationInterface $configuration
     *
     * @return string
     */
    public function saveString($configuration);
}