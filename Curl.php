<?php

    /**
     * @author Gavin Staniforth, http://twitter.com/gsphpdev
     * @version 0.1
     */
    class Curl
    {

        private $_curl;

        /**
         * Creates a new CURL resource, you can also send a CURL which you have created with curl_init
         * @param type $curl
         * @return void
         */
        public function __construct(&$curl=null)
        {
            if ($curl !== null) {
                $this->_curl = $curl;
                return;
            }

            if (function_exists('curl_init')) {
                $this->_curl = curl_init();
                return;
            }

            throw new CurlException('No CURL found on this machine. Make sure the PHP5-CURL extension is installed.', null, null);
        }

        /**
         * Since resources are not cleaned via the GC its important we handle it !
         */
        public function __destruct()
        {
            if (is_resource($this->_curl)) {
                curl_close($this->_curl);
            }
        }

        /**
         * @return resource 
         */
        public function __clone()
        {
            return curl_copy_handle($this->_curl);
        }

        /**
         * Used to set the CURL constants
         * @param constant name
         * @param string value
         * @return bool
         */
        public function __set($name, $value)
        {
            if (defined($name)) {
                return curl_setopt($this->_curl, constant($name), $value);
            }

            throw new CurlException('Failed to find CURL constant ' . $name, null, null);
        }

        /**
         * Returns information about the current CURL resource
         * @param string $option
         * @return variant 
         */
        public function getInfo($option=null)
        {
            if ($option !== null) {
                return curl_getinfo($this->_curl, $option);
            }

            return curl_getinfo($this->_curl);
        }

    }

    