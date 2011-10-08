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
    $myCurlObject->CURLOPT_URL = 'http://www.whatismyip.org/';
    $myCurlObject->CURLOPT_RETURNTRANSFER = true;

    $myCurlObject->exec(function($return, stdClass $info) {
            if (($http = $info->http_code) == '200') {
                echo $return;
                return;
            }

            echo 'No HTTP 200, it returned ' . $http . ' Code';
        });