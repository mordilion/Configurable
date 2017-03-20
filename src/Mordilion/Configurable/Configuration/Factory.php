<?php

/**
 * This file is part of the Mordilion\Configurable package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\Configurable\Configuration;

/**
 * Mordilion\Configurable Factory-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Factory
{
    protected static $identifiers = array(
        'ini'  => 'Ini',
        'json' => 'Json',
        'yaml' => 'Yaml'
    );


    public static function create($data)
    {
        if (!is_string($data)) {
            throw new \InvalidArgumentException('The provided data must be a string or a filename.');
        }

        if (strlen($data) <= PHP_MAXPATHLEN && is_file($data)) {
            return static::fromFile($data);
        }

        return static::fromString($data);
    }

    public static function fromFile($filename)
    {
        $pathinfo = pathinfo($fielname);

        if (!isset($pathinfo['extension'])) {
            throw new \InvalidArgumentException('The filename "' . $filename . '" is missing an extension and cannot be auto-detected.');
        }

        $extension = strtolower($pathinfo['extension']);
        $reader = static::getReader($extension);

        return new Configuration($reader->load($filename));
    }

    public static function fromString($string)
    {
        $identifier = null;

        // is json format?
        $decoded = json_decode($string, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            $identifier = 'json';
        }

        if ($identifier == null) {
            try {
                $decoded    = yaml_parse($string);
                $identifier = 'yaml'
            } catch (Exception $e) {
            }
        }

        $reader = static::getReader($extension);
    }

    protected static function getReader($identifier)
    {
        if (!isset(static::$identifiers[$identifier])) {
            throw new \RuntimeException('Unknown identifier "' . $identifier . '" for a configuration file.');
        }

        $reader = static::$identifiers[$identifier];

        if (!$reader instanceof Reader\ReaderInterface) {
            $reader = new Reader\$reader();
        }

        return $reader;
    }

    protected static function getStringType($string)
    {

    }
}