<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

	spectra_site_head ($GLOBALS["currency"]["code"]." API Guide");
	
	echo "	<h1> ".$GLOBALS["currency"]["name"]." Explorer API </h1> \n";
	
	echo "	<div class=\"panel_wide\"> \n\n";
	
	echo "		<h2> About the API: </h2> \n\n";
	
	echo "		<p> \n";
	echo "			This page provides instructions and sample output \n";
	echo "			for working with the SPECTRA block explorer API. \n";
	echo "		</p> \n\n";
	
	echo "		<p> \n";
	echo "			Each API response is a JSON encoded array with \n";
	echo "			three fields provided to enable consistent status \n";
	echo "			and error information for use in your application. \n";
	echo "		</p> \n\n";
	
	echo "		<h2> Sample Request And Error Output: </h2> \n\n";
	
	echo "		<p> \n";
	echo "			The API endpoint for this SPECTRA installation can be reached at: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			The following output is an intentionally generated \n";
	echo "			error.  In case of error, the API will return a \n";
	echo "			status of `0` and attempt to populate the \n";
	echo "			message field with an informative error message. \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 0 \n";
	echo "    [message] => Invalid or Unrecognized Request \n";
	echo "    [data] =>  \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			The rest of this page describes the available \n";
	echo "			API methods and provides samples using live calls\n";
	echo "			to the local API. \n";
	echo "		</p> \n\n";
	
	echo "	</div> \n\n";
	
/******************************************************************************
	Market API Information
******************************************************************************/
	
	echo "	<h1> Market Information </h1> \n";
	
	echo "	<div class=\"panel_wide\"> \n\n";
	
	echo "		<h2> Method: ticker </h2> \n\n";

	echo "		<p> \n";
	echo "			The ticker API method will produce a consolidated  \n";
	echo "			ticker for all markets enabled by the admin. \n";
	echo "		</p> \n\n";
	
	echo "		<p> \n";
	echo "			The ticker method is a pass-through request \n";
	echo "			to each enabled exchange, the data is formatted, \n";
	echo "			consolidated, and returned without caching. \n";
	echo "		</p> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'ticker' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>No Parameter Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=ticker \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'ticker' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => Array \n";
	echo "        ( \n";
	echo "            [0] => Array \n";
	echo "                ( \n";
	echo "                    [bid] => 0.00000012 \n";
	echo "                    [ask] => 0.00000013 \n";
	echo "                    [last] => 0.00000013 \n";
	echo "                    [vol] => 1585441.07300570 \n";
	echo "                    [exchange] => Cryptsy \n";
	echo "                    [market] => SPEC / BTC \n";
	echo "                ) \n";
	echo " \n";
	echo "            [3] => Array \n";
	echo "                ( \n";
	echo "                    [bid] => 0.00000011 \n";
	echo "                    [ask] => 0.00000014 \n";
	echo "                    [vol] => 165559.23200289 \n";
	echo "                    [last] => 0.00000014 \n";
	echo "                    [exchange] => Bleutrade \n";
	echo "                    [market] => SPEC / BTC \n";
	echo "                ) \n";
	echo "        ) \n";
	echo " \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
	echo "	</div> \n\n";
	
