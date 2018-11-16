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
 * Mordilion\Configurable Json-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Json implements ReaderInterface
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
     * Decodes the provided $json into an object or an array.
     *
     * @param string $json
     *
     * @throws \InvalidArgumentException if the provided json is not valid
     * @throws \RuntimeException if the decoding throwed some errors
     *
     * @return array
     */
    private function decode(string $json): array
    {
        $data = json_decode($json, true);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('The provided JSON is not valid.');
        }

        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        throw new \RuntimeException(json_last_error_msg());
    }
}