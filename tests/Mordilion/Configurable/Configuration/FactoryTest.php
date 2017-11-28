<?php

require_once 'Mordilion/Configurable/Configuration/Factory.php';

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

        $data = "
            var1 = '" . $var1 . "'
            var2 = " . $var2 . "
            var3 = " . $var3 . "
        ";

        $configuration = Factory::create($data, 'ini');
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertEquals($configurationArray['var1'], $var1);
        $this->assertEquals($configurationArray['var2'], $var2);
        $this->assertEquals($configurationArray['var3'], $var3);
    }

    public function testFactoryCreateWithIniFile()
    {
        $configuration = Factory::create(__DIR__ . '/test.ini');

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
        $configuration = Factory::create(__DIR__ . '/test.json');

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testFactoryCreateWithYmlData()
    {
        $var1 = "This is a text!";
        $var2 = 12345;
        $var3 = true;

        $data = "
            var1: " . $var1 . "
            var2: " . $var2 . "
            var3: " . $var3 . "
            var4:
                - Apple
                - Mango
        ";

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
        $configuration = Factory::create(__DIR__ . '/test.yml');

        $this->assertInstanceOf(Configuration::class, $configuration);
    }
}
