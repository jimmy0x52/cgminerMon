<?

	include_once ('config.php');

	function getTotalDoges()
	{
		$total = 0;
		$wallets = explode(",",DOGE_WALLETS);
		foreach($wallets as $wallet) {
		    $total += getWalletDoges($wallet);
		}
		return $total;
	}

	function getWalletDoges($walletAddress)
	{
	        // our curl handle (initialize if required)
	        static $ch = null;
	        if (is_null($ch)) {
	                $ch = curl_init();
	                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; DogeChain API PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
	        }
	        curl_setopt($ch, CURLOPT_URL, 'http://dogechain.info/chain/Dogecoin/q/addressbalance/'.$walletAddress);
	      
	        // run the query
	        $res = curl_exec($ch);
	        
	        if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
	        return floatval($res);
	}
?>