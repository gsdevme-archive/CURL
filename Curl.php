<?php

    class Curl
    {

        private $_curl, $_isMulti;

        /**
         * Constructor, creates a new CURL resource, with option to pass opts
         * @param bool $mutli
         * @param resource $curl=null
         */
        public function __construct($multi=false, &$curl=null)
        {
            if (function_exists('curl_init')) {
                if ($curl === null){
                    if ($multi) {
                        $this->_curl = @curl_multi_init();
                    } else {
                        $this->_curl = @curl_init();
                    }
                }else{
                    $this->_curl = $curl;
                }

                if (is_resource($this->_curl)) {
                    $this->_isMulti = ( bool ) $multi;
                    return;
                }
            }

            throw new Exception('Failed to create CURL Resource, is CURL installed ?');
        }

        /**
         * Destructor, closes the resource, "Yeah! Extra Memory"
         */
        public function __destruct()
        {
            if(is_resource($this->_curl)){
                if ($this->_isMulti) {
                    @curl_multi_close($this->_curl);
                } else {
                    @curl_close($this->_curl);
                }               
            }
        }

        /**
         * Clone, upon clone of the object we copy the CURL resource
         */
        public function __clone()
        {
            $this->_curl = curl_copy_handle($this->_curl);
            return;
        }

        /**
         * Used for debugging
         */
        public function __toString()
        {
            return print_r($this->_curl, true);
        }

        /**
         * Used for method overloading on _setOpt and _setOptArray
         */
        public function __call($method, $args)
        {
            if ($method === 'setOpt') {
                if (is_array($args[0])) {
                    return call_user_func_array(array($this, '_setOptArray'), $args);
                }

                return call_user_func_array(array($this, '_setOpt'), $args);
            }

            throw new BadMethodCallException($method . ' method does not exist.');
        }

        /**
         * Sets values for Curl
         * @param int $name
         * @param mixed $value
         * @return bool
         */
        private function _setOpt($name, $value)
        {
            return ( bool ) curl_setopt($this->_curl, $name, $value);
        }

        /**
         * Sets multiple values for Curl
         * @param array $value
         * @return bool     
         */
        private function _setOptArray(array $value)
        {
            return ( bool ) curl_setopt_array($this->_curl, $value);
        }

        /**
         * Executes the curret resource
         * @param closure $callback=false
         * @return mixed
         */
        public function exec($callback=false)
        {
            if (!$this->_isMulti) {
                $r = curl_exec($this->_curl);

                if (is_bool($r)) {
                    return ( bool ) $r;
                }

                return $r;
            }

            $running = null;

            do {
                $status = curl_multi_exec($this->_curl, $running);                
                
                if(($callback !== false) && (curl_multi_select($this->_curl) != -1)){
                    $info = curl_multi_info_read($this->_curl);
                    
                    if(is_array($info)){
                        $callback(new self(false, $info['handle']));
                    }                   
                }                
            } while ($status === CURLM_CALL_MULTI_PERFORM || $running > 0);
        }

        /**
         * Used to add curls to the Multi CURL
         * @param Curl $curl
         * @return bool
         */
        public function addHandle(Curl &$curl)
        {
            if ($this->_isMulti) {
                return ( bool ) (curl_multi_add_handle($this->_curl, $curl->getCurl()) === 0) ? : true;
            }

            throw new Exception('Your using a Multi method yet you have defined a standard CURL, you should use $c = new Curl(true); for a Multi curl.');
        }

        /**
         * Used to remove curls from the Multi CURL
         * @param Curl $curl
         * @return bool
         */
        public function removeHandle(Curl &$curl)
        {
            if ($this->_isMulti) {
                return ( bool ) (curl_multi_remove_handle($this->_curl, $curl->getCurl()) === 0) ? : true;
            }

            throw new Exception('Your using a Multi method yet you have defined a standard CURL, you should use $c = new Curl(true); for a Multi curl.');
        }

        /**
         * Used to get content from CURLs within Multi
         * @param Curl $curl
         * @return bool
         */
        public function getContent(Curl &$curl)
        {
            if ($this->_isMulti) {
                return curl_multi_getcontent($curl->getCurl());
            }

            throw new Exception('Your using a Multi method yet you have defined a standard CURL, you should use $c = new Curl(true); for a Multi curl.');
        }

        /**
         * Returns the CURL version, simples :)
         * @return array
         */
        public function getVersion()
        {
            return ( array ) curl_version();
        }

        /**
         * Returns Information about the last CURL
         * @param int $opt=0
         * @return mixed 
         * Full list of options here 
         * http://www.php.net/manual/en/function.curl-getinfo.php
         */
        public function getInfo($opt=null)
        {
            if($opt !== null){
                return curl_getinfo($this->_curl, $opt);
            }
            
            return ( array ) curl_getinfo($this->_curl);            
        }

        /**
         * Returns error string
         * @return string/null
         */
        public function getError()
        {
            return (curl_error($this->_curl)) ? : null;
        }

        /**
         * Returns error number, full list of error codes here
         * http://curl.haxx.se/libcurl/c/libcurl-errors.html
         * @return int/null
         */
        public function getErrno()
        {
            return curl_errno($this->_curl);
        }

        /**
         * Returns a standard curl for use
         * @return resource
         */
        public function getCurl()
        {
            return $this->_curl;
        }

    }
