<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Reader\Ini;

class IniReaderTest extends TestCase
{
    public function testIniLoadStringWithValidIniStringWithoutSections()
    {
        $reader = new Ini();

        $configuration = new Configuration($reader->loadString(
            'variable1 = 123456' . PHP_EOL .
            'variable2 = "This is a Test-String"' . PHP_EOL .
            'variable3 = true'
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

    public function testIniLoadStringWithValidIniStringWithSections()
    {
        $reader = new Ini();

        $configuration = new Configuration($reader->loadString(
            '[Section1]' . PHP_EOL .
            'variable1 = 123456' . PHP_EOL .
            'variable2 = "This is a Test-String"' . PHP_EOL .
            '[Section2]' . PHP_EOL .
            'variable3 = true'
        ));

        $configurationArray = $configuration->toArray();

        $this->assertInstanceOf(Configuration::class, $configuration);
        $this->assertArrayHasKey('Section1', $configurationArray);
        $this->assertArrayHasKey('Section2', $configurationArray);
        $this->assertArrayHasKey('variable1', $configurationArray['Section1']);
        $this->assertArrayHasKey('variable2', $configurationArray['Section1']);
        $this->assertArrayHasKey('variable3', $configurationArray['Section2']);
        $this->assertEquals($configurationArray['Section1']['variable1'], 123456);
        $this->assertEquals($configurationArray['Section1']['variable2'], "This is a Test-String");
        $this->assertEquals($configurationArray['Section2']['variable3'], true);
    }

    public function testLoadFileMethodThrowsRuntimeExceptionForNotReadableFile()
    {
        $this->expectException(\RuntimeException::class);

        $reader = new Ini();

        $reader->loadFile(TEST_ROOT_PATH . '/not-readable.file');
    }

    public function testLoadStringMethodReturnsAnEmptyArraForEmptyString()
    {
        $reader = new Ini();

        $this->assertEquals($reader->loadString(''), array());
        $this->assertEquals($reader->loadString(null), array());
        $this->assertEquals($reader->loadString(false), array());
    }
}
