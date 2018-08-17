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
 * Mordilion\Configurable Ini-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Ini implements ReaderInterface
{
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
     * Decodes the provided $ini into an object or an array.
     *
     * @param string $ini
     *
     * @throws \InvalidArgumentException if the provided ini is not valid
     * @throws \RuntimeException if the decoding throwed some errors
     *
     * @return array
     */
    private function decode($ini)
    {
        $data = parse_ini_string($ini, true);

        if (!is_array($data) && !is_object($data)) {
            throw new \InvalidArgumentException('The provided INI is not valid.');
        }

        if ($data !== false) {
            return $data;
        }

        throw new \RuntimeException('Could\'t parse the INI');
    }
}
