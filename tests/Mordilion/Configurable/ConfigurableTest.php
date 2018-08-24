<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;

class ConfigurableTest extends TestCase
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

    public function testSetConfigurationWithArrayAndConfigureFalse()
    {
        $object = new TestTrait();

        $valueString = 'That\'s a String';

        $object->setConfiguration(array(
            'property1' => $valueString
        ), false);

        $configuration = $object->getConfiguration();
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertFalse(isset($object->property1));
        $this->assertEquals($configurationArray['property1'], $valueString);

        $object->configure();

        $configuration = $object->getConfiguration();
        $configurationArray = $configuration->toArray();

        $this->assertTrue(isset($object->property1));
        $this->assertEquals($object->property1, $valueString);
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

    public function testAddConfigurationWithoutPreviouseExistingConfiguration()
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

        $mock->addConfiguration($configuration);

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

    public function tstTestTraitRoutingIntegration()
    {
        $object = new TestTrait();

        $valueString = 'That\'s a String';

        $object->setConfiguration(array(
            'property1' => $valueString,
            'property2' => $valueString
        ), false);

        $configuration = $object->getConfiguration();
        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertFalse(isset($object->property1));
        $this->assertFalse(isset($object->property2));
        $this->assertEquals($configurationArray['property1'], $valueString);
        $this->assertEquals($configurationArray['property2'], $valueString);

        $object->configure();

        $configuration = $object->getConfiguration();
        $configurationArray = $configuration->toArray();

        $this->assertTrue(isset($object->property1));
        $this->assertTrue(isset($object->property2));
        $this->assertEquals($object->property1, $valueString);
        $this->assertEquals($object->property2, $valueString);
    }
}

class TestTrait
{
    use Configurable;

    public $property1 = null;


    /* Routing directly to configuration */
    public function __get($name)
    {
        if (property_exists($this, $name) || isset($this->$name)) {
            return $this->$name;
        } else if (isset($this->configuration->$name)) {
            return $this->configuration->$name;
        }

        return null;
    }

    /* Routing directly to configuration */
    public function __set($name, $value)
    {
        if (property_exists($this, $name) || isset($this->$name)) {
            $this->$name = $value;
        } else if (isset($this->configuration->$name)) {
            $this->configuration->$name = $value;
        }
    }
}
