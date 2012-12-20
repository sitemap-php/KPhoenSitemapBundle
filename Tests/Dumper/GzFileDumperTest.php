<?php

namespace KPhoen\SitemapBundle\Tests\Dumper;

use KPhoen\SitemapBundle\Dumper\GzFileDumper;


class GzFileDumperTest extends \PHPUnit_Framework_TestCase
{
    protected $file;


    public function setUp()
    {
        $this->file = sys_get_temp_dir() . '/KPhoenSitemapBundleGzFileDumperTest';
    }

    public function tearDown()
    {
        unlink($this->file);
    }

    public function testDumper()
    {
        $dumper = new GzFileDumper($this->file);

        $dumper->dump('joe');
        $dumper->dump('-hell yeah!');

        $this->assertTrue(file_exists($this->file));
        unset($dumper); // force the dumper to close the file

        // readgzfile reads the content of the file and also prints it...
        ob_start();
        readgzfile($this->file);
        $content = ob_get_contents();
        ob_clean();

        $this->assertEquals('joe-hell yeah!', $content);
        $this->assertNotEquals('joe-hell yeah!', file_get_contents($this->file), 'The file\'s content is compressed');
    }
}
