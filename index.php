<?php

    #################################################################
    ## Simple Autoloader ############################################
    #################################################################
    $root = realpath(dirname(__FILE__)) . '/';

    spl_autoload_register(function($name) use ($root) {
            $file = $root . str_replace('\\', '/', $name) . '.php';

            if (is_readable($file)) {
                require_once $file;
            }
        }, true, true);
    #################################################################

    $myCurlObject = new Curl;
    $myCurlObject->CURLOPT_URL = 'http://google.com';
    
    echo '<pre>' . print_r($myCurlObject->CURLOPT_URL, 1) . '</pre>';