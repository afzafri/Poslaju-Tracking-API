<!DOCTYPE html>
<html>
<head>
	<title>Poslaju API Demo</title>
</head>
<body>

	<form action="./demo.php" method="get">
		Tracking No: &nbsp; &nbsp; 
		<input type="text" name="trackingNo" placeholder="Tracking no, ex: ER126406955MY" value="ER126406955MY"> &nbsp; &nbsp;
		<button type="submit">Track</button>
	</form>

	<?php

	if(isset($_GET['trackingNo']))
	{

		$trackingNo = $_GET['trackingNo']; # your tracking number
		$url = "http://localhost/poslajuAPI/api.php?trackingNo=".$trackingNo; # the full URL to the API
		$getdata = file_get_contents($url); # use files_get_contents() to fetch the data, but you can also use cURL, or javascript/jquery json
		$parsed = json_decode($getdata,true); # decode the json into array. set true to return array instead of object

		$httpcode = $parsed["http_code"];
		$message = $parsed["message"];

		echo "<br>Status: ".$message . "<br>";

		if($message == "Record Found" && $httpcode == 200)
		{
			?>

			<table border=1>
				<tr>
					<th>Date/Time</th>
					<th>Process</th>
					<th>Event</th>
				</tr>
				<?php
					
					# iterate through the array
					for($i=0;$i<count($parsed['data']);$i++) 
					{
						# access each items in the JSON
						echo "
							<tr>
								<td>".$parsed['data'][$i]['date_time']."</td>
								<td>".$parsed['data'][$i]['process']."</td>
								<td>".$parsed['data'][$i]['event']."</td>
							</tr>
							";
					}
			?>

			</table>

			<?php
		}

	}
	
	?>

</body>
</html>