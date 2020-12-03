<?php

require 'vendor/autoload.php';

use Afzafri\PoslajuTrackingApi;

if (isset($argv[1])) {
	print_r(PoslajuTrackingApi::crawl($argv[1]));
} else {
	echo "Usage: " . $argv[0] . " <Tracking code>\n";
}