<?php

include_once ('config.php');
include_once ('aws/aws-autoloader.php');

/* Script Allowed Execution Time */
set_time_limit(0);

function getsock($addr, $port)
{
	$socket = null;
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

	if ($socket === false || $socket === null)
	{
		$error = socket_strerror(socket_last_error());
		$msg = "socket create(TCP) failed";
		//echo "ERR: $msg '$error'\n";
		return null;
	}

	socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => SOCK_TIMEOUT, 'usec' => 0));
	socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => SOCK_TIMEOUT, 'usec' => 0)); 

	$res = socket_connect($socket, $addr, $port);
	if ($res === false)
	{
		$error = socket_strerror(socket_last_error());
		$msg = "socket connect($addr,$port) failed";
		//echo "ERR: $msg '$error'\n";
		socket_close($socket);
		return null;
	}

	return $socket;
}

function readsockline($socket)
{
	$line = '';
	while (true)
	{
		$byte = socket_read($socket, 1);
		if ($byte === false || $byte === '')
			break;
		if ($byte === "\0")
			break;
		$line .= $byte;
	}
	return $line;
}

function request($cmd, $ip, $port)
{
	$socket = getsock($ip, $port);
	
	if ($socket != null)
	{
		socket_write($socket, $cmd, strlen($cmd));
		$line = readsockline($socket);
		socket_close($socket);

		if (strlen($line) == 0)
		{
			//echo "WARN: '$cmd' returned nothing\n";
			return $line;
		}

		if (substr($line,0,1) == '{')
			return json_decode($line, true);

		$data = array();

		$objs = explode('|', $line);
		foreach ($objs as $obj)
		{
			if (strlen($obj) > 0)
			{
				$items = explode(',', $obj);
				$item = $items[0];
				$id = explode('=', $items[0], 2);
				if (count($id) == 1 or !ctype_digit($id[1]))
					$name = $id[0];
				else
					$name = $id[0].$id[1];

				if (strlen($name) == 0)
					$name = 'null';

				if (isset($data[$name]))
				{
					$num = 1;
					while (isset($data[$name.$num]))
						$num++;
					$name .= $num;
				}

				$counter = 0;
				foreach ($items as $item)
				{
					$id = explode('=', $item, 2);
					if (count($id) == 2)
						$data[$name][$id[0]] = $id[1];
					else
						$data[$name][$counter] = $id[0];

					$counter++;
				}
			}
		}

		return $data;
	}

	return null;
}

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

$monitor['summary'] = request('summary', $monitor['ip'], $monitor['port']);
if ($monitor['summary'] != null)
{
	$monitor['devs']  = request('devs',  $monitor['ip'], $monitor['port']);
	$monitor['stats'] = request('stats', $monitor['ip'], $monitor['port']);
	$monitor['pools'] = request('pools', $monitor['ip'], $monitor['port']);
	$monitor['coin']  = request('coin',  $monitor['ip'], $monitor['port']);

}
$monitor['lastUploaded'] = date("Y-m-d H:i:s");
$monitor['name'] = MINER_NAME;
$monitorJson = json_encode($monitor);


$aws = Aws\Common\Aws::factory(array(
    'key'    => AWS_ACCESSKEYID,
    'secret' => AWS_SECRET,
));

$s3Client = $aws->get('s3');

// Upload data.
$result = $s3Client->putObject(array(
    'Bucket' => AWS_BUCKET_NAME,
    'Key'    => MINER_NAME . ".data",
    'Body'   => $monitorJson
));


?>
