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
     * Decodes the provided $ini into an object or an array.
     *
     * @param string $ini
     *
     * @throws \InvalidArgumentException if the provided ini is not valid
     *
     * @return array
     */
    private function decode(string $ini): array
    {
        $data = parse_ini_string($ini, true, INI_SCANNER_TYPED);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('The provided INI is not valid.');
        }

        return $data;
    }
}
