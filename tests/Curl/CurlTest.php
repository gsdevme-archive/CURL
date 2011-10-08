<?php

    use PHPUnit_Framework_TestCase as PHPUnit;
use Curl\Curl as Curl;

    class CurlTest extends PHPUnit
    {

        private $_curl;

        public function setup()
        {
            $this->_curl = new Curl;
        }

        public function testInstance()
        {
            $this->assertInstanceOf('Curl\Curl', $this->_curl);
        }

        public function testInstancePass()
        {
            $this->assertInstanceOf('Curl\Curl', new Curl(curl_init()));
        }

        public function testClone()
        {
            $this->assertInstanceOf('Curl\Curl', (clone $this->_curl));
        }

        public function testSetConstant()
        {
            try {
                $this->_curl->CURLOPT_RETURNTRANSFER = true;
            } catch (\Exception $e) {
                $this->fail('Failed due to exception being thrown');
            }
        }

        public function testCatchInvalidConstant()
        {
            try {
                $this->_curl->CURLOPT_RETU = true;
                $this->fail('No exception was thrown when an invalid property was set');
            } catch (\Exception $e) {
                
            }
        }

        public function testFailedExec()
        {
            $this->assertEquals(false, $this->_curl->exec()); 
        }

        public function testExec()
        {
            $this->_curl->CURLOPT_URL = 'http://whatismyip.org';
            $this->_curl->CURLOPT_RETURNTRANSFER = true;

            $return = $this->_curl->exec();

            if (!filter_var($return, FILTER_VALIDATE_IP)) {
                $this->fail('Failed to validate IP from whatsismyip.org');
            }
        }

        public function testExecCallback()
        {
            $this->_curl->CURLOPT_URL = 'http://whatismyip.org';
            $this->_curl->CURLOPT_RETURNTRANSFER = true;

            $return = $this->_curl->exec(function($return, $info) {
                    return ( bool ) (filter_var($return, FILTER_VALIDATE_IP));
                });

            $this->assertTrue($return);
        }

        public function testExecInfo()
        {
            $this->_curl->CURLOPT_URL = 'http://whatismyip.org';
            $this->_curl->CURLOPT_RETURNTRANSFER = true;

            $return = $this->_curl->exec();

            if ($this->_curl->getInfo('CURLINFO_HTTP_CODE') == '200') {
                if (!filter_var($return, FILTER_VALIDATE_IP)) {
                    $this->fail('Failed to validate IP from whatsismyip.org');
                }
            }
        }
        
        public function testGetResource(){
            $resource = $this->_curl->getResource();
            
            if(!is_resource($resource)){
                $this->fail('Failed to get resource');
            }
        }

    }