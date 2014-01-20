<?php

function seconds_to_time($input_seconds)
{
	$seconds_in_minute = 60;
	$seconds_in_hour   = 60 * $seconds_in_minute;
	$seconds_in_day    = 24 * $seconds_in_hour;

	// extract days
	$days = floor($input_seconds / $seconds_in_day);

	// extract hours
	$hour_seconds = $input_seconds % $seconds_in_day;
	$hours = floor($hour_seconds / $seconds_in_hour);

	// extract minutes
	$minute_seconds = $hour_seconds % $seconds_in_hour;
	$minutes = floor($minute_seconds / $seconds_in_minute);

	// extract the remaining seconds
	$remaining_seconds = $minute_seconds % $seconds_in_minute;
	$seconds = ceil($remaining_seconds);

	// return the final array
	$obj = array(
		'd' => (int)$days,
		'h' => sprintf('%02d', (int)$hours),
		'm' => sprintf('%02d', (int)$minutes),
		's' => sprintf('%02d', (int)$seconds)
	);
	return $obj;
}
?>
