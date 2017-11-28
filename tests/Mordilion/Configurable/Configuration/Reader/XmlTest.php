<?php

require_once 'Mordilion/Configurable/Configuration/ConfigurationInterface.php';
require_once 'Mordilion/Configurable/Configuration/Configuration.php';
require_once 'Mordilion/Configurable/Configuration/Reader/ReaderInterface.php';
require_once 'Mordilion/Configurable/Configuration/Reader/Xml.php';

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Reader\Xml;

class XmlTest extends TestCase
{
    /**
     * @dataProvider getXmlData
     */
    public function testXmlLoadStringWithValidXmlString($xml)
    {
        $reader = new Xml();

        $configuration = new Configuration($reader->loadString($xml));

        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('var1', $configurationArray);
        $this->assertArrayHasKey('var2', $configurationArray);
        $this->assertArrayHasKey('var3', $configurationArray);
        $this->assertArrayHasKey('var4', $configurationArray);
        $this->assertArrayHasKey('fruits', $configurationArray);
        $this->assertEquals($configurationArray['var1'], 'This is a text');
        $this->assertEquals($configurationArray['var2'], 123456789);
        $this->assertEquals($configurationArray['var3'], true);
        $this->assertEquals($configurationArray['var4']['var4.1'], 'Another text');
        $this->assertEquals($configurationArray['var4']['var4.2'], 12345);
        $this->assertEquals($configurationArray['var4']['var4.3'], false);
        $this->assertEquals($configurationArray['fruits'][0], 'Apple');
        $this->assertEquals($configurationArray['fruits'][1], 'Orange');
        $this->assertEquals($configurationArray['fruits'][2], 'Mango');

    }

    public function testLoadFileMethodThrowsInvalidArgumentExceptionForNotExistingFile()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = new Xml();

        $reader->loadFile('not-existing-file.xml');
    }

    public function testLoadStringMethodReturnsAnEmptyArraForEmptyString()
    {
        $reader = new Xml();

        $this->assertEquals($reader->loadString(''), array());
        $this->assertEquals($reader->loadString(null), array());
        $this->assertEquals($reader->loadString(false), array());
    }

    public function testSetDecoderMethodThrowsInvalidArgumentExceptionIfDecoderIsNotCallable()
    {
        $this->expectException(\InvalidArgumentException::class);

        $reader = new Xml();

        $reader->setDecoder('Whatever!');
    }

    public function testDecodeMethodThrowsRuntimeExceptionIfNoDecoderIsSet()
    {
        $this->expectException(\RuntimeException::class);

        $reader = new Xml();

        $reflection = new ReflectionClass($reader);
        $reflectionProperty = $reflection->getProperty('decoder');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($reader, null);

        $reader->loadString('Whatever!');
    }

    public function getXmlData()
    {
        $xml = file_get_contents(
            dirname(__FILE__) . DIRECTORY_SEPARATOR .
            '..'   . DIRECTORY_SEPARATOR .
            '..'   . DIRECTORY_SEPARATOR .
            '..'   . DIRECTORY_SEPARATOR .
            '..'   . DIRECTORY_SEPARATOR .
            'Data' . DIRECTORY_SEPARATOR .
            'test.xml'
        );

        return array(
            'valid'   => array($xml),
        );
    }
}
