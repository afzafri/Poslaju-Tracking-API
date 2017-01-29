<?php

/*  Poslaju Tracking API created by Afif Zafri.
    Tracking details are fetched directly from Poslaju tracking website,
    parse the content, and return JSON formatted string.
    Please note that this is not the official API, this is actually just a "hack",
    or workaround for implementing Poslaju tracking feature in other project.
    Usage: http://site.com/api.php?trackingNo=CODE , where CODE is your tracking number
*/

header("Access-Control-Allow-Origin: *"); # enable CORS

if(isset($_GET['trackingNo']))
{
    $trackingNo = $_GET['trackingNo']; # put your poslaju tracking number here 

    $url = "http://poslaju.com.my/track-trace-v2/"; # url of poslaju tracking website

    # store post data into array (poslaju website only receive the tracking no with POST, not GET. So we need to POST data)
    $postdata = http_build_query(
        array(
            'trackingNo03' => $trackingNo,
            'hvtrackNoHeader03' => '',
            'hvfromheader03' => 0,
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
    $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # receive http response status
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
    $trackres['http_code'] = $httpstatus; # set http response code into the array

    # checking if record found or not, by checking the number of rows available in the result table
    if(count($tr[0]) > 0)
    {
        $trackres['message'] = "Record Found"; # return record found if number of row > 0

        # record found, so proceed
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
            $trackres['data'][$i]['date_time'] = $datetime;
            $trackres['data'][$i]['process'] = $process;
            $trackres['data'][$i]['event'] = $event;
        }
    }
    else
    {
        $trackres['message'] = "No Record Found"; # return record not found if number of row < 0
        # since no record found, no need to parse the html furthermore
    }
   
    # project info, move it here so people see the good stuff first
    $trackres['info']['creator'] = "Afif Zafri (afzafri)";
    $trackres['info']['project_page'] = "https://github.com/afzafri/Poslaju-Tracking-API";
    $trackres['info']['date_updated'] = "29/01/2017";

    # output/display the JSON formatted string
    echo json_encode($trackres);
}

?>

