<?php
    // Assign to Global space
    use Curl\Curl as Curl;    

    #################################################################
    ## Simple Autoloader ############################################
    #################################################################
    $root = realpath(dirname(__FILE__)) . '/';
    
    echo $root;

    spl_autoload_register(function($name) use ($root) {
            $file = $root .'../' . str_replace('\\', '/', $name) . '.php';

		

            if (is_readable($file)) {
                require_once $file;
            }

        }, true, true);
    #################################################################


    