<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Writer\Ini as IniWriter;
use Mordilion\Configurable\Configuration\Reader\Ini as IniReader;

class IniWriterTest extends TestCase
{
    public function testIniSaveStringWithAConfiguration()
    {
        $writer = new IniWriter();
        $reader = new IniReader();

        $ini = 'key1 = 123' . PHP_EOL .
            'key2 = "Value 2"' . PHP_EOL . PHP_EOL .
            '[Section 1]' . PHP_EOL .
            'key3 = "value 3"' . PHP_EOL .
            'key4[0] = "value 4"' . PHP_EOL .
            'key4[1] = "value 5"' . PHP_EOL . PHP_EOL .
            '[Section 2]' . PHP_EOL .
            'key5[a] = "value 6"' . PHP_EOL .
            'key5[b] = "value 7"';
            //'[Section 2]' . PHP_EOL .
            //'key5[0][a] = "value 6"' . PHP_EOL . // multidimensional array's are not allowed ... YET!
            //'key5[0][b] = "value 7"' . PHP_EOL;

        $configuration = new Configuration($reader->loadString($ini));
        $result = $writer->saveString($configuration);

        $this->assertEquals($ini, $result);
    }

    public function testSaveFileMethodThrowsRuntimeExceptionForNotWriteableFile()
    {
        $this->expectException(\RuntimeException::class);

        $tmpfile = tempnam(sys_get_temp_dir(), 'configurable_');

        touch($tmpfile);
        chmod($tmpfile, 0444);

        $writer = new IniWriter();

        $writer->saveFile(array('nothing' => 'todo'), $tmpfile);
    }

    public function testIniSaveFileWithAConfiguration()
    {
        $writer = new IniWriter();
        $reader = new IniReader();

        $tmpfile = tempnam(sys_get_temp_dir(), 'configurable_');

        $ini = 'key1 = 123' . PHP_EOL .
            'key2 = "Value 2"' . PHP_EOL . PHP_EOL .
            '[Section 1]' . PHP_EOL .
            'key3 = "value 3"' . PHP_EOL .
            'key4[0] = "value 4"' . PHP_EOL .
            'key4[1] = "value 5"' . PHP_EOL . PHP_EOL .
            '[Section 2]' . PHP_EOL .
            'key5[a] = "value 6"' . PHP_EOL .
            'key5[b] = "value 7"';
            //'[Section 2]' . PHP_EOL .
            //'key5[0][a] = "value 6"' . PHP_EOL . // multidimensional array's are not allowed ... YET!
            //'key5[0][b] = "value 7"' . PHP_EOL;

        $configuration = new Configuration($reader->loadString($ini));
        $result = $writer->saveFile($configuration, $tmpfile);

        $this->assertTrue($result);
        $this->assertEquals($ini, file_get_contents($tmpfile));
    }

    public function testInternalEncodeMethodTrhowsExceptionForAnInvalidConfiguration()
    {
        $this->expectException(\InvalidArgumentException::class);

        $writer = new IniWriter();

        $writer->saveString(12345);
    }
}
