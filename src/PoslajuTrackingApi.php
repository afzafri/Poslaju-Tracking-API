<?php 

namespace Afzafri;

class PoslajuTrackingApi
{
    public static function crawl($trackingNo, $include_info = false)
    {
		$url = "https://sendparcel.poslaju.com.my/open/trace?tno={$trackingNo}";

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    $result = curl_exec($ch);
	    $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    $errormsg = (curl_error($ch)) ? curl_error($ch) : "No error";
	    curl_close($ch);

	    # using regex (regular expression) to parse the HTML webpage.
	    # we only want to good stuff
	    # regex patern
	    $patern = "/<table.*?>(.*?)<\/table>/si";
	    # execute regex
	    preg_match_all($patern, $result, $parsed);
	    // echo "<table>".$parsed[1][0]."</table>";
	    # parse the table by row <tr>
	    $trpatern = "#<tr>(.*?)</tr>#";
	    preg_match_all($trpatern, implode('', $parsed[0]), $tr);
	    unset($tr[0][0]); # remove an array element because we don't need the 1st row (<th></th>)
	    unset($tr[0][1]);
	    $tr[0] = array_values($tr[0]); # rearrange the array index

	    # array for keeping the data
	    $trackres = array();
	    $trackres['http_code'] = $httpstatus; # set http response code into the array
	    $trackres['error_msg'] = $errormsg; # set error message into array

	    # checking if record found or not, by checking the number of rows available in the result table
	    if(count($tr[0]) > 0)
	    {
	    	$trackres['status'] = 1;
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
	    } else {
	        $trackres['message'] = "No Record Found"; # return record not found if number of row < 0
	        # since no record found, no need to parse the html furthermore
	        $trackres['status'] = 0;
	    }

	    if ($include_info) {
		    # project info, move it here so people see the good stuff first
		    $trackres['info']['creator'] = "Afif Zafri (afzafri)";
		    $trackres['info']['project_page'] = "https://github.com/afzafri/Poslaju-Tracking-API";
		    $trackres['info']['date_updated'] = "11/11/2020";
		}

	    return $trackres;
	
    }
}
