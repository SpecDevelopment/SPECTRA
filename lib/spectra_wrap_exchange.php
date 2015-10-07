<?php 
//	This script provides the cURL wrapper and data normalization
//	required for the SPECTRA Block Explorer market integration.

/******************************************************************************
	Market Ticker Fetching and Data Formatting
******************************************************************************/

	function spectra_ticker_bittrex ($market)
	{
	//	Retrieve the ticker data
		$response = spectra_fetch_json ("https://bittrex.com/api/v1.1//public/getmarketsummary?market=".$market["mkt_id"]);
		
	//	Format the data for use in the template
		if ($response["success"] == 1)
		{
			$ticker["bid"] = number_format ($response["result"][0]["Bid"], 8, ".", "");
			$ticker["ask"] = number_format ($response["result"][0]["Ask"], 8, ".", "");
			$ticker["vol"] = number_format ($response["result"][0]["Volume"], 8, ".", "");
			$ticker["last"] = number_format ($response["result"][0]["Last"], 8, ".", "");
		}
		
		else
		{
			$ticker["bid"] = "Unavailable";
			$ticker["ask"] = "Unavailable";
			$ticker["vol"] = "Unavailable";
			$ticker["last"] = "Unavailable";
		}

	//	Return the data to the requesting code
		return $ticker;
	}
	
	function spectra_ticker_bleutrade ($market)
	{
	//	Retrieve the ticker data
		$response = spectra_fetch_json ("https://bleutrade.com/api/v2/public/getmarketsummary?market=".$market["mkt_id"]);
		
	//	Format the data for use in the template
		if ($response["success"] == "true")
		{
			$ticker["bid"] = number_format ($response["result"][0]["Bid"], 8, ".", "");
			$ticker["ask"] = number_format ($response["result"][0]["Ask"], 8, ".", "");
			$ticker["vol"] = number_format ($response["result"][0]["Volume"], 8, ".", "");
			$ticker["last"] = number_format ($response["result"][0]["Last"], 8, ".", "");
		}
		
		else
		{
			$ticker["bid"] = "Unavailable";
			$ticker["ask"] = "Unavailable";
			$ticker["vol"] = "Unavailable";
			$ticker["last"] = "Unavailable";
		}

	//	Return the data to the requesting code
		return $ticker;
	}
	
	function spectra_ticker_ccex ($market)
	{
	//	Retrieve the ticker data
		$response = spectra_fetch_json ("https://c-cex.com/t/".$market["mkt_id"].".json");

	//	Format the data for use in the template
		if (isset ($response["ticker"]["high"]))
		{
			$ticker["bid"] = number_format ($response["ticker"]["buy"], 8, ".", "");
			$ticker["ask"] = number_format ($response["ticker"]["sell"], 8, ".", "");
			$ticker["vol"] = "Unavailable";
			$ticker["last"] = number_format ($response["ticker"]["lastprice"], 8, ".", "");
		}
		
		else
		{
			$ticker["bid"] = "Unavailable";
			$ticker["ask"] = "Unavailable";
			$ticker["vol"] = "Unavailable";
			$ticker["last"] = "Unavailable";
		}

	//	Return the data to the requesting code
		return $ticker;
	}
	
	function spectra_ticker_cryptopia ($market)
	{
	//	Retrieve the ticker data
		$response = spectra_fetch_json ("https://www.cryptopia.co.nz/api/GetMarket/".$market["mkt_id"]);

	//	Format the data for use in the template
		if ($response["Success"] == 1)
		{
			$ticker["bid"] = number_format ($response["Data"]["BidPrice"], 8, ".", "");
			$ticker["ask"] = number_format ($response["Data"]["AskPrice"], 8, ".", "");
			$ticker["vol"] = number_format ($response["Data"]["Volume"], 8, ".", "");
			$ticker["last"] = number_format ($response["Data"]["LastPrice"], 8, ".", "");
		}
		
		else
		{
			$ticker["bid"] = "Unavailable";
			$ticker["ask"] = "Unavailable";
			$ticker["vol"] = "Unavailable";
			$ticker["last"] = "Unavailable";
		}

	//	Return the data to the requesting code
		return $ticker;
	}
	
	function spectra_ticker_cryptsy ($market)
	{
	//	Retrieve the ticker data
		$pricing = spectra_fetch_json ("https://api.cryptsy.com/api/v2/markets/".$market["mkt_id"]."/ticker");
	
	//	Format the data for use in the template
		if ($pricing["success"] == 1)
		{
			$ticker["bid"] = number_format ($pricing["data"]["bid"], 8, ".", "");
			$ticker["ask"] = number_format ($pricing["data"]["ask"], 8, ".", "");
		}
		
		else
		{
			$ticker["bid"] = "Unavailable";
			$ticker["ask"] = "Unavailable";
		}

	//	Retrieve the market info
		$response = spectra_fetch_json ("https://api.cryptsy.com/api/v2/markets/".$market["mkt_id"]);
	
	//	Format the data for use in the template
		if ($response["success"] == 1)
		{
			$ticker["last"] = number_format ($response["data"]["last_trade"]["price"], 8, ".", "");
			$ticker["vol"] = number_format ($response["data"]["24hr"]["volume"], 8, ".", "");
		}
		
		else
		{
			$ticker["vol"] = "Unavailable";
			$ticker["last"] = "Unavailable";
		}

	//	Return the data to the requesting code
		return $ticker;
	}
	
	function spectra_ticker_poloniex ($market)
	{
	//	Retrieve the ticker data
		$response = spectra_fetch_json ("https://poloniex.com/public?command=returnTicker");
		
	//	Format the data for use in the template
		if (isset ($response[$market["mkt_id"]]))
		{
			$ticker["bid"] = number_format ($response[$market["mkt_id"]]["highestBid"], 8, ".", "");
			$ticker["ask"] = number_format ($response[$market["mkt_id"]]["lowestAsk"], 8, ".", "");
			$ticker["vol"] = number_format ($response[$market["mkt_id"]]["quoteVolume"], 8, ".", "");
			$ticker["last"] = number_format ($response[$market["mkt_id"]]["last"], 8, ".", "");
		}
		
		else
		{
			$ticker["bid"] = "Unavailable";
			$ticker["ask"] = "Unavailable";
			$ticker["vol"] = "Unavailable";
			$ticker["last"] = "Unavailable";
		}

	//	Return the data to the requesting code
		return $ticker;
	}
	
	function spectra_ticker_yobit ($market)
	{
	//	Retrieve the ticker data
		$response = spectra_fetch_json ("https://yobit.net/api/3/ticker/".$market["mkt_id"]);
		
	//	Format the data for use in the template
		if (isset ($response[$market["mkt_id"]]["last"]))
		{
			$ticker["bid"] = number_format ($response[$market["mkt_id"]]["buy"], 8, ".", "");
			$ticker["ask"] = number_format ($response[$market["mkt_id"]]["sell"], 8, ".", "");
			$ticker["vol"] = number_format ($response[$market["mkt_id"]]["vol"], 8, ".", "");
			$ticker["last"] = number_format ($response[$market["mkt_id"]]["last"], 8, ".", "");
		}
		
		else
		{
			$ticker["bid"] = "Unavailable";
			$ticker["ask"] = "Unavailable";
			$ticker["vol"] = "Unavailable";
			$ticker["last"] = "Unavailable";
		}

	//	Return the data to the requesting code
		return $ticker;
	}
	
	
