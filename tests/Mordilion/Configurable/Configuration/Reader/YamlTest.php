<?php

require_once 'Mordilion/Configurable/Configuration/ConfigurationInterface.php';
require_once 'Mordilion/Configurable/Configuration/Configuration.php';
require_once 'Mordilion/Configurable/Configuration/Reader/ReaderInterface.php';
require_once 'Mordilion/Configurable/Configuration/Reader/Yaml.php';

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Reader\Yaml;

class YamlTest extends TestCase
{
    public function testYamlLoadStringWithValidYamlString()
    {
        $reader = new Yaml();

        $configuration = new Configuration($reader->loadString(
            '# one line comment' . PHP_EOL .
            'Person1:' . PHP_EOL .
            '  name: Mueller' . PHP_EOL .
            '  firstname: Hans' . PHP_EOL .
            'Person2: {name: Schmitz, firstname: Peter}' . PHP_EOL .
            'List1: [Element1, Element2]' . PHP_EOL .
            'List2:' . PHP_EOL .
            '  - Ele1' . PHP_EOL .
            '  - Ele2' . PHP_EOL .
            '  - Ele3'
        ));

        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('variable1', $configurationArray);
        $this->assertArrayHasKey('variable2', $configurationArray);
        $this->assertArrayHasKey('variable3', $configurationArray);
        $this->assertEquals($configurationArray['variable1'], 123456);
        $this->assertEquals($configurationArray['variable2'], "This is a Test-String");
        $this->assertEquals($configurationArray['variable3'], true);
    }
}