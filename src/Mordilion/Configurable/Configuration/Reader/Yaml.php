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
 * Mordilion\Configurable Yaml-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Yaml implements ReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadFile($filename)
    {
        if (!is_file($filename)) {
            throw new \InvalidArgumentException('The provided filename is not a valid filename.');
        }

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
     * Decodes the provided $yaml into an object or an array.
     *
     * @param string $yaml
     *
     * @throws \InvalidArgumentException if the provided yaml is not valid
     * @throws \RuntimeException if a exception was catched
     * @throws \RuntimeException if the decoding throwed some errors
     *
     * @return array
     */
    private function decode($yaml)
    {
        try {
            if (class_exists('Symfony\Component\Yaml\Yaml')) {
                $data = Symfony\Component\Yaml\Yaml::parse($yaml);
            } else if (function_exists('spyc_load')) {
                $data = spyc_load($yaml);
            } else if (function_exists('yaml_parse')) {
                $data = yaml_parse($yaml);
            } else {
                $data = false;
            }

            if (!is_array($data) && !is_object($data)) {
                throw new \InvalidArgumentException('The provided YAML is not valid.');
            }

            if ($data !== false) {
                return $data;
            }
        } catch (Exception $e) {
            throw new \RuntimeException('Unable to parse the YAML string! :: ' . $e->getMessage());
        }

        throw new \RuntimeException('Unable to parse the YAML string! Is a YAML library configured?');
    }
}