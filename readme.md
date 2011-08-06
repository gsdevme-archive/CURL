Curl, PHP 5.3.3+
=============

Configurations
---------------------
	// Standard
	$curl = curl;

	// Multi Curl (i.e. curl_multi_init)
	$curl = curl(true);

Usage
---------------------
	$curl = new Curl();
	$curl->setOpt(CURLOPT_URL, 'http://api.twitter.com/1/trends.json');  
	$curl->setOpt(CURLOPT_RETURNTRANSFER, true);
	$curl->setOpt(CURLOPT_HEADER, false);
	$data = $curl->exec();
