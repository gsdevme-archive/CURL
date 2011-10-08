<?php

    use PHPUnit_Framework_TestCase as PHPUnit;
use Cure\Curl as Curl;

    class CurlTest extends PHPUnit
    {

        private $_curl;

        public function setup()
        {
            $this->_curl = new Curl\Curl;
        }

        public function testInvoke()
        {
            
        }

    }