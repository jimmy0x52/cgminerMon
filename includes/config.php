<?

date_default_timezone_set('America/Chicago');

/*--------------
# GENERAL CONFIG OPTIONS #
--------------*/

// Show Pool and User name on Dashboard
define('SHOW_POOLS', TRUE);

/*--------------
# AWS BUCKET INFO #
--------------*/
// Set to your S3 bucket's access to drop the data file
define('AWS_ACCESSKEYID','YOUR_BUCKET_ACCESS_KEY_ID');
define('AWS_SECRET','YOUR_BUCKET_SECRET_KEY');
define('AWS_BUCKET_NAME','YOUR_BUCKET_NAME');

/*--------------
# MINER INFO #
--------------*/

$miners = array();

/*--------------
# DOGE WALLETS #
--------------*/

// These are the list of ID's to check and add up to display your total DOGE
define("DOGE_WALLETS","walletaddress1,walletaddress2");


/*------------------------
# MONITORED MARKET ID'S #
------------------------*/

// Markets to monitor using Vircurex
$monitoredMarkets[0]['marketname'] = 'DOGE/USD'; //DOGE/BTC
$monitoredMarkets[0]['base'] = 'DOGE'; //DOGE/BTC
$monitoredMarkets[0]['alt'] = 'USD'; //DOGE/BTC

$monitoredMarkets[1]['marketname'] = 'DOGE/BTC'; //DOGE/BTC
$monitoredMarkets[1]['base'] = 'DOGE'; //DOGE/BTC
$monitoredMarkets[1]['alt'] = 'BTC'; //DOGE/BTC

$monitoredMarkets[2]['marketname'] = 'BTC/USD'; //LTC/BTC
$monitoredMarkets[2]['base'] = 'BTC'; //DOGE/BTC
$monitoredMarkets[2]['alt'] = 'USD'; //DOGE/BTC

/*#####################################################################################*/
/*# DO NOT EDIT BELOW THIS LINE #######################################################*/
/*#####################################################################################*/

/* Connection Timeout in Seconds */
define('SOCK_TIMEOUT', '3');
ini_set('default_socket_timeout', SOCK_TIMEOUT);

?>