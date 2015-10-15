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
    public function testOmenTimestampFilename()
    {
        $contact = new Contact('sms', '555-555-5555', 15);
        $check   = $this->failingCheck();

        $this->assertEquals(
            'test_repo/always_fails.sms.555_555_5555',
            omen_timestamp_filename($check,$contact)
        );
    }


    public function failingCheck()
    {
        return new Check('always_fails', function() {
            return new Status(false, 'This check just always fails.');
        });
    }
}
