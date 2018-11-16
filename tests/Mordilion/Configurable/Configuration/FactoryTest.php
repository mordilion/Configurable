<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Factory;

class FactoryTest extends TestCase
{
    public function testFactoryCreateWithIniData()
    {
        $var1 = "This is a text!";
        $var2 = 12345;
        $var3 = "Another text";

        $data = "var1 = '" . $var1 . "' " . PHP_EOL
            . "var2 = " . $var2 . PHP_EOL
            . "var3 = " . $var3;

        $configuration = Factory::create($data, 'ini');
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertEquals($configurationArray['var1'], $var1);
        $this->assertEquals($configurationArray['var2'], $var2);
        $this->assertEquals($configurationArray['var3'], $var3);
    }

    public function testFactoryCreateWithIniFile()
    {
        $configuration = Factory::create(__DIR__ . '/../../../Data/test.ini');

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testFactoryCreateWithJsonData()
    {
        $var1 = "This is a text!";
        $var2 = 12345;
        $var3 = true;
        $var4 = array('Text', 123, false);

        $data = array(
            'var1' => $var1,
            'var2' => $var2,
            'var3' => $var3,
            'var4' => $var4
        );

        $configuration = Factory::create(json_encode($data), 'json');
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertEquals($configurationArray['var1'], $var1);
        $this->assertEquals($configurationArray['var2'], $var2);
        $this->assertEquals($configurationArray['var3'], $var3);
        $this->assertEquals($configurationArray['var4'], $var4);
    }

    public function testFactoryCreateWithJsonFile()
    {
        $configuration = Factory::create(__DIR__ . '/../../../Data/test.json');

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testFactoryCreateWithYmlData()
    {
        $var1 = "This is a text!";
        $var2 = 12345;
        $var3 = true;

        $data = "var1: " . $var1 . PHP_EOL
            . "var2: " . $var2 . PHP_EOL
            . "var3: " . $var3 . PHP_EOL
            . "var4:" . PHP_EOL
            . "  - Apple" . PHP_EOL
            . "  - Mango";

        $configuration = Factory::create($data, 'yaml');
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertEquals($configurationArray['var1'], $var1);
        $this->assertEquals($configurationArray['var2'], $var2);
        $this->assertEquals($configurationArray['var3'], $var3);
        $this->assertEquals($configurationArray['var4'], array('Apple', 'Mango'));
    }

    public function testFactoryCreateWithYmlFile()
    {
        $configuration = Factory::create(__DIR__ . '/../../../Data/test.yml');

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testFactoryCreateWithXmlData()
    {
        $var1 = "This is a text!";
        $var2 = 12345;
        $var3 = true;

        $data = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"
            . "<root>" . PHP_EOL
            . "  <var1>" . $var1 . "</var1>" . PHP_EOL
            . "  <var2>" . $var2 . "</var2>" . PHP_EOL
            . "  <var3>" . $var3 . "</var3>" . PHP_EOL
            . "  <fruits>Apple</fruits>" . PHP_EOL
            . "  <fruits>Mango</fruits>" . PHP_EOL
            . "</root>";

        $configuration = Factory::create($data, 'xml');
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertEquals($configurationArray['var1'], $var1);
        $this->assertEquals($configurationArray['var2'], $var2);
        $this->assertEquals($configurationArray['var3'], $var3);
        $this->assertEquals($configurationArray['fruits'], array('Apple', 'Mango'));
    }

    public function testFactoryCreateWithXmlFile()
    {
        $configuration = Factory::create(__DIR__ . '/../../../Data/test.xml');

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testFactoryGetReaderThrowsRuntimeExceptionForInvalidIdentifier()
    {
        $this->expectException(\RuntimeException::class);

        $configuration = Factory::create('', 'none');
    }

    public function testFactoryFromFileWithoutExtension()
    {
        $configuration = Factory::create(__DIR__ . '/../../../Data/json-file', 'json');

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testFactoryFromFileThrowsInvalidArgumentExceptionForFileWithoutExtension()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = Factory::create(__DIR__ . '/../../../Data/json-file');
    }

    public function testFactoryCreateThrowsInvalidArgumentExceptionIfNoStringIsProvided()
    {
        $this->expectException(\TypeError::class);

        $configuration = Factory::create(null);
    }
}
