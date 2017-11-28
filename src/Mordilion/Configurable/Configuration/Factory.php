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

use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Reader\ReaderInterface;
use Mordilion\Configurable\Configuration\Reader\Ini;
use Mordilion\Configurable\Configuration\Reader\Json;
use Mordilion\Configurable\Configuration\Reader\Yaml;

/**
 * Mordilion\Configurable Factory-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Factory
{
    /**
     * All possible identifier types.
     *
     * @var array
     */
    protected static $identifiers = array(
        'ini'  => 'Ini',
        'json' => 'Json',
        'yaml' => 'Yaml'
    );


    /**
     * Creates a Configuration based on the provided $data. The $data can be a filename or a string.
     *
     * @param string $data
     * @param string $identifier
     *
     * @throws \InvalidArgumentException if the provided data is not a string
     *
     * @return Configuration
     */
    public static function create($data, $identifier = null)
    {
        if (!is_string($data)) {
            throw new \InvalidArgumentException('The provided data must be a string or a filename.');
        }

        if (strlen($data) <= PHP_MAXPATHLEN && is_file($data)) {
            return static::fromFile($data, $identifier);
        }

        return static::fromString($data, $identifier);
    }

    /**
     * Creates a Configuration based on the file by the provided $filename.
     *
     * @param string $filename
     * @param string $identifier
     *
     * @throws \InvalidArgumentException if the provided filename i missing an extension
     *
     * @return Configuration
     */
    public static function fromFile($filename, $identifier = null)
    {
        if ($identifier == null) {
	    $pathinfo = pathinfo($filename);

            if (!isset($pathinfo['extension'])) {
                throw new \InvalidArgumentException('The filename "' . $filename . '" is missing an extension and cannot be auto-detected.');
            }

            $extension = strtolower($pathinfo['extension']);
            $reader = static::getReader($extension);
        } else {
            $reader = static::getReader($identifier);
        }

        return new Configuration($reader->loadFile($filename));
    }

    /**
     * Creates a Configuration based on the provided $string.
     *
     * @param string $string
     * @param string $identifier
     *
     * @return Configuration
     */
    public static function fromString($string, $identifier)
    {
        $reader = static::getReader($identifier);

        return new Configuration($reader->loadString($string));
    }

    /**
     * Returns a reader for the provided $identifier.
     *
     * @param string $identifier
     *
     * @throws \RuntimeException if the provided $identifier is not valid
     *
     * @return ReaderInterface
     */
    protected static function getReader($identifier)
    {
        if (!isset(static::$identifiers[$identifier])) {
            throw new \RuntimeException('Unknown identifier "' . $identifier . '" for a configuration file.');
        }

        $reader = static::$identifiers[$identifier];

        if (!$reader instanceof Reader\ReaderInterface) {
            // Namespace in variable needs fully qualified name
            $readerClass = __NAMESPACE__ . '\Reader\\' . $reader;
            $reader = new $readerClass();
        }

        return $reader;
    }
}
