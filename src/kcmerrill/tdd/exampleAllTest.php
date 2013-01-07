<?php
/**
 * You can do one of two things.
 *
 * Either:
 * A.) Include a file with your configuration(See phpwebunit tests folder as an example of this).
 *
 * OR
 *
 * B.) To test this single file and not a folder structure, just include the class and init a new class
 * new kcmerrill\tdd\phpwebunit
 *
 *
 * YOu shouldn't need to change any params, but the first param is the file/directory to run php unit on.
 * By default, we use the script name(see other examples of it's usage).
 *
 */

require_once __DIR__ . '/phpwebunit.php';
new kcmerrill\tdd\phpwebunit(__DIR__);



class phpwebunitExampleTest extends PHPUnit_Framework_TestCase {
    var $phpwebunit = false;
    public function setUp(){
        $this->phpwebunit = new kcmerrill\tdd\phpwebunit;
    }
    public function tearDown(){
        $this->phpwebunit = false;
    }
    public function testExample(){
        $this->assertTrue(true);
        $this->assertFalse(false);
    }
}