<?;
	include_once ('config.php');
	include_once ('cryptsy_marketData.php');
	include_once ('btce_marketData.php');

	for ($i=0; $i<count($monitoredMarkets); $i++)
	{
	        $monitoredMarkets[$i]['marketdata'] = cryptsy_public_api_query("singlemarketdata",array("marketid" => $monitoredMarkets[$i]['marketid']));
	}

    $btcusd = get_btcusd();

    $usdConversionFactor = $btcusd['ticker']['avg'];

    // ADD BTCUSD TO THE MARKET DATA
    $btcusdObj[0]['marketname'] = 'USD';
    $btcusdObj[0]['marketdata']['return']['markets']['USD']['label'] = '<i class="fa fa-btc"></i>/<i class="fa fa-usd"></i>';
    $btcusdObj[0]['marketdata']['return']['markets']['USD']['primarycode'] = 'BTC';
    $btcusdObj[0]['marketdata']['return']['markets']['USD']['secondarycode'] = 'USD';
    $btcusdObj[0]['marketdata']['return']['markets']['USD']['recenttrades'][0]['time'] = date("Y-m-d H:i:s",$btcusd['ticker']['updated']);
    $btcusdObj[0]['marketdata']['return']['markets']['USD']['recenttrades'][0]['price'] = $btcusd['ticker']['avg'];
    
    // Put the almight BTCUSD conversion at the top
	$monitoredMarkets = array_merge($btcusdObj,$monitoredMarkets);

    $otherToUSD = array();
    for($x=0;$x<count($monitoredMarkets);$x++)
    {
    	$marketInfo = $monitoredMarkets[$x]['marketdata']['return']['markets'][$monitoredMarkets[$x]['marketname']];
    	if($marketInfo['secondarycode'] == 'BTC')
    	{
    		$otherToUSDidx = count($otherToUSD);
		    $otherToUSD[$otherToUSDidx]['marketname'] = $marketInfo['primarycode'];
		    $otherToUSD[$otherToUSDidx]['marketdata']['return']['markets'][$otherToUSD[$otherToUSDidx]['marketname']]['label'] = $marketInfo['primarycode'].'/USD';
		    $otherToUSD[$otherToUSDidx]['marketdata']['return']['markets'][$otherToUSD[$otherToUSDidx]['marketname']]['primarycode'] = $marketInfo['primarycode'];
		    $otherToUSD[$otherToUSDidx]['marketdata']['return']['markets'][$otherToUSD[$otherToUSDidx]['marketname']]['secondarycode'] = 'USD';
		    $otherToUSD[$otherToUSDidx]['marketdata']['return']['markets'][$otherToUSD[$otherToUSDidx]['marketname']]['recenttrades'][0]['time'] = $marketInfo['recenttrades'][0]['time'];
		    $otherToUSD[$otherToUSDidx]['marketdata']['return']['markets'][$otherToUSD[$otherToUSDidx]['marketname']]['recenttrades'][0]['price'] = $marketInfo['recenttrades'][0]['price'] * $usdConversionFactor;
    	}
    }

    if(count($otherToUSD)>0)
    {
    	$monitoredMarkets = array_merge($monitoredMarkets,$otherToUSD);
    }

	if($_GET['displayResults'])
	{
	        for ($i=0; $i<count($monitoredMarkets); $i++)
	        {
	                $marketInfo = $monitoredMarkets[$i]['marketdata']['return']['markets'][$monitoredMarkets[$i]['marketname']];
	                echo "<pre>";
	                print_r($marketInfo);
	                echo "</pre>";
	        }
	}

?>