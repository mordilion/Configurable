<?php

use PHPUnit\Framework\TestCase;

use Mordilion\Configurable\Configurable;
use Mordilion\Configurable\Configuration\Configuration;
use Mordilion\Configurable\Configuration\Writer\Yaml as YamlWriter;
use Mordilion\Configurable\Configuration\Reader\Yaml as YamlReader;

class YamlWriterTest extends TestCase
{
    public function testYamlSaveStringWithAConfiguration()
    {
        $writer = new YamlWriter();
        $reader = new YamlReader();

        $yaml = 'Person1:' . PHP_EOL .
            '  name: Mueller' . PHP_EOL .
            '  firstname: Hans' . PHP_EOL .
            'Person2:' . PHP_EOL .
            '  name: Schmitz,' . PHP_EOL .
            '  firstname: Peter' . PHP_EOL .
            'List1:' . PHP_EOL .
            '  - Element1,' . PHP_EOL .
            '  - Element2' . PHP_EOL .
            'List2:' . PHP_EOL .
            '  - Ele1' . PHP_EOL .
            '  - Ele2' . PHP_EOL .
            '  - Ele3' . PHP_EOL;

        $writer->setEncoder('spyc_dump');

        $configuration = new Configuration($reader->loadString($yaml));
        $result = $writer->saveString($configuration);

        $this->assertEquals($yaml, $result);
    }

    public function testSaveFileMethodThrowsRuntimeExceptionForNotWriteableFile()
    {
        $this->expectException(\RuntimeException::class);

        $tmpfile = tempnam(sys_get_temp_dir(), 'configurable_');

        touch($tmpfile);
        chmod($tmpfile, 0444);

        $writer = new YamlWriter();

        $writer->saveFile(array('nothing' => 'todo'), $tmpfile);
    }

    public function testYamlSaveFileWithAConfiguration()
    {
        $writer = new YamlWriter();
        $reader = new YamlReader();

        $tmpfile = tempnam(sys_get_temp_dir(), 'configurable_');

        $yaml = 'Person1:' . PHP_EOL .
            '  name: Mueller' . PHP_EOL .
            '  firstname: Hans' . PHP_EOL .
            'Person2:' . PHP_EOL .
            '  name: Schmitz,' . PHP_EOL .
            '  firstname: Peter' . PHP_EOL .
            'List1:' . PHP_EOL .
            '  - Element1,' . PHP_EOL .
            '  - Element2' . PHP_EOL .
            'List2:' . PHP_EOL .
            '  - Ele1' . PHP_EOL .
            '  - Ele2' . PHP_EOL .
            '  - Ele3' . PHP_EOL;

        $writer->setEncoder('spyc_dump');

        $configuration = new Configuration($reader->loadString($yaml));
        $result = $writer->saveFile($configuration, $tmpfile);

        $this->assertTrue($result);
        $this->assertEquals($yaml, file_get_contents($tmpfile));
    }

    public function testInternalEncodeMethodTrhowsExceptionForAnInvalidConfiguration()
    {
        $this->expectException(\InvalidArgumentException::class);

        $writer = new YamlWriter();

        $writer->saveString(12345);
    }

    public function testSetEncoderAndEncoderParameters()
    {
        $writer = new YamlWriter();

        $writer->setEncoder('spyc_dump')
            ->setEncoderParameters(array());

        $this->assertEquals($writer->getEncoder(), 'spyc_dump');
        $this->assertEquals($writer->getEncoderParameters(), array());
    }

    public function testSetEncoderThrowsAnInvaludArgumentExceptionIfSomethingNotCallableIsProvided()
    {
        $this->expectException(\InvalidArgumentException::class);

        $writer = new YamlWriter();

        $writer->setEncoder(12345);
    }
}
