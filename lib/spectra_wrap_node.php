<?php 
//	This script enables the SPECTRA Block Explorer's JSON-RPC
//	interface for a bitcoin-style cryptocurrency wallet (node).

/******************************************************************************
	Node / Network Info Wrappers
******************************************************************************/

	function getinfo ()
	{
		$request_array["method"] = "getinfo";
	
		return (daemon_fetch ($request_array));
	}
	
	function getmininginfo ()
	{
		$request_array["method"] = "getmininginfo";
	
		return (daemon_fetch ($request_array));
	}
	
	function getdifficulty ()
	{
		$request_array["method"] = "getdifficulty";
	
		return (daemon_fetch ($request_array));
	}
	
	function getnetworkhashps ()
	{
		$request_array["method"] = "getnetworkhashps";
	
		return (daemon_fetch ($request_array));
	}
	
	function getpeerinfo ()
	{
		$request_array["method"] = "getpeerinfo";
	
		return (daemon_fetch ($request_array));
	}
	
/******************************************************************************
	Node / Chain Info Wrappers
******************************************************************************/

	function getblockcount ()
	{
		$request_array["method"] = "getblockcount";
	
		return (daemon_fetch ($request_array));
	}
	
	function getblockhash ($blockheight)
	{
		$request_array["method"] = "getblockhash";
	
		$request_array["params"][0] = $blockheight;
	
		return (daemon_fetch ($request_array));
	}
	
	function getbestblockhash ()
	{
		$request_array["method"] = "getbestblockhash";
	
		return (daemon_fetch ($request_array));
	}
	
	function getblock ($blockhash, $verbose=FALSE)
	{
		$request_array["method"] = "getblock";
	
		$request_array["params"][0] = $blockhash;
		
	//	For dealing with multiple chains SPECTRA requires an 
	//	override on the "verbose" parameter
		if (system_flag_get ("block_req_verbose") == 0)
		{
			$request_array["params"][1] = FALSE;
		}
		
		else
		{
			$request_array["params"][1] = TRUE;
		}
	
		return (daemon_fetch ($request_array));
	}
	
	function getrawtransaction ($tx_id, $verbose=1)
	{
		$request_array["method"] = "getrawtransaction";
	
		$request_array["params"][0] = $tx_id;
	
		$request_array["params"][1] = $verbose;
	
		return (daemon_fetch ($request_array));
	}
	
/******************************************************************************
	Signature Verification
******************************************************************************/

	function verifymessage ($address, $signature, $message)
	{
		$request_array["method"] = "verifymessage";
	
		$request_array["params"][0] = $address;
	
		$request_array["params"][1] = $signature;
	
		$request_array["params"][2] = $message;
		
		$response = daemon_fetch ($request_array);
		
		if ($response == "1")
		{
			return "true";
		}
		
		else
		{
			return "false";
		}
	}
	
/******************************************************************************
	JSON-RPC Handler
******************************************************************************/
	function daemon_fetch ($request_array)
	{
	//	Encode the request as JSON for the wallet
		$request = json_encode ($request_array);

	//	Create curl connection object
		$coind = curl_init();
		
	//	Set the IP address and port for the wallet server
		curl_setopt ($coind, CURLOPT_URL, $GLOBALS["node"]["host"]);
		curl_setopt ($coind, CURLOPT_PORT, $GLOBALS["node"]["port"]);
	
	//	Tell curl to use basic HTTP authentication
		curl_setopt($coind, CURLOPT_HTTPAUTH, CURLAUTH_BASIC) ;
	
	//	Provide the username and password for the connection
		curl_setopt($coind, CURLOPT_USERPWD, $GLOBALS["node"]["user"].":".$GLOBALS["node"]["pass"]);
	
	//	Tell curl to use a 30 section connection timeout
		curl_setopt($coind, CURLOPT_CONNECTTIMEOUT , 30);
	
	//	Tell curl to use a 60 second response timeout
		curl_setopt($coind, CURLOPT_TIMEOUT , 60);
	
	//	JSON-RPC Header for the wallet
		curl_setopt($coind, CURLOPT_HTTPHEADER, array ("Content-type: application/json"));
	
	//	Prepare curl for a POST request
		curl_setopt($coind, CURLOPT_POST, TRUE);
	
	//	Provide the JSON data for the request
		curl_setopt($coind, CURLOPT_POSTFIELDS, $request); 

	//	Indicate we want the response as a string
		curl_setopt($coind, CURLOPT_RETURNTRANSFER, TRUE);
	
	//	Required by RPC SSL self-signed cert
		curl_setopt($coind, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($coind, CURLOPT_SSL_VERIFYHOST, FALSE);

	//	Execute the request	
		$response_data = curl_exec($coind);
		
	//	Gather curl diagnostics	
		$curl_resp = curl_error ($coind);
		
	//	Close the connection
		curl_close($coind);
		
	//	Check for conection errors
		if (isset ($curl_resp) && $curl_resp != "")
		{
			$info["error"]["code"] = 1;
			$info["error"]["message"] = $curl_resp;
			
			return $info;
		}
		
	//	There is a known issue with json_decode and large int/decimal
	//	values that requires they be converted to strings in order to
	//	retain their precision
		$recoded = preg_replace('/":([0-9]+)\.([0-9]+)(,|})/', '":"$1.$2"$3', $response_data);
	
	//	The JSON response is read into an array
		$info = json_decode ($recoded, TRUE);
		
	//	If an error message was received the message is returned
	//	to the calling code as a string.	
		if (isset ($info["error"]) && $info["error"] != "")
		{
			return $info;
		}
		
	//	If there was no error the result is returned to the calling code
		else
		{
			return $info["result"];
		}
	}

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>