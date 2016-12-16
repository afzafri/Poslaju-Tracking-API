<!DOCTYPE html>
<html>
<head>
	<title>Poslaju API Demo</title>
</head>
<body>

	<?php

	$trackingNo = "EN222032266MY"; # your tracking number
	$url = "http://localhost/poslajuAPI/api.php?trackingNo=".$trackingNo; # the full URL to the API
	$getdata = file_get_contents($url); # use files_get_contents() to fetch the data, but you can also use cURL, or javascript/jquery json
	$parsed = json_decode($getdata,true); # decode the json into array. set true to return array instead of object

	?>

	<table border=1>
		<tr>
			<th>Date/Time</th>
			<th>Process</th>
			<th>Event</th>
		</tr>
		<?php
			
			# iterate through the array
			for($i=0;$i<count($parsed['items']);$i++) 
			{
				# access each items in the JSON
				echo "
					<tr>
						<td>".$parsed['items'][$i]['date_time']."</td>
						<td>".$parsed['items'][$i]['process']."</td>
						<td>".$parsed['items'][$i]['event']."</td>
					</tr>
					";
			}
		?>

	</table>

</body>
</html>