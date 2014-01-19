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

// Cryptsy market ID's to monitor
// Find the market ID by going to a cryptsy market and looking at the number at the end of the URL
// For example: https://www.cryptsy.com/markets/view/132

$monitoredMarkets[0]['marketid'] = '132'; //DOGE/BTC
$monitoredMarkets[0]['marketname'] = 'DOGE'; //DOGE/BTC

$monitoredMarkets[1]['marketid'] = '3'; //LTC/BTC
$monitoredMarkets[1]['marketname'] = 'LTC'; //LTC/BTC

/*#####################################################################################*/
/*# DO NOT EDIT BELOW THIS LINE #######################################################*/
/*#####################################################################################*/

/* Connection Timeout in Seconds */
define('SOCK_TIMEOUT', '3');
ini_set('default_socket_timeout', SOCK_TIMEOUT);

?>