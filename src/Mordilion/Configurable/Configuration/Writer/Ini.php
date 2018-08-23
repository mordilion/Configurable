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

use Mordilion\Configurable\Configuration\ConfigurationInterface;

/**
 * Mordilion\Configurable Ini-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Ini implements WriterInterface
{
    /**
     * {@inheritdoc}
     */
    public function saveFile($configuration, $filename)
    {
        if (!is_writeable($filename)) {
            throw new \RuntimeException('The file "' . $filename . '" is not writeable.');
        }

        $content = $this->saveString($configuration);

        return file_put_contents($filename, $content) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function saveString($configuration)
    {
        return $this->encode($configuration);
    }

    /**
     * Encodes the provided $configuration into an ini string.
     *
     * @param array|ConfigurationInterface $configuration
     *
     * @throws \InvalidArgumentException if the provided configuration is not an array or an instance of ConfigurationInterface
     * @throws \RuntimeException if the decoding throwed some errors
     *
     * @return array
     */
    private function encode($configuration)
    {
        if ($configuration instanceof ConfigurationInterface) {
            $configuration = $configuration->toArray();
        }

        if (!is_array($configuration)) {
            throw new \InvalidArgumentException('The provided configuration is not an array or an instance of ConfigurationInterface.');
        }

        $noSection = array();
        $result    = '';
        $temp      = '';

        foreach ($configuration as $section => $values) {
            if (!is_array($values)) {
                // it's not in a section
                $noSection[$section] = $values;
                continue;
            }

            $result .= '[' . $section . ']' . PHP_EOL;

            foreach ($values as $key => $value) {
                $result .= $this->encodeKeyValuePair($key, $value);
            }

            $result .= PHP_EOL;
        }

        foreach ($noSection as $key => $value) {
            $temp .= $this->encodeKeyValuePair($key, $value);
        }

        return (empty($temp) ?: $temp . PHP_EOL) . trim($result);
    }

    /**
     * Encodes the provided $key and $value into a assignment string.
     *
     * @param string $key
     * @param string $value
     * @param boolean $enclose
     *
     * @return string
     */
    private function encodeKeyValuePair($key, $value, $enclose = false)
    {
        $result           = '';
        $keyPrefix        = ($enclose ? '[' : '');
        $keySuffix        = ($enclose ? ']' : '');
        $withoutLineBreak = false;

        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $result .= $keyPrefix . $key . $keySuffix;
                $result .= $this->encodeKeyValuePair($k, $v, true);

                $withoutLineBreak = true;
            }
        } else if (is_numeric($value)) {
            $result .= $keyPrefix . $key . $keySuffix . ' = ' . $value;
        } else {
            $result .= $keyPrefix . $key . $keySuffix . ' = "' . $value . '"';
        }

        return $result . ($withoutLineBreak ? '' : PHP_EOL);
    }
}
