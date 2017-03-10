# Configurable
## Description
Configurable is a small library to make each class configurable with different kinds of configuration objects. Internaly does it create an own type of configuration object to make it more reusable.
## Example
```php
<?php

use Mordilion\Configurable\ConfigurableAbstract;
use Mordilion\Configurable\Configuration;

class Something extends ConfigurableAbstract
{
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
    
    private $setting1;
    public $setting2;
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
        $this->defaults['setting2'] = new DateTime('now', 'America/Chicago');
        
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
        $this->setting1 = $value;
        
        return $this;
    }
}
```
