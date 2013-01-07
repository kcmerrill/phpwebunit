<?php

require_once __DIR__ . '/phpwebunit.php';
require_once __DIR__ . '/../src/kcmerrill/tdd/phpwebunit.php';

class phpwebunitTest extends PHPUnit_Framework_TestCase {
    var $phpwebunit = false;
    public function setUp(){
        $this->phpwebunit = new kcmerrill\tdd\phpwebunit;
    }
    public function tearDown(){
        $this->phpwebunit = false;
    }

    public function testIsObject(){
        $this->assertTrue(is_object($this->phpwebunit));
    }

    public function testPHPWebUnitConfig(){
        //Make sure that bin was set by default
        $this->assertEquals('phpunit', $this->phpwebunit['bin']);
        $this->assertEquals('SCRIPT_FILENAME', $this->phpwebunit['script_name']);

        //Ok, lets make sure that offsets are working
        $newphpwebunit = new kcmerrill\tdd\phpwebunit;
        $newphpwebunit['bin'] = 'kcwazhere';
        $this->assertEquals('kcwazhere', $newphpwebunit['bin']);

        //Ok, now lets move on to make sure that our constructor is rocking
        $newphpwebunit = new kcmerrill\tdd\phpwebunit('something', array('bin'=>'kcwazhereagain'));
        $this->assertEquals('kcwazhereagain', $newphpwebunit['bin']);
    }

    public function testSystemUnderTest(){
        //Lets pass through this file, and it should return this file basically
        //Because it passes is_file
        $sut = $this->phpwebunit->setSystemUnderTest(__FILE__);
        $this->assertEquals(__FILE__, $sut);
        $this->assertEquals(__FILE__, $this->phpwebunit['sut']);

        //Ok, now lets test the fallback plan
        $sut = $this->phpwebunit->setSystemUnderTest(uniqid('does_not_exist'), array('SCRIPT_FILENAME'=>'SCRIPTNAMEGOESHERE'));
        $this->assertEquals('SCRIPTNAMEGOESHERE', $sut);

        //Worse case scenario, script_name isn't set :(
        try {
            $sut = $this->phpwebunit->setSystemUnderTest(uniqid('does_not_exist'), array());
        } catch (\Exception $expected) {
            return;
        }
        $this->fail('Exception should have been raised, SCRIPT_NAME cannot be found');
    }

    public function testsoLongAndThanksForAllTheFish(){
        $this->phpwebunit['sapi'] = 'not_cli';
        $this->phpwebunit['view'] = 'tester';
        $result = $this->phpwebunit->soLongAndThanksForAllTheFish();
        $this->assertEquals("This is my view!\nAPI[not_cli]\nCONFIG_COUNT[10]", $result);

        //I can verify that we need 2 functions to be called.
        //getPHPUnitRawResults();
        //analyzeResults();
        $this->assertTrue(isset($this->phpwebunit['raw_output']));
        $this->assertTrue(isset($this->phpwebunit['result_type']));
    }

    public function testGetPHPUnitRawResults(){
        $this->phpwebunit['bin'] = 'bin';
        $this->phpwebunit['sut'] = 'script_name';
        $this->phpwebunit['php_exec'] = 'mock_exec';
        $this->phpwebunit['switches'] = '--debug';
        $result = $this->phpwebunit->getPHPUnitRawResults(function($cmd, & $result){ $result = array($cmd); });
        $this->assertTrue(is_array($result));
        $this->assertEquals('bin --debug script_name', $result[0]);
    }

    public function testAnalyzeResults(){
        //Test our winning scenario
        $result = $this->phpwebunit->analyzeResults(array('OK (5 tests, 13 assertions)'));
        $this->assertEquals('win', $result);
        $this->assertEquals('OK (5 tests, 13 assertions)', $this->phpwebunit['status_bar_text']);

        //What about a failure?
        $result = $this->phpwebunit->analyzeResults(array('Tests: 6, Assertions: 16, Failures: 1.'));
        $this->assertEquals('fail', $result);
        $this->assertEquals('Tests: 6, Assertions: 16, Failures: 1.', $this->phpwebunit['status_bar_text']);

        //What about an unknown with valid results?
        $result = $this->phpwebunit->analyzeResults(array('huh, what do we have here?'));
        $this->assertEquals('unknown', $result);
        $this->assertEquals('Unknown', $this->phpwebunit['status_bar_text']);

        //What about an unknown with invalid results?
        $result = $this->phpwebunit->analyzeResults('should be an array!');
        $this->assertEquals('unknown', $result);
        $this->assertEquals('Unknown', $this->phpwebunit['status_bar_text']);
        $this->assertTrue(true);
    }
}