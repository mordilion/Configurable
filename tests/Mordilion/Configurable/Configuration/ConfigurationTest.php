<?php

require_once 'Mordilion/Configurable/Configuration/ConfigurationInterface.php';
require_once 'Mordilion/Configurable/Configuration/Configuration.php';

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;

class ConfigurationTest extends TestCase
{
    public function testConfigurationConstructorWithArray()
    {
        $configuration = new Configuration(array(
            'param1' => true,
            'param2' => new \DateTime()
        ));

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testConfigurationConstructorWithObject()
    {
        $configuration = new Configuration(new Configuration(array(
            'param1' => true,
            'param2' => new \DateTime()
        )));

        $this->assertInstanceOf(Configuration::class, $configuration);
    }

    public function testConfigurationConstructorWithDateTimeObject()
    {
        $configuration = new Configuration(new \DateTime('2017-01-25 14:00:00', new \DateTimeZone('America/Chicago')));
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertEquals($configurationArray['date'], '2017-01-25 14:00:00.000000');
        $this->assertEquals($configurationArray['timezone_type'], 3);
        $this->assertEquals($configurationArray['timezone'], 'America/Chicago');
    }

    public function testConfigurationConstructorThrowsExceptionForNull()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration(null);
    }

    public function testConfigurationConstructorThrowsExceptionForString()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration('Text');
    }

    public function testConfigurationConstructorThrowsExceptionForInteger()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration(123456);
    }

    public function testConfigurationConfigure()
    {
        $timezone = 'Europe/Berlin';
        $configuration = new Configuration(array(
            'timezone' => new \DateTimeZone($timezone)
        ));

        $object = new \DateTime('now', new \DateTimeZone('America/Chicago'));

        $configuration->configure($object);

        $this->assertEquals($timezone, $object->getTimezone()->getName());
    }

    public function testConfigurationConfigureThrowsExceptionForNull()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration(array(
            'param1' => 'Text'
        ));

        $configuration->configure(null);
    }

    public function testConfigurationConfigureThrowsExceptionForString()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration(array(
            'param1' => 'Text'
        ));

        $configuration->configure('Text');
    }

    public function testConfigurationConfigureThrowsExceptionForInteger()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration(array(
            'param1' => 'Text'
        ));

        $configuration->configure(123456789);
    }
}