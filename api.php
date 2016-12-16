<?php

//http://poslaju.com.my/track-trace-v2/?trackingNo=TRACKINGCODE&hvtrackNoHeader=&hvfromheader=0
//set_time_limit(0);

$trackingNo = '';

# store post data into array
$postdata = http_build_query(
    array(
        'trackingNo' => $trackingNo,
        'hvtrackNoHeader' => '',
        'hvfromheader' => 0
    )
);

# create option array for steam
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

# create new stream
$context  = stream_context_create($opts);

# fetch webpage content with post data
$result = file_get_contents('http://poslaju.com.my/track-trace-v2/', false, $context);

print_r($result);


?>