/******************************************************************************
	Market Detail Fetching and Data Formatting 
******************************************************************************/
	
	function spectra_market_bittrex ($market)
	{
	//	Retrieve the market history
		$history = spectra_fetch_json ("https://bittrex.com/api/v1.1/public/getmarkethistory?market=".$market["mkt_id"]."&count=50");
	
	//	Format the trade history for use in the template
		$count = 0;
		foreach ($history["result"] as $trade)
		{
			$response["history"][$count]["type"] = $trade["OrderType"];
			$response["history"][$count]["quantity"] = number_format ($trade["Quantity"], "8", ".", "");
			$response["history"][$count]["price"] = number_format ($trade["Price"], "8", ".", "");
			$response["history"][$count]["total"] = number_format ($trade["Total"], "8", ".", "");
			$response["history"][$count]["time"] = $trade["TimeStamp"];
			
			$count++;
		}
		
	//	Retrieve the market orderbook
		$orderbook = spectra_fetch_json ("https://bittrex.com/api/v1.1/public/getorderbook?market=".$market["mkt_id"]."&type=both&depth=50");

	//	Format the ask orders for use in the template
		$count = 0;
		foreach ($orderbook["result"]["sell"] as $order)
		{
			$response["orders"]["ask"][$count]["price"] = number_format ($order["Rate"], "8", ".", "");
			$response["orders"]["ask"][$count]["quantity"] = number_format ($order["Quantity"], "8", ".", "");
			$response["orders"]["ask"][$count]["total"] = bcmul ($response["orders"]["ask"][$count]["quantity"], $response["orders"]["ask"][$count]["price"], 8);
			$count++;
		}
		
	//	Format the bid orders for use in the template
		$count = 0;
		foreach ($orderbook["result"]["buy"] as $order)
		{
			$response["orders"]["bid"][$count]["price"] = number_format ($order["Rate"], "8", ".", "");
			$response["orders"]["bid"][$count]["quantity"] = number_format ($order["Quantity"], "8", ".", "");
			$response["orders"]["bid"][$count]["total"] = bcmul ($response["orders"]["bid"][$count]["quantity"], $response["orders"]["bid"][$count]["price"], 8);
			$count++;
		}

	//	Return the data to the calling code
		return $response;
	}
	
	function spectra_market_bleutrade ($market)
	{
	//	Retrieve the market history
		$history = spectra_fetch_json ("https://bleutrade.com/api/v2/public/getmarkethistory?market=".$market["mkt_id"]."&type=all&depth=50");
	
	//	Format the trade history for use in the template
		$count = 0;
		foreach ($history["result"] as $trade)
		{
			$response["history"][$count]["type"] = $trade["OrderType"];
			$response["history"][$count]["quantity"] = number_format ($trade["Quantity"], "8", ".", "");
			$response["history"][$count]["price"] = number_format ($trade["Price"], "8", ".", "");
			$response["history"][$count]["total"] = number_format ($trade["Total"], "8", ".", "");
			$response["history"][$count]["time"] = $trade["TimeStamp"];
			
			$count++;
		}
		
	//	Retrieve the market orderbook
		$orderbook = spectra_fetch_json ("https://bleutrade.com/api/v2/public/getorderbook?market=".$market["mkt_id"]."&type=ALL&count=50");

	//	Format the ask orders for use in the template
		$count = 0;
		foreach ($orderbook["result"]["sell"] as $order)
		{
			$response["orders"]["ask"][$count]["price"] = number_format ($order["Rate"], "8", ".", "");
			$response["orders"]["ask"][$count]["quantity"] = number_format ($order["Quantity"], "8", ".", "");
			$response["orders"]["ask"][$count]["total"] = bcmul ($response["orders"]["ask"][$count]["quantity"], $response["orders"]["ask"][$count]["price"], 8);
			$count++;
		}
		
	//	Format the bid orders for use in the template
		$count = 0;
		foreach ($orderbook["result"]["buy"] as $order)
		{
			$response["orders"]["bid"][$count]["price"] = number_format ($order["Rate"], "8", ".", "");
			$response["orders"]["bid"][$count]["quantity"] = number_format ($order["Quantity"], "8", ".", "");
			$response["orders"]["bid"][$count]["total"] = bcmul ($response["orders"]["bid"][$count]["quantity"], $response["orders"]["bid"][$count]["price"], 8);
			$count++;
		}

	//	Return the data to the calling code
		return $response;
	}
	
	function spectra_market_ccex ($market)
	{
	//	Format dates for C-Cex
		$to = date ("Y-m-d", time ());
		$from = date ("Y-m-d", (time () - (60 * 60 * 48)));
		
	//	Retrieve the market history
		$history = spectra_fetch_json ("https://c-cex.com/t/s.html?a=tradehistory&d1=".$from."&d2=".$to."&pair=".$market["mkt_id"]);

	//	Format the trade history for use in the template
		$count = 0;
		foreach ($history["return"] as $trade)
		{
			$response["history"][$count]["type"] = $trade["type"];
			$response["history"][$count]["quantity"] = number_format ($trade["amount"], "8", ".", "");
			$response["history"][$count]["price"] = number_format ($trade["rate"], "8", ".", "");
			$response["history"][$count]["total"] = bcmul ($response["history"][$count]["quantity"], $response["history"][$count]["price"], 8);
			$response["history"][$count]["time"] = $trade["datetime"];
			
			$count++;
		}
		
	//	Retrieve the market orderbook
	//	(c-cex does not have a good API for this)
	//	$orderbook = spectra_fetch_json ("");

	//	Format the ask orders for use in the template
	//	(c-cex does not have a good API for this)
		$response["orders"]["ask"] = "";
		
	//	Format the bid orders for use in the template
	//	(c-cex does not have a good API for this)
		$response["orders"]["bid"] = "";

	//	Return the data to the calling code
		return $response;
	}
	
	function spectra_market_cryptopia ($market)
	{
	//	Retrieve the market history
		$history = spectra_fetch_json ("https://www.cryptopia.co.nz/api/GetMarketHistory/".$market["mkt_id"]);
	
	//	Format the trade history for use in the template
		$count = 0;
		foreach ($history["Data"] as $trade)
		{
			$response["history"][$count]["type"] = $trade["Type"];
			$response["history"][$count]["quantity"] = number_format ($trade["Amount"], "8", ".", "");
			$response["history"][$count]["price"] = number_format ($trade["Price"], "8", ".", "");
			$response["history"][$count]["total"] = number_format ($trade["Total"], "8", ".", "");
			$response["history"][$count]["time"] = date ("M d, Y  H:i:s", $trade["Timestamp"]);
			
			$count++;
		}
		
	//	Retrieve the market orderbook
		$orderbook = spectra_fetch_json ("https://www.cryptopia.co.nz/api/GetMarketOrders/".$market["mkt_id"]);

	//	Format the ask orders for use in the template
		$count = 0;
		foreach ($orderbook["Data"]["Sell"] as $order)
		{
			$response["orders"]["ask"][$count]["price"] = number_format ($order["Price"], "8", ".", "");
			$response["orders"]["ask"][$count]["quantity"] = number_format ($order["Volume"], "8", ".", "");
			$response["orders"]["ask"][$count]["total"] = number_format ($order["Total"], "8", ".", "");
			$count++;
		}
		
	//	Format the bid orders for use in the template
		$count = 0;
		foreach ($orderbook["Data"]["Buy"] as $order)
		{
			$response["orders"]["bid"][$count]["price"] = number_format ($order["Price"], "8", ".", "");
			$response["orders"]["bid"][$count]["quantity"] = number_format ($order["Volume"], "8", ".", "");
			$response["orders"]["bid"][$count]["total"] = number_format ($order["Total"], "8", ".", "");
			$count++;
		}

	//	Return the data to the calling code
		return $response;
	}
	
	function spectra_market_cryptsy ($market)
	{
	//	Retrieve the market history
		$history = spectra_fetch_json ("https://api.cryptsy.com/api/v2/markets/".$market["mkt_id"]."/tradehistory");
	
	//	Format the trade history for use in the template
		$count = 0;
		foreach ($history["data"] as $trade)
		{
			$response["history"][$count]["type"] = $trade["initiate_ordertype"];
			$response["history"][$count]["quantity"] = number_format ($trade["quantity"], "8", ".", "");
			$response["history"][$count]["price"] = number_format ($trade["tradeprice"], "8", ".", "");
			$response["history"][$count]["total"] = number_format ($trade["total"], "8", ".", "");
			$response["history"][$count]["time"] = date ("M d, Y  H:i:s", $trade["timestamp"]);
			
			$count++;
		}
		
	//	Retrieve the market orderbook
		$orderbook = spectra_fetch_json ("https://api.cryptsy.com/api/v2/markets/".$market["mkt_id"]."/orderbook");

	//	Format the ask orders for use in the template
		$count = 0;
		foreach ($orderbook["data"]["sellorders"] as $order)
		{
			$response["orders"]["ask"][$count]["price"] = number_format ($order["price"], "8", ".", "");
			$response["orders"]["ask"][$count]["quantity"] = number_format ($order["quantity"], "8", ".", "");
			$response["orders"]["ask"][$count]["total"] = number_format ($order["total"], "8", ".", "");
			$count++;
		}
		
	//	Format the bid orders for use in the template
		$count = 0;
		foreach ($orderbook["data"]["buyorders"] as $order)
		{
			$response["orders"]["bid"][$count]["price"] = number_format ($order["price"], "8", ".", "");
			$response["orders"]["bid"][$count]["quantity"] = number_format ($order["quantity"], "8", ".", "");
			$response["orders"]["bid"][$count]["total"] = number_format ($order["total"], "8", ".", "");
			$count++;
		}

	//	Return the data to the calling code
		return $response;
	}
	
	function spectra_market_poloniex ($market)
	{
	//	Retrieve the market history
		$history = spectra_fetch_json ("https://poloniex.com/public?command=returnTradeHistory&currencyPair=".$market["mkt_id"]);
	
	//	Format the trade history for use in the template
		$count = 0;
		foreach ($history as $trade)
		{
			$response["history"][$count]["type"] = $trade["type"];
			$response["history"][$count]["quantity"] = number_format ($trade["amount"], "8", ".", "");
			$response["history"][$count]["price"] = number_format ($trade["rate"], "8", ".", "");
			$response["history"][$count]["total"] = number_format ($trade["total"], "8", ".", "");
			$response["history"][$count]["time"] = $trade["date"];
			
			$count++;
		}
		
	//	Retrieve the market orderbook
		$orderbook = spectra_fetch_json ("https://poloniex.com/public?command=returnOrderBook&currencyPair=".$market["mkt_id"]);

	//	Format the ask orders for use in the template
		$count = 0;
		foreach ($orderbook["asks"] as $order)
		{
			$response["orders"]["ask"][$count]["price"] = number_format ($order[0], "8", ".", "");
			$response["orders"]["ask"][$count]["quantity"] = number_format ($order[1], "8", ".", "");
			$response["orders"]["ask"][$count]["total"] = bcmul ($response["orders"]["ask"][$count]["quantity"], $response["orders"]["ask"][$count]["price"], 8);
			$count++;
		}
		
	//	Format the bid orders for use in the template
		$count = 0;
		foreach ($orderbook["bids"] as $order)
		{
			$response["orders"]["bid"][$count]["price"] = number_format ($order[0], "8", ".", "");
			$response["orders"]["bid"][$count]["quantity"] = number_format ($order[1], "8", ".", "");
			$response["orders"]["bid"][$count]["total"] = bcmul ($response["orders"]["bid"][$count]["quantity"], $response["orders"]["bid"][$count]["price"], 8);
			$count++;
		}

	//	Return the data to the calling code
		return $response;
	}
	
	function spectra_market_yobit ($market)
	{
	//	Retrieve the market history
		$history = spectra_fetch_json ("https://yobit.net/api/3/trades/".$market["mkt_id"]);
	
	//	Format the trade history for use in the template
		$count = 0;
		foreach ($history[$market["mkt_id"]] as $trade)
		{
			$response["history"][$count]["type"] = $trade["type"];
			$response["history"][$count]["quantity"] = number_format ($trade["amount"], "8", ".", "");
			$response["history"][$count]["price"] = number_format ($trade["price"], "8", ".", "");
			$response["history"][$count]["total"] = bcmul ($response["history"][$count]["quantity"], $response["history"][$count]["price"], 8);
			$response["history"][$count]["time"] = date ("M d, Y  H:i:s", $trade["timestamp"]);
			
			$count++;
		}
		
	//	Retrieve the market orderbook
		$orderbook = spectra_fetch_json ("https://yobit.net/api/3/depth/".$market["mkt_id"]);

	//	Format the ask orders for use in the template
		$count = 0;
		foreach ($orderbook[$market["mkt_id"]]["asks"] as $order)
		{
			$response["orders"]["ask"][$count]["price"] = number_format ($order[0], "8", ".", "");
			$response["orders"]["ask"][$count]["quantity"] = number_format ($order[1], "8", ".", "");
			$response["orders"]["ask"][$count]["total"] = bcmul ($response["orders"]["ask"][$count]["quantity"], $response["orders"]["ask"][$count]["price"], 8);
			$count++;
		}
		
	//	Format the bid orders for use in the template
		$count = 0;
		foreach ($orderbook[$market["mkt_id"]]["bids"] as $order)
		{
			$response["orders"]["bid"][$count]["price"] = number_format ($order[0], "8", ".", "");
			$response["orders"]["bid"][$count]["quantity"] = number_format ($order[1], "8", ".", "");
			$response["orders"]["bid"][$count]["total"] = bcmul ($response["orders"]["bid"][$count]["quantity"], $response["orders"]["bid"][$count]["price"], 8);
			$count++;
		}

	//	Return the data to the calling code
		return $response;
	}
	
/******************************************************************************
	cURL Request Processing
******************************************************************************/

	function spectra_fetch_json ($url)
	{
	//	Everything is better with cookies
		$url_stripdash = str_replace ("-", "_", $url);
		$url_decomp = explode ("/", $url_stripdash);
		
		$cookie = $GLOBALS["path"]["root"]."/lib/cache/".$url_decomp[2];
	
	//	Create a cURL connection
		$obj_request = curl_init($url);
	
	//	Configure cURL for this request type
		curl_setopt ($obj_request, CURLOPT_USERAGENT, "SPECTRA");
		curl_setopt ($obj_request, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($obj_request, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt ($obj_request, CURLOPT_TIMEOUT, 60);
		curl_setopt ($obj_request, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt ($obj_request, CURLOPT_COOKIEJAR, $cookie);

	//	Execute the request	
		$response = curl_exec($obj_request);
			
	//	Inspect Connection Data for HTTP Errors
		$curl_error = curl_error ($obj_request);
		
	//	Close the connection
		curl_close($obj_request);
		
	//	Return an error code in case of failure
		if ($curl_error > 0)
		{
			return $curl_error;
		}
		
	//	Return the data
		return json_decode ($response, 1);
	}

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>