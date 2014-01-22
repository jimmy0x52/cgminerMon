<?
	include_once ('config.php');
	include_once ('vircurex_marketData.php');

	for ($i=0; $i<count($monitoredMarkets); $i++)
	{ 
        $vircurexData = vircurex_api_query("get_info_for_1_currency",array("base" => $monitoredMarkets[$i]['base'],"alt" => $monitoredMarkets[$i]['alt']));
	    $monitoredMarkets[$i]['marketdata']['return']['markets'][$monitoredMarkets[$i]['marketname']]['label'] = $monitoredMarkets[$i]['marketname'];
	    $monitoredMarkets[$i]['marketdata']['return']['markets'][$monitoredMarkets[$i]['marketname']]['primarycode'] = $vircurexData['base'];
	    $monitoredMarkets[$i]['marketdata']['return']['markets'][$monitoredMarkets[$i]['marketname']]['secondarycode'] = $vircurexData['alt'];
	    $monitoredMarkets[$i]['marketdata']['return']['markets'][$monitoredMarkets[$i]['marketname']]['recenttrades'][0]['time'] = date("Y-m-d H:i:s");
	    $monitoredMarkets[$i]['marketdata']['return']['markets'][$monitoredMarkets[$i]['marketname']]['recenttrades'][0]['price'] = $vircurexData['last_trade'];
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