/******************************************************************************
	Network API Information
******************************************************************************/
	
	echo "	<h1> Network Information </h1> \n";
	
	echo "	<div class=\"panel_wide\"> \n\n";
	
	echo "		<p> \n";
	echo "			The following API methods return information \n";
	echo "			about the ".$GLOBALS["currency"]["name"]." network \n";
	echo "			as provided by the block explorer node. \n";
	echo "		</p> \n\n";
	
	echo "		<p> \n";
	echo "			Response data is not cached at this time. \n";
	echo "		</p> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: getinfo </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getinfo' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>No Parameter Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getinfo \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getinfo' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => Array \n";
	echo "        ( \n";
	echo "            [version] => 1000005 \n";
	echo "            [protocolversion] => 70002 \n";
	echo "            [walletversion] => 60000 \n";
	echo "            [balance] => 0.00000000 \n";
	echo "            [blocks] => 10587 \n";
	echo "            [timeoffset] => -1 \n";
	echo "            [connections] => 4 \n";
	echo "            [proxy] =>  \n";
	echo "            [difficulty] => 62.45401651 \n";
	echo "            [testnet] =>  \n";
	echo "            [keypoololdest] => 1439439959 \n";
	echo "            [keypoolsize] => 101 \n";
	echo "            [paytxfee] => 0.00000000 \n";
	echo "            [mininput] => 0.00001000 \n";
	echo "            [errors] =>  \n";
	echo "        ) \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: getmininginfo </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getmininginfo' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>No Parameter Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getmininginfo \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getmininginfo' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => Array \n";
	echo "        ( \n";
	echo "            [blocks] => 10587 \n";
	echo "            [currentblocksize] => 0 \n";
	echo "            [currentblocktx] => 0 \n";
	echo "            [difficulty] => 62.45401651 \n";
	echo "            [errors] =>  \n";
	echo "            [generate] =>  \n";
	echo "            [genproclimit] => -1 \n";
	echo "            [hashespersec] => 0 \n";
	echo "            [networkhashps] => 336112204 \n";
	echo "            [pooledtx] => 0 \n";
	echo "            [testnet] =>  \n";
	echo "        ) \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: getdifficulty </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getdifficulty' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>No Parameter Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getdifficulty \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getdifficulty' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => 62.45401651 \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	

//*****************************************************************************
	if (!is_array (getnetworkhashps ()))
	{
		echo "		<h2> Method: getnetworkhashps </h2> \n\n";
		
		echo "		<p> \n";
		echo "			Sample 'getnetworkhashps' API request: \n";
		echo "		</p> \n\n";
		
		echo "		<ul> \n";
		echo "			<li>No Parameter Is Required</li>\n";
		echo "		</ul> \n\n";
	
		echo "		<div class=\"api_sample\"> \n";
		echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getnetworkhashps \n";
		echo "		</div> \n\n";
		
		echo "		<p> \n";
		echo "			Sample 'getnetworkhashps' API response: \n";
		echo "		</p> \n\n";
		
		echo "		<div class=\"api_sample\"> \n";
		echo "			<pre> \n";
		echo "Array \n";
		echo "( \n";
		echo "    [status] => 1 \n";
		echo "    [message] =>  \n";
		echo "    [data] => 336112204 \n";
		echo ") \n";
		echo "			</pre> \n";
		echo "		</div> \n\n";
	}
	
//*****************************************************************************
	echo "		<h2> Method: getpeerinfo </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getpeerinfo' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>No Parameter Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getpeerinfo \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getpeerinfo' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => Array \n";
	echo "        ( \n";
	echo "            [0] => Array \n";
	echo "                ( \n";
	echo "                    [addr] => 198.50.217.13:57992 \n";
	echo "                    [services] => 00000003 \n";
	echo "                    [lastsend] => 1440091304 \n";
	echo "                    [lastrecv] => 1440091304 \n";
	echo "                    [bytessent] => 3012 \n";
	echo "                    [bytesrecv] => 690 \n";
	echo "                    [blocksrequested] => 0 \n";
	echo "                    [conntime] => 1440091304 \n";
	echo "                    [version] => 70002 \n";
	echo "                    [subver] => /Satoshi:1.0.0.5/ \n";
	echo "                    [inbound] => 1 \n";
	echo "                    [startingheight] => 10587 \n";
	echo "                    [banscore] => 0 \n";
	echo "                    [syncnode] => 1 \n";
	echo "                ) \n";
	echo " \n";
	echo "            [1] => Array \n";
	echo "                ( \n";
	echo "                    [addr] => 109.155.79.53:4319 \n";
	echo "                    [services] => 00000003 \n";
	echo "                    [lastsend] => 1440091305 \n";
	echo "                    [lastrecv] => 1440091306 \n";
	echo "                    [bytessent] => 229 \n";
	echo "                    [bytesrecv] => 2425 \n";
	echo "                    [blocksrequested] => 0 \n";
	echo "                    [conntime] => 1440091305 \n";
	echo "                    [version] => 70002 \n";
	echo "                    [subver] => /Satoshi:1.0.0.5/ \n";
	echo "                    [inbound] =>  \n";
	echo "                    [startingheight] => 10587 \n";
	echo "                    [banscore] => 0 \n";
	echo "                ) \n";
	echo "        ) \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
	echo "	</div> \n\n";
	
