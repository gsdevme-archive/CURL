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
    
    $clone = clone $myCurlObject;
    
    echo '<pre>' . print_r($myCurlObject, 1) . '</pre>';
    
    unset($myCurlObject);