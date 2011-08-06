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
    
    $curlOne = new Curl();
    $curlOne->setOpt(CURLOPT_URL, 'https://api.twitter.com/1/legal/tos.json');
    $curlOne->setOpt(CURLOPT_RETURNTRANSFER, true);
    $curlOne->setOpt(CURLOPT_HEADER, false);
    
	$curlTwo = new Curl();
	$curlTwo->setOpt(CURLOPT_URL, 'http://api.twitter.com/1/statuses/public_timeline.json');  
	$curlTwo->setOpt(CURLOPT_RETURNTRANSFER, true);
	$curlTwo->setOpt(CURLOPT_HEADER, false);

	$curlThree = new Curl();
	$curlThree->setOpt(CURLOPT_URL, 'http://api.twitter.com/1/trends.json');  
	$curlThree->setOpt(CURLOPT_RETURNTRANSFER, true);
	$curlThree->setOpt(CURLOPT_HEADER, false);
	
	// Add all CURLs to a multi Curl
	$multi = new Curl(true); // Notice TRUE for a curl_multi_init()
	$multi->addHandle($curlOne);
	$multi->addHandle($curlTwo);
	$multi->addHandle($curlThree);
	
	// Simultaneously CURL all CURLs
	$multi->exec();
	
	echo '<h3>Curl Three, Twitter Trends</h3><pre>' . print_r(json_decode($multi->getContent($curlThree)), true) . '</pre>';
	echo '<h3>Curl One, Twitter TOS</h3><pre>' . print_r(json_decode($multi->getContent($curlOne)), true) . '</pre>';
	echo '<h3>Curl Two, Twitter Public Timeline</h3><pre>' . print_r(json_decode($multi->getContent($curlTwo)), true) . '</pre>';
?>