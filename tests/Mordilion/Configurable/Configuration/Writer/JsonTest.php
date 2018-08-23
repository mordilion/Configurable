<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Writer\Json as JsonWriter;
use Mordilion\Configurable\Configuration\Reader\Json as JsonReader;

class JsonWriterTest extends TestCase
{
    public function testJsonSaveStringWithAConfiguration()
    {
        $writer = new JsonWriter();
        $reader = new JsonReader();

        $json = '{' . PHP_EOL .
            '    "Person1": {' . PHP_EOL .
            '        "name": "Mueller",' . PHP_EOL .
            '        "firstname": "Hans"' . PHP_EOL .
            '    },' . PHP_EOL .
            '    "List1": [' . PHP_EOL .
            '        "Element1",' . PHP_EOL .
            '        "Element2"' . PHP_EOL .
            '    ]' . PHP_EOL .
            '}';

        $configuration = new Configuration($reader->loadString($json));
        $result = $writer->saveString($configuration);

        $this->assertEquals($json, $result);
    }

    public function testSaveFileMethodThrowsRuntimeExceptionForNotWriteableFile()
    {
        $this->expectException(\RuntimeException::class);

        $tmpfile = tempnam(sys_get_temp_dir(), 'configurable_');

        touch($tmpfile);
        chmod($tmpfile, 0444);

        $writer = new JsonWriter();

        $writer->saveFile(array('nothing' => 'todo'), $tmpfile);
    }

    public function testJsonSaveFileWithAConfiguration()
    {
        $writer = new JsonWriter();
        $reader = new JsonReader();

        $tmpfile = tempnam(sys_get_temp_dir(), 'configurable_');

        $json = '{' . PHP_EOL .
            '    "Person1": {' . PHP_EOL .
            '        "name": "Mueller",' . PHP_EOL .
            '        "firstname": "Hans"' . PHP_EOL .
            '    },' . PHP_EOL .
            '    "List1": [' . PHP_EOL .
            '        "Element1",' . PHP_EOL .
            '        "Element2"' . PHP_EOL .
            '    ]' . PHP_EOL .
            '}';

        $configuration = new Configuration($reader->loadString($json));
        $result = $writer->saveFile($configuration, $tmpfile);

        $this->assertTrue($result);
        $this->assertEquals($json, file_get_contents($tmpfile));
    }

    public function testInternalEncodeMethodTrhowsExceptionForAnInvalidConfiguration()
    {
        $this->expectException(\InvalidArgumentException::class);

        $writer = new JsonWriter();

        $writer->saveString(12345);
    }
}
