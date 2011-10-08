<?php

    namespace Curl {

        use \Closure as Closure;

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

                $this->_curl = curl_init();
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
                    return ( bool ) curl_setopt($this->_curl, constant($name), $value);
                }

                throw new CurlException('Failed to find CURL constant ' . $name . ', ' . $this->_errorMessage(), null, null);
            }

            /**
             * Returns information about the current CURL resource
             * @param string $option
             * @return variant 
             */
            public function getInfo($option=null)
            {
                if (($option !== null) && (defined($option))) {
                    return curl_getinfo($this->_curl, constant($option));
                }

                return ( object ) curl_getinfo($this->_curl);
            }

            /**
             *
             * @return variant 
             */
            public function exec(Closure $callback=null)
            {
                if (($return = curl_exec($this->_curl)) !== false) {
                    if ($callback !== null) {
                        return $callback($return, $this->getInfo());
                    }

                    return $return;
                }

                return ( bool ) false;
            }

            /**
             * Returns the resource via reference
             * @return resource
             */
            public function &getResource()
            {
                return $this->_curl;
            }

            /**
             *
             * @return string 
             */
            private function _errorMessage()
            {
                return ( string ) curl_errno($this->_curl) . ': ' . curl_error($this->_curl);
            }

        }

    }