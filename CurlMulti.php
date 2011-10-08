<?php

    class CurlMulti extends SplQueue
    {

        private $_curls;

        public function __construct()
        {
            /*
             * This will mean the Curl objects destructor is called 
             * i.e. meaning the curl is closed !! Woop go GC
             */
            $this->setIteratorMode(self::IT_MODE_DELETE);
        }

        public function exec()
        {
            foreach ($this as $value) {
                echo '<pre>' . print_r($value, 1) . '</pre>';
            }
        }

    }

    