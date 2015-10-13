<?php
class OmenTest extends PHPUnit_Framework_TestCase
{
    public function testRepoNameStorage()
    {
        omen_repo_name('test');
        $this->assertEquals('test', omen_repo_name());
    }
    public function testRepoFolderInitAndFlush()
    {
        omen_init('test_repo');
        $this->assertTrue(file_exists('test_repo'));
        omen_flush();
        $this->assertFalse(file_exists('test_repo'));
    }
}
