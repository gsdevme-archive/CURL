<?php

    // Assign to Global space
    use Curl\Curl as Curl;

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
    $myCurlObject->CURLOPT_URL = 'http://whatismyip.org';
    $myCurlObject->CURLOPT_RETURNTRANSFER = true;

    $mySecondCurl = new Curl;
    $mySecondCurl->CURLOPT_URL = 'http://www.google.com/';
    $mySecondCurl->CURLOPT_RETURNTRANSFER = true;

    $multi = new \Curl\CurlMulti;
    
    $multi->push($myCurlObject, function($return, $info) {
            echo '<pre>' . print_r($info, 1) . '</pre>';
        }
    );

    $multi->push($mySecondCurl, function($return, $info) {
            echo '<pre>' . print_r($info, 1) . '</pre>';
        }
    );

    $multi->exec();