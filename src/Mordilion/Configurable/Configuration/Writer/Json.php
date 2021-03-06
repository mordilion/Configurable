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
 * Mordilion\Configurable Json-Class.
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Json implements WriterInterface
{
    /**
     * {@inheritdoc}
     */
    public function saveFile($configuration, string $filename): bool
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
    public function saveString($configuration): string
    {
        return $this->encode($configuration);
    }

    /**
     * Encodes the provided $configuration into a JSON string.
     *
     * @param array|ConfigurationInterface $configuration
     *
     * @throws \InvalidArgumentException if the provided configuration is not an array or an instance of ConfigurationInterface
     * @throws \RuntimeException if the decoding throwed some errors
     *
     * @return string
     */
    private function encode($configuration): string
    {
        if ($configuration instanceof ConfigurationInterface) {
            $configuration = $configuration->toArray();
        }

        if (!is_array($configuration)) {
            throw new \InvalidArgumentException('The provided configuration is not an array or an instance of ConfigurationInterface.');
        }

        $result = json_encode($configuration, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        if ($result !== false) {
            return $result;
        }

        throw new \RuntimeException(json_last_error_msg());
    }
}
