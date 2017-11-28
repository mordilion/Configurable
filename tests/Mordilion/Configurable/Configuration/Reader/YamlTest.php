<?php

require_once 'Mordilion/Configurable/Configuration/ConfigurationInterface.php';
require_once 'Mordilion/Configurable/Configuration/Configuration.php';
require_once 'Mordilion/Configurable/Configuration/Reader/ReaderInterface.php';
require_once 'Mordilion/Configurable/Configuration/Reader/Yaml.php';

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Reader\Yaml;

class YamlTest extends TestCase
{
    public function testYamlLoadStringWithValidYamlString()
    {
        $reader = new Yaml();

        $configuration = new Configuration($reader->loadString(
            '# one line comment' . PHP_EOL .
            'Person1:' . PHP_EOL .
            '  name: Mueller' . PHP_EOL .
            '  firstname: Hans' . PHP_EOL .
            'Person2: {name: Schmitz, firstname: Peter}' . PHP_EOL .
            'List1: [Element1, Element2]' . PHP_EOL .
            'List2:' . PHP_EOL .
            '  - Ele1' . PHP_EOL .
            '  - Ele2' . PHP_EOL .
            '  - Ele3'
        ));

        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('Person1', $configurationArray);
        $this->assertArrayHasKey('Person2', $configurationArray);
        $this->assertArrayHasKey('List1', $configurationArray);
        $this->assertArrayHasKey('List2', $configurationArray);
        $this->assertEquals($configurationArray['Person1']['name'], 'Mueller');
        $this->assertEquals($configurationArray['Person1']['firstname'], 'Hans');
        $this->assertEquals($configurationArray['Person2']['name'], 'Schmitz');
        $this->assertEquals($configurationArray['Person2']['firstname'], 'Peter');
        $this->assertEquals($configurationArray['List1'][0], 'Element1');
        $this->assertEquals($configurationArray['List1'][1], 'Element2');
        $this->assertEquals($configurationArray['List2'][0], 'Ele1');
        $this->assertEquals($configurationArray['List2'][1], 'Ele2');
        $this->assertEquals($configurationArray['List2'][2], 'Ele3');
    }

    public function testLoadFileMethodThrowsInvalidArgumentExceptionForNotExistingFile()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = new Yaml();

        $reader->loadFile('not-existing-file.yml');
    }

    public function testLoadStringMethodReturnsAnEmptyArraForEmptyString()
    {
        $reader = new Yaml();

        $this->assertEquals($reader->loadString(''), array());
        $this->assertEquals($reader->loadString(null), array());
        $this->assertEquals($reader->loadString(false), array());
    }

    public function testSetDecoderMethodThrowsInvalidArgumentExceptionIfDecoderIsNotCallable()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = new Yaml();

        $reader->setDecoder('Whatever!');
    }

    public function testDecodeMethodThrowsRuntimeExceptionIfNoDecoderIsSet()
    {
        $this->expectException(\RuntimeException::class);

        $reader = new Yaml();

        $reflection = new ReflectionClass($reader);
        $reflectionProperty = $reflection->getProperty('decoder');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($reader, null);

        $reader->loadString('Whatever!');
    }
}