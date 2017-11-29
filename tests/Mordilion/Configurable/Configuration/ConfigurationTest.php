<?php

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

    public function testConfigurationThroughMagicSetAndGetMethod()
    {
        $configuration = new Configuration();

        $configuration->param1 = "Test1";
        $configuration->param2 = 12345;

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertTrue(isset($configuration->param1));
        $this->assertFalse(isset($configuration->param3));
        $this->assertEquals($configuration->param1, "Test1");
        $this->assertEquals($configuration->param2, 12345);
        $this->assertEquals($configuration->count(), 2);
        unset($configuration->param1);
        $this->assertFalse(isset($configuration->param1));
        $this->assertEquals($configuration->count(), 1);
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

    public function testGetMethodReturnsProvidedDefaultValue()
    {
        $configuration = new Configuration();

        $this->assertEquals($configuration->get('unknown', 'X-Mas'), 'X-Mas');
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

    public function testMergeMethodWithNestedConfiguration()
    {
        $configuration = new Configuration(array(
            'cfg' => new Configuration(array(
                'param1' => 'Test1'
            ))
        ));

        $configuration2 = new Configuration(array(
            'cfg' => new Configuration(array(
                'param1' => 'Test2'
            ))
        ));

        $configuration->merge($configuration2);
        $configurationArray = $configuration->toArray();

        $this->assertEquals($configuration->cfg->param1, 'Test2');
        $this->assertEquals($configurationArray['cfg']['param1'], 'Test2');
    }
}
