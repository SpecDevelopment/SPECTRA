<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

//	This function provides status and formatting for API responses	
	function spectra_api_response ($data, $status=1, $message="")
	{
	//	Build the response array
		$response["status"] = $status;
		$response["message"] = $message;
		$response["data"] = $data;
		
	//	Apply the expected JSON format
		echo json_encode($response);
		
	//	Update the rate flag timer
		system_flag_set (hash_hmac ("sha256", $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]), time ());

		exit;
	}

/******************************************************************************
	Connection and Request Error Handling
******************************************************************************/

//	If this IP has passed the rate limit the request is rejected
	$last_req = system_flag_get (hash_hmac ("sha256", $_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]));

	if(time() < floor ($last_req + $GLOBALS["api_rate_limit"]))
	{
		spectra_api_response ("", 0, "Request Rate Limit Exceeded");
	}

//	If no method is specified the API returns an error
	if (!isset ($_REQUEST["method"]) || $_REQUEST["method"] == "")
	{
		spectra_api_response ("", 0, "Method Is Required");
	}
	
/******************************************************************************
	Ticker API 
******************************************************************************/

	if ($_REQUEST["method"] == "ticker")
	{
	//	The ticker is requested from each enabled exchange
		foreach ($GLOBALS["markets"] as $market)
		{
		//	Format the request for this exchange	
			$function_name = "spectra_ticker_".$market["exch_id"];
				
		//	Cannot use [] for reading output
			$ticker_data = $function_name ($market);
				
		//	additional data for API response
			$ticker_data["exchange"] = $market["exch_display"];
			$ticker_data["market"] = $market["mkt_display"];
				
		//	Consolidate output
			$ticker[] = $ticker_data;
		}
	
	//	The consolidated tickers are returned
		spectra_api_response ($ticker);
	}
	
/******************************************************************************
	Node / Network API 
******************************************************************************/
	
	if ($_REQUEST["method"] == "getinfo")
	{
		$requested = getinfo ();

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
	if ($_REQUEST["method"] == "getmininginfo")
	{
		$requested = getmininginfo ();

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
	if ($_REQUEST["method"] == "getdifficulty")
	{
		$requested = getdifficulty ();
	
		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
	if ($_REQUEST["method"] == "getnetworkhashps")
	{
		$requested = getnetworkhashps ();

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
	if ($_REQUEST["method"] == "getpeerinfo")
	{
		$requested = getpeerinfo ();

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
/******************************************************************************
	Node / Chain API 
******************************************************************************/
	
	if ($_REQUEST["method"] == "getblockcount")
	{
		$requested = getblockcount ();

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
	if ($_REQUEST["method"] == "getblockhash")
	{
		if (!isset ($_REQUEST["height"]))
		{
			spectra_api_response ("", 0, "Block Height (&height=xxxxx) Is Required For Method 'getblockhash'");
		}
		
		$requested = getblockhash ((int) $_REQUEST["height"]);

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
	if ($_REQUEST["method"] == "getbestblockhash")
	{
		$requested = getbestblockhash ();

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
	if ($_REQUEST["method"] == "getblock")
	{
		if (!isset ($_REQUEST["hash"]))
		{
			spectra_api_response ("", 0, "Block Height (&hash=xxxxx) Is Required For Method 'getblock'");
		}
		
		$requested = getblock ($_REQUEST["hash"]);

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
	if ($_REQUEST["method"] == "gettransaction")
	{
		if (!isset ($_REQUEST["txid"]))
		{
			spectra_api_response ("", 0, "Transaction ID (&txid=xxxxx) Is Required For Method 'gettransaction'");
		}
		
		$requested = getrawtransaction ($_REQUEST["txid"]);

		if (isset ($requested["error"]) && $requested["error"] != "")
		{
			spectra_api_response ("", 0, $requested["error"]["message"]);
		}
		
		else
		{
			spectra_api_response ($requested);
		}		
	}
	
/******************************************************************************
	Address API 
******************************************************************************/

	if ($_REQUEST["method"] == "isaddress")
	{
		if (!isset ($_REQUEST["address"]))
		{
			spectra_api_response ("", 0, "Address (?address=xxxxx) Is Required For Method 'isaddress'");
		}
		
		
		if (spectra_address_exists ($_REQUEST["address"]))
		{
			spectra_api_response ("Address Exists");
		}
		
		else
		{
			spectra_api_response ("", 0, "Address Not Found");
		}
	}
	
	if ($_REQUEST["method"] == "getbalance")
	{
		if (!isset ($_REQUEST["address"]))
		{
			spectra_api_response ("", 0, "Address (?address=xxxxx) Is Required For Method 'getbalance'");
		}
		
		spectra_api_response (spectra_address_balance ($_REQUEST["address"]));
	}
	
	if ($_REQUEST["method"] == "verifymessage")
	{
		spectra_api_response (verifymessage ($_GET["address"], $_GET["signature"], urldecode ($_GET["message"])));
	}
	
	if ($_REQUEST["method"] == "moneysupply")
	{
		
		spectra_api_response (spectra_money_supply ());
	}
	
/******************************************************************************
	Explorer API 
******************************************************************************/

	if ($_REQUEST["method"] == "false")
	{
	//
	}
	

	
/******************************************************************************
	Final Error trap
******************************************************************************/

	spectra_api_response ("", 0, "Invalid or Unrecognized Request");

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>