/******************************************************************************
	Block Chain API Information
******************************************************************************/
	
	echo "	<h1> Block Chain Information </h1> \n";
	
	echo "	<div class=\"panel_wide\"> \n\n";
	
	echo "		<p> \n";
	echo "			The following API methods return information \n";
	echo "			about the ".$GLOBALS["currency"]["name"]." block chain \n";
	echo "			as provided by the block explorer node. \n";
	echo "		</p> \n\n";
	
	echo "		<p> \n";
	echo "			Response data is not cached at this time. \n";
	echo "		</p> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: getblockcount </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getblockcount' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>No Parameter Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getblockcount \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getblockcount' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => 10587 \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: getblockhash </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getblockhash' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>'height' Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getblockhash&height=2 \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getblockhash' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => 68dd2aa055ff44a977cbfeac0aee86d73dc0f81578a3a9ffb5b808d89179a5f6 \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: getbestblockhash </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getbestblockhash' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>No Parameter Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getbestblockhash \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getbestblockhash' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => 68dd2aa055ff44a977cbfeac0aee86d73dc0f81578a3a9ffb5b808d89179a5f6 \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: getblock </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getblock' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>'hash' Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getblock&hash=fae203fca989aeedf3efb85f75e8e88080b3cd9dfdd93b68d583fdabfb2c3047 \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getblock' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => Array \n";
	echo "        ( \n";
	echo "            [hash] => fae203fca989aeedf3efb85f75e8e88080b3cd9dfdd93b68d583fdabfb2c3047 \n";
	echo "            [confirmations] => 1 \n";
	echo "            [size] => 431 \n";
	echo "            [height] => 10587 \n";
	echo "            [version] => 2 \n";
	echo "            [merkleroot] => 26c0e4a07ef9ffa66641f856a8a0e6b9d889cec8041b3833d4d3bf2a373bf19e \n";
	echo "            [tx] => Array \n";
	echo "                ( \n";
	echo "                    [0] => 919dab2189d1efd72b16b5378af39ae79c17be2f0919a0629dad3eec48388397 \n";
	echo "                    [1] => 9d4c4256679c09f1e983ed2f10bae07e1aa5bc520d6d03ff91104d74788295eb \n";
	echo "                ) \n";
	echo " \n";
	echo "            [time] => 1440090430 \n";
	echo "            [nonce] => 17024140 \n";
	echo "            [bits] => 1c041955 \n";
	echo "            [difficulty] => 62.45401651 \n";
	echo "            [previousblockhash] => ee30b72368a1f63bc4989fd798f29f41e95b46f105e2ce37b06ec393a32229a6 \n";
	echo "        ) \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: gettransaction </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'gettransaction' API request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>'txid' Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=gettransaction&txid=919dab2189d1efd72b16b5378af39ae79c17be2f0919a0629dad3eec48388397 \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'gettransaction' API response: \n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => Array \n";
	echo "        ( \n";
	echo "            [hex] => 01000000010000000000000000000000000000000000000000000000000000000000000000ffffffff26025b29062f503253482f046009d6550877fffffecf2600000d2f6e6f64655374726174756d2f000000000100a2941a1d0000001976a9145067bf5c5f124ba4d02275ae4dbdb2fd4ea8186d88ac00000000 \n";
	echo "            [txid] => 919dab2189d1efd72b16b5378af39ae79c17be2f0919a0629dad3eec48388397 \n";
	echo "            [version] => 1 \n";
	echo "            [locktime] => 0 \n";
	echo "            [vin] => Array \n";
	echo "                ( \n";
	echo "                    [0] => Array \n";
	echo "                        ( \n";
	echo "                            [coinbase] => 025b29062f503253482f046009d6550877fffffecf2600000d2f6e6f64655374726174756d2f \n";
	echo "                            [sequence] => 0 \n";
	echo "                        ) \n";
	echo " \n";
	echo "                ) \n";
	echo " \n";
	echo "            [vout] => Array \n";
	echo "                ( \n";
	echo "                    [0] => Array \n";
	echo "                        ( \n";
	echo "                            [value] => 1250.00000000 \n";
	echo "                            [n] => 0 \n";
	echo "                            [scriptPubKey] => Array \n";
	echo "                                ( \n";
	echo "                                    [asm] => OP_DUP OP_HASH160 5067bf5c5f124ba4d02275ae4dbdb2fd4ea8186d OP_EQUALVERIFY OP_CHECKSIG \n";
	echo "                                    [hex] => 76a9145067bf5c5f124ba4d02275ae4dbdb2fd4ea8186d88ac \n";
	echo "                                    [reqSigs] => 1 \n";
	echo "                                    [type] => pubkeyhash \n";
	echo "                                    [addresses] => Array \n";
	echo "                                        ( \n";
	echo "                                            [0] => 59iBBwF2p329DLU8MJiNuJFFVvff48fxT8 \n";
	echo "                                        ) \n";
	echo " \n";
	echo "                                ) \n";
	echo " \n";
	echo "                        ) \n";
	echo " \n";
	echo "                ) \n";
	echo " \n";
	echo "            [blockhash] => fae203fca989aeedf3efb85f75e8e88080b3cd9dfdd93b68d583fdabfb2c3047 \n";
	echo "            [confirmations] => 1 \n";
	echo "            [time] => 1440090430 \n";
	echo "            [blocktime] => 1440090430 \n";
	echo "        ) \n";
	echo ")				 \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
	echo "	</div> \n\n";
	
/******************************************************************************
	Address API Information
******************************************************************************/
	
	echo "	<h1> Address Information </h1> \n";
	
	echo "	<div class=\"panel_wide\"> \n\n";
	
	echo "		<p> \n";
	echo "			The SPECTRA Address API allows users to verify \n";
	echo "			addresses and balance information. \n";
	echo "		</p> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: isaddress </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'isaddress' API Request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>'address' Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=isaddress&address=5LsbCoYa8xH5Uig1pvaqdwmym6zptL5r8k \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'isaddress' API Response:\n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => Address Exists \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: getbalance</h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getbalance' API Request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>'address' Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=getbalance&address=5LsbCoYa8xH5Uig1pvaqdwmym6zptL5r8k \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'getbalance' API Response:\n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => 300000000.00000000 \n";
	echo ")  \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: verifymessage</h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'verifymessage' API Request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>'address' Is Required</li>\n";
	echo "			<li>'message' Is Required (URL Encode Accepted)</li>\n";
	echo "			<li>'signature' Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=verifymessage&address=5FPpJZmwhnMxrZefA1prarSeKgwkH6JPNp&message=hello&signature=IN3ijsyKxWoSNnqS5L12UwO6pQkhtef10PgOCs7WW9QW6gsCtqwuwdt7w3qNv0V8SBxwAc2SSFNRGvfIMNhuup8= \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'verifymessage' API Response:\n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => true \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
//*****************************************************************************
	echo "		<h2> Method: moneysupply </h2> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'moneysupply' API Request: \n";
	echo "		</p> \n\n";
	
	echo "		<ul> \n";
	echo "			<li>No Parameter Is Required</li>\n";
	echo "		</ul> \n\n";

	echo "		<div class=\"api_sample\"> \n";
	echo "			".$GLOBALS["url"]["home"]."api_fetch.php?method=moneysupply \n";
	echo "		</div> \n\n";
	
	echo "		<p> \n";
	echo "			Sample 'moneysupply' API Response:\n";
	echo "		</p> \n\n";
	
	echo "		<div class=\"api_sample\"> \n";
	echo "			<pre> \n";
	echo "Array \n";
	echo "( \n";
	echo "    [status] => 1 \n";
	echo "    [message] =>  \n";
	echo "    [data] => 189689.67282100 \n";
	echo ") \n";
	echo "			</pre> \n";
	echo "		</div> \n\n";
	
	echo "	</div> \n\n";
	
	
	spectra_site_foot ();

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>