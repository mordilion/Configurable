<?php

require_once 'Mordilion/Configurable/Configuration/ConfigurationInterface.php';
require_once 'Mordilion/Configurable/Configuration/Configuration.php';
require_once 'Mordilion/Configurable/Configuration/Reader/ReaderInterface.php';
require_once 'Mordilion/Configurable/Configuration/Reader/Json.php';

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Reader\Json;

class JsonTest extends TestCase
{
    public function testJsonLoadStringWithValidJsonString()
    {
        $reader = new Json();

        $configuration = new Configuration($reader->loadString(
            '{' . PHP_EOL .
            '    "Person1": {' . PHP_EOL .
            '        "name": "Mueller",' . PHP_EOL .
            '        "firstname": "Hans"' . PHP_EOL .
            '    },' . PHP_EOL .
            '    "List1": ["Element1", "Element2"]' . PHP_EOL .
            '}'
        ));

        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('Person1', $configurationArray);
        $this->assertArrayHasKey('List1', $configurationArray);
        $this->assertEquals($configurationArray['Person1']['name'], 'Mueller');
        $this->assertEquals($configurationArray['Person1']['firstname'], 'Hans');
        $this->assertEquals($configurationArray['List1'][0], 'Element1');
        $this->assertEquals($configurationArray['List1'][1], 'Element2');
    }
}