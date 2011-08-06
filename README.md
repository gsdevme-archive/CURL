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
    // Standard
    $curl = new Curl();
    $curl->setOpt(CURLOPT_URL, 'http://api.twitter.com/1/trends.json');  
    $curl->setOpt(CURLOPT_RETURNTRANSFER, true);
    $curl->setOpt(CURLOPT_HEADER, false);
    $data = $curl->exec();
    unset($curl); // will call curl_close();

    // Mutil Curl
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

    $dataTwo = $multi->getContent($curlTwo);