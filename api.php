<?php

/*  Poslaju Tracking API created by Afif Zafri.
    Tracking details are fetched directly from Poslaju tracking website,
    parse the content, and return JSON formatted string.
    Please note that this is not the official API, this is actually just a "hack",
    or workaround for implementing Poslaju tracking feature in other project.
    Usage: http://site.com/index.php?trackingNo
*/

if(isset($_GET['trackingNo']))
{
    $trackingNo = $_GET['trackingNo']; # put your poslaju tracking number here 

    $url = "http://poslaju.com.my/track-trace-v2/"; # url of poslaju tracking website

    # store post data into array (poslaju website only receive the tracking no with POST, not GET. So we need to POST data)
    $postdata = http_build_query(
        array(
            'trackingNo' => $trackingNo,
            'hvtrackNoHeader' => '',
            'hvfromheader' => 0
        )
    );

    # use cURL instead of file_get_contents(), this is because on some server, file_get_contents() cannot be used
    # cURL also have more options and customizable
    $ch = curl_init(); # initialize curl object
    curl_setopt($ch, CURLOPT_URL, $url); # set url
    curl_setopt($ch, CURLOPT_POST, 1); # set option for POST data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); # set post data array
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
    $result = curl_exec($ch); # execute curl, fetch webpage content
    curl_close($ch);  # close curl

    # using regex (regular expression) to parse the HTML webpage.
    # we only want to good stuff
    # regex patern
    $patern = "#<table id='tbDetails'(.*?)</table>#"; 
    # execute regex
    preg_match_all($patern, $result, $parsed);  

    # parse the table by row <tr>
    $trpatern = "#<tr>(.*?)</tr>#";
    preg_match_all($trpatern, implode('', $parsed[0]), $tr);
    unset($tr[0][0]); # remove an array element because we don't need the 1st row (<th></th>) 
    $tr[0] = array_values($tr[0]); # rearrange the array index
    
    # array for keeping the data
    $trackres = array();
    $trackres['info']['creator'] = "Afif Zafri";
    $trackres['info']['contact'] = "http://www.facebook.com/afzafri";
    $trackres['info']['project_page'] = "https://github.com/afzafri/Poslaju-Tracking-API";
    $trackres['info']['date_updated'] =  "16/12/2016";
    
    # iterate through the array, access the data needed and store into new array 
    for($i=0;$i<count($tr[0]);$i++)
    {
        # parse the table by column <td>
        $tdpatern = "#<td>(.*?)</td>#";
        preg_match_all($tdpatern, $tr[0][$i], $td);
        
        # store into variable, strip_tags is for removeing html tags
        $datetime = strip_tags($td[0][0]);
        $process = strip_tags($td[0][1]);
        $event = strip_tags($td[0][2]);

        # store into associative array
        $trackres['items'][$i]['date_time'] = $datetime;
        $trackres['items'][$i]['process'] = $process;
        $trackres['items'][$i]['event'] = $event;
    }

    # output/display the JSON formatted string
    echo json_encode($trackres);
}

?>

