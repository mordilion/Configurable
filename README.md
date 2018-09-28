[![Travis](https://img.shields.io/travis/mordilion/Configurable.svg?branch=master)](https://travis-ci.org/mordilion/Configurable)
[![Packagist](https://img.shields.io/packagist/dt/mordilion/configurable.svg)](https://packagist.org/packages/mordilion/configurable)

# Configurable

## Description

Configurable is a small library to make each class configurable with different kinds of configuration objects. Internaly does it create an own type of configuration object to make it more reusable.

## Requirements for YAML support

To use that package with YAML you have to have at least one of the following libraries in your installation.

Symfony YAML-Component https://github.com/symfony/yaml

Spyc (Simple-PHP-YAML-Class) https://github.com/mustangostang/spyc

PECL YAML http://php.net/manual/en/book.yaml.php

## Basic Example

```php
<?php

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;

class Something
{
    /**
     * Use the following traits.
     */
    use Configurable;
    
    
    /**
     * Default configuration settings.
     *
     * @var array
     */
    private $defaults = array(
        'setting1' => 'http://www.devjunkie.de',
        'setting2' => null,
        'setting3' => 12345
    );
    
    /**
     * A public property.
     *
     * @var integer
     */
    public $setting3;
    
    /**
     * Constructor.
     *
     * The provided $configuration will configure the object.
     *
     * @param mixed $configuration
     *
     * @return void
     */
    public function __construct($configuration = null)
    {
        $this->defaults['setting2'] = new \DateTime('now', new \DateTimeZone('America/Chicago'));
        
        $this->setConfiguration(new Configuration($this->defaults));
        
        if ($configuration != null) {
            $this->addConfiguration(new Configuration($configuration));
        }
    }
    
    /**
     * Sets the value for setting1.
     *
     * @param string $value
     *
     * @return Something
     */
    public function setSetting1($value)
    {
        $this->configuration->set('setting1', $value);
        
        return $this;
    }
    
    /**
     * Sets the value for setting2.
     *
     * @param \DateTime $value
     *
     * @return Something
     */
    public function setSetting2(\DateTime $value)
    {
        $this->configuration->set('setting2', $value); // or $this->configuration->setting2 = $value;
        
        return $this;
    }
}
```

## Routing Example

Use __get and __set to route through the object directly to the configuration.

```php
<?php

use Mordilion\Configurable\Configurable;

class Something
{
    /**
     * Use the following traits.
     */
    use Configurable;

    /**
     * A public property.
     *
     * @var mixed
     */
    public $property1;


    /**
     * Routing requests directly to the configuration if needed.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name) || isset($this->$name)) {
            return $this->$name;
        } else if (isset($this->configuration->$name)) {
            return $this->configuration->$name;
        }

        return null;
    }

    /**
     * Routing requests directly to the configuration if needed.
     * 
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name) || isset($this->$name)) {
            $this->$name = $value;
        } else if (isset($this->configuration->$name)) {
            $this->configuration->$name = $value;
        }
    }
}

// { ... }

$object = new Something();
$obejct->setConfiguration(array('property1' => 'some text', 'property2' => 'some other text')); // simple use
echo $object->property1 . ' -- ' . $object->property2; // => "some text -- some other text"
```
