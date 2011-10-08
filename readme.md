Curl, use 1
    $myCurlObject = new Curl;
    $myCurlObject->CURLOPT_URL = 'http://whatismyip.org';
    $myCurlObject->CURLOPT_RETURNTRANSFER = true;

    $data = $myCurlObject->exec();

    echo '<pre>' . print_r($data, 1) . '</pre>' . "\n\n";

Curl, use 2
    $myCurlObject = new Curl;
    $myCurlObject->CURLOPT_URL = 'http://whatismyip.org';
    $myCurlObject->CURLOPT_RETURNTRANSFER = true;

    $data = $myCurlObject->exec(function($return, $info){
        echo '<pre>' . print_r($return, 1) . '</pre>' . "\n\n";
    });   

Multi Curl, use 1

    $myCurlObject = new Curl;
    $myCurlObject->CURLOPT_URL = 'http://whatismyip.org';
    $myCurlObject->CURLOPT_RETURNTRANSFER = true;

    $mySecondCurl = new Curl;
    $mySecondCurl->CURLOPT_URL = 'http://www.google.com/';
    $mySecondCurl->CURLOPT_RETURNTRANSFER = true;

    $multi = new CurlMulti;
    
    $multi->push($myCurlObject);
    $multi->push($mySecondCurl);

    $data = $multi->exec();
    
    foreach($data as $value){
        echo '<pre>' . print_r($value, 1) . '</pre>' . "\n\n";
    }

Multi Curl, use 2

    $myCurlObject = new Curl;
    $myCurlObject->CURLOPT_URL = 'http://whatismyip.org';
    $myCurlObject->CURLOPT_RETURNTRANSFER = true;

    $mySecondCurl = new Curl;
    $mySecondCurl->CURLOPT_URL = 'http://www.google.com/';
    $mySecondCurl->CURLOPT_RETURNTRANSFER = true;

    $multi = new CurlMulti;
    
    $multi->push($myCurlObject, function($return, $info){
        echo '<pre>' . print_r($return, 1) . '</pre>' . "\n\n";
    });

    $multi->push($mySecondCurl, function($return, $info){
        echo '<pre>' . print_r($return, 1) . '</pre>' . "\n\n";
    });

    $multi->exec();