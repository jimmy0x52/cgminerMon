<?
date_default_timezone_set('America/Chicago');

// Give your miner a unique name
define('MINER_NAME','UNIQUEMINERNAME');

// Set the API port for your miner.
define('API_PORT','4001');

// Set to your S3 bucket's access to drop the data file
define('AWS_ACCESSKEYID','AWS_ACCESSKEY');
define('AWS_SECRET','AWS_SECRETKEY');
define('AWS_BUCKET_NAME','AWS_BUCKETNAME');

$monitor['ip'] = 'localhost'; 
$monitor['port'] = API_PORT;


/*#####################################################################################*/
/*# DO NOT EDIT BELOW THIS LINE #######################################################*/
/*#####################################################################################*/
/* Connection Timeout in Seconds */
define('SOCK_TIMEOUT', '3');
ini_set('default_socket_timeout', SOCK_TIMEOUT);

?>