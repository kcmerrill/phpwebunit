<?php

/*
 * Copyright (c) 2012 kc merrill
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/*
 * phpwebunit
 * Love TDD? Love PHPUnit? Love SimpleTest?
 * Me too .. this is a simple wrapper utility that lets one have unit test in a web browser.
 * TL;DR
 * PHPUnit that works like simpletest
 *
 * Especially useful if you have an IDE that does live reloading(Coda2, StormPHP ect ..)
 * kcmerrill/1.5.2013
 */

namespace kcmerrill\tdd;

class phpwebunit implements \arrayaccess {
    protected $_config = array(
        'bin'=>'phpunit',
        'switches'=>'',
        'view' => 'default',
        'php_exec'=>'exec',
        'script_name' => 'SCRIPT_FILENAME'
    );

    public function __construct($sut = '', $config = array(), $SERVER = false){
        $this['sapi'] = php_sapi_name();
        $this->setSystemUnderTest($sut, $SERVER);
        $this->_config = array_merge($this->_config, $config);
        if($this['sapi'] != 'cli'){
            exit;
        }
    }

    public function setSystemUnderTest($sut = '', $SERVER = false){
        $SERVER = is_array($SERVER) ? $SERVER : $_SERVER;
        if(!isset($SERVER[$this['script_name']])){
            throw new \Exception('Unable to find the script name!');
        }
        $sut = is_string($sut) && (is_file($sut) || is_dir($sut)) ? $sut : $SERVER[$this['script_name']];
        $this['sut'] = $sut;
        return $sut;
    }

    public function analyzeResults($results){
        $results = is_array($results) ? $results : array();
        if(count($results)){
           $end_line =  array_pop($results);
           if(strpos($end_line, 'OK (') === 0){
               //Ok
               $this['status_bar_text'] = $end_line;
               return 'win';
           }else if(stristr($end_line, 'Failures: ')){
               $this['status_bar_text'] = $end_line;
               return 'fail';
           }
           else{
               $this['status_bar_text'] = 'Unknown';
               return  'unknown';
           }
        } else {
            $this['status_bar_text'] = 'Unknown';
            return 'unknown';
        }
    }

    public function getPHPUnitRawResults($exec = 'exec'){
        if(!is_callable($exec)){
            throw new \Exception('\$exec must pass is_callable()');
        }

        $result = array();
        $exec($this['bin'] . ' ' . (strlen($this['switches']) ? $this['switches'] . ' ' : '') . $this['sut'], $result);
        return is_array($result) ? $result : array();
    }

    public function view($template, $variables = array() , $echo = true){
        ob_start();
        extract($variables);
        $template = is_file(dirname(__FILE__). '/views/'. $template .'.php') ? dirname(__FILE__). '/views/'. $template .'.php' : dirname(__FILE__). '/views/default.php';
        include $template;
        $view = ob_get_clean();
        if($echo){
            echo $view;
        }
        return $view;
    }

    public function soLongAndThanksForAllTheFish(){
        //Meaning it wasnt called from the command line
        //If so, don't even bother
        if($this['sapi'] != 'cli'){
            $this['raw_output'] = $this->getPHPUnitRawResults();
            $this['result_type'] = $this->analyzeResults($this['raw_output']);
            $this['config_count'] = count($this->_config);
            return $this->view($this['view'], $this->_config);
        }
        return '';
    }

    public function __destruct(){
        $this->soLongAndThanksForAllTheFish();
    }

    public function offsetSet($offset, $value) {
        $this->_config[$offset] = $value;
    }
    public function offsetExists($offset) {
        return isset($this->_config[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->_config[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->_config[$offset]) ? $this->_config[$offset] : NULL;
    }
}