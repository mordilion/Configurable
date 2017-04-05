<?php

require_once 'Mordilion/Configurable/Configurable.php';
require_once 'Mordilion/Configurable/Configuration/ConfigurationInterface.php';
require_once 'Mordilion/Configurable/Configuration/Configuration.php';

use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;

//class ConfigurableTest extends TestCase
class ConfigurableTest extends PHPUnit_Framework_TestCase
{
    public function testSetConfigurationWithArray()
    {
        $mock = $this->getMockForTrait(Configurable::class);

        $valueString  = 'That\'s a String';
        $valueInteger = 123456789;
        $valueObject  = new \DateTime();
        $valueBoolean = true;

        $mock->setConfiguration(array(
            'param1' => $valueString,
            'param2' => $valueInteger,
            'param3' => $valueObject,
            'param4' => $valueBoolean
        ));

        $configuration = $mock->getConfiguration();
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('param1', $configurationArray);
        $this->assertArrayHasKey('param2', $configurationArray);
        $this->assertArrayHasKey('param3', $configurationArray);
        $this->assertArrayHasKey('param4', $configurationArray);
        $this->assertEquals($configurationArray['param1'], $valueString);
        $this->assertEquals($configurationArray['param2'], $valueInteger);
        $this->assertEquals($configurationArray['param3'], $valueObject);
        $this->assertEquals($configurationArray['param4'], $valueBoolean);
    }

    public function testSetConfigurationWithConfigurationObject()
    {
        $mock = $this->getMockForTrait(Configurable::class);

        $valueString  = 'That\'s a String';
        $valueInteger = 123456789;
        $valueObject  = new \DateTime();
        $valueBoolean = true;

        $configuration = new Configuration();
        $configuration->set('param1', $valueString);
        $configuration->param2 = $valueInteger;
        $configuration->set('param3', $valueObject);
        $configuration->param4 = $valueBoolean;

        $mock->setConfiguration($configuration);

        $configuration = $mock->getConfiguration();
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('param1', $configurationArray);
        $this->assertArrayHasKey('param2', $configurationArray);
        $this->assertArrayHasKey('param3', $configurationArray);
        $this->assertArrayHasKey('param4', $configurationArray);
        $this->assertEquals($configurationArray['param1'], $valueString);
        $this->assertEquals($configurationArray['param2'], $valueInteger);
        $this->assertEquals($configurationArray['param3'], $valueObject);
        $this->assertEquals($configurationArray['param4'], $valueBoolean);
    }

    public function testAddConfigurationWithArray()
    {
        $mock = $this->getMockForTrait(Configurable::class);

        $valueString  = 'That\'s a String';
        $valueInteger = 123456789;
        $valueObject  = new \DateTime();
        $valueBoolean = true;

        $mock->setConfiguration(array(
            'param1' => $valueString,
            'param2' => $valueInteger,
            'param3' => $valueObject,
            'param4' => $valueBoolean
        ));

        $mock->addConfiguration(array(
            'param5' => $valueString,
            'param6' => $valueInteger
        ));

        $configuration = $mock->getConfiguration();
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('param1', $configurationArray);
        $this->assertArrayHasKey('param2', $configurationArray);
        $this->assertArrayHasKey('param3', $configurationArray);
        $this->assertArrayHasKey('param4', $configurationArray);
        $this->assertArrayHasKey('param5', $configurationArray);
        $this->assertArrayHasKey('param6', $configurationArray);
        $this->assertEquals($configurationArray['param1'], $valueString);
        $this->assertEquals($configurationArray['param2'], $valueInteger);
        $this->assertEquals($configurationArray['param3'], $valueObject);
        $this->assertEquals($configurationArray['param4'], $valueBoolean);
        $this->assertEquals($configurationArray['param5'], $valueString);
        $this->assertEquals($configurationArray['param6'], $valueInteger);
    }

    public function testAddConfigurationWithConfigurationObject()
    {
        $mock = $this->getMockForTrait(Configurable::class);

        $valueString  = 'That\'s a String';
        $valueInteger = 123456789;
        $valueObject  = new \DateTime();
        $valueBoolean = true;

        $configuration = new Configuration();
        $configuration->set('param1', $valueString);
        $configuration->param2 = $valueInteger;
        $configuration->set('param3', $valueObject);
        $configuration->param4 = $valueBoolean;

        $mock->setConfiguration($configuration);

        $configuration = new Configuration();
        $configuration->set('param5', $valueString);
        $configuration->param6 = $valueInteger;

        $mock->addConfiguration($configuration);

        $configuration = $mock->getConfiguration();
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('param1', $configurationArray);
        $this->assertArrayHasKey('param2', $configurationArray);
        $this->assertArrayHasKey('param3', $configurationArray);
        $this->assertArrayHasKey('param4', $configurationArray);
        $this->assertArrayHasKey('param5', $configurationArray);
        $this->assertArrayHasKey('param6', $configurationArray);
        $this->assertEquals($configurationArray['param1'], $valueString);
        $this->assertEquals($configurationArray['param2'], $valueInteger);
        $this->assertEquals($configurationArray['param3'], $valueObject);
        $this->assertEquals($configurationArray['param4'], $valueBoolean);
        $this->assertEquals($configurationArray['param5'], $valueString);
        $this->assertEquals($configurationArray['param6'], $valueInteger);
    }
}