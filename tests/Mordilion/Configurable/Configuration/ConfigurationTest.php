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

    public function testConfigurationConstructorThrowsExceptionForNull()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration(null);
    }

    public function testConfigurationConstructorThrowsExceptionForObject()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configuration = new Configuration(new \DateTime());
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