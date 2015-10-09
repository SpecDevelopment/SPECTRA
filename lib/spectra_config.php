<?php
//	This script is used to configure the block explorer for your
//	hosting environment.  Please read the instructions located in
//	the included "install.txt" file for an explanation of these values.

/******************************************************************************
	Currency Description
******************************************************************************/

	$GLOBALS["currency"]["name"] = "SPEC Coin";
	$GLOBALS["currency"]["code"] = "SPEC";
	
/******************************************************************************
	Local Configuration
******************************************************************************/

//	Common URLs
	$GLOBALS["url"]["home"] = "http://URL HERE/coin_name/";
	$GLOBALS["url"]["theme"] = $GLOBALS["url"]["home"]."theme/themeless/";
	
//	Common Paths
	$GLOBALS["path"]["root"] = "/var/www/html/spectra/";
	$GLOBALS["path"]["lib"] = $GLOBALS["path"]["root"]."lib/";
	$GLOBALS["path"]["theme"] = $GLOBALS["path"]["root"]."theme/themeless/";
	$GLOBALS["path"]["logfile"] = $GLOBALS["path"]["root"]."/utility/spectra_log.txt";

//	Debug Output set to 1 will cause all messages to be shown at the 
//	console as well as in the log file
	$GLOBALS["debug_output"] = 1;

//	API Request Rate Limit
	$GLOBALS["api_rate_limit"] = 0;

/******************************************************************************
	Node Connection
******************************************************************************/

//	Node Connection Info
	$GLOBALS["node"]["host"] = "localhost";
	$GLOBALS["node"]["port"] = "4320";
	$GLOBALS["node"]["user"] = "SPECD USERNAME HERE";
	$GLOBALS["node"]["pass"] = "SPECD PASSWORD HERE";
	
/******************************************************************************
	Database Customization 
******************************************************************************/

//	Database Connection
	$GLOBALS["db"]["host"] = "localhost";
	$GLOBALS["db"]["name"] = "spectra_1";
	$GLOBALS["db"]["user"] = "MYSQL USERNAME HERE";
	$GLOBALS["db"]["pass"] = "MYSQL PASSWORD HERE";

//	Database Description
	$GLOBALS["tables"]["block"] = "spectra_block";
	$GLOBALS["tables"]["tx"] = "spectra_tx";
	$GLOBALS["tables"]["vin"] = "spectra_vin";
	$GLOBALS["tables"]["vout"] = "spectra_vout";
	$GLOBALS["tables"]["ledger"] = "spectra_ledger";
	$GLOBALS["tables"]["flag"] = "spectra_flag";
	

/******************************************************************************
	Market Configuration
******************************************************************************/

//	Disable to avoid massive loading lag
	$GLOBALS["markets"][0]["index"] = 0;
	$GLOBALS["markets"][0]["exch_id"] = "cryptsy";
	$GLOBALS["markets"][0]["exch_display"] = "Cryptsy";
	$GLOBALS["markets"][0]["mkt_id"] = "492";
	$GLOBALS["markets"][0]["mkt_display"] = "SPEC / BTC";

	$GLOBALS["markets"][1]["index"] = 1;
	$GLOBALS["markets"][1]["exch_id"] = "yobit";
	$GLOBALS["markets"][1]["exch_display"] = "YoBit";
	$GLOBALS["markets"][1]["mkt_id"] = "spec_btc";
	$GLOBALS["markets"][1]["mkt_display"] = "SPEC / BTC";
	
	$GLOBALS["markets"][3]["index"] = 3;
	$GLOBALS["markets"][3]["exch_id"] = "bleutrade";
	$GLOBALS["markets"][3]["exch_display"] = "Bleutrade";
	$GLOBALS["markets"][3]["mkt_id"] = "SPEC_BTC";
	$GLOBALS["markets"][3]["mkt_display"] = "SPEC / BTC";
	
	$GLOBALS["markets"][6]["index"] = 6;
	$GLOBALS["markets"][6]["exch_id"] = "bleutrade";
	$GLOBALS["markets"][6]["exch_display"] = "Bleutrade";
	$GLOBALS["markets"][6]["mkt_id"] = "SPEC_USD";
	$GLOBALS["markets"][6]["mkt_display"] = "SPEC / USD";
	
	$GLOBALS["markets"][7]["index"] = 7;
	$GLOBALS["markets"][7]["exch_id"] = "bittrex";
	$GLOBALS["markets"][7]["exch_display"] = "Bittrex";
	$GLOBALS["markets"][7]["mkt_id"] = "BTC-LTC";
	$GLOBALS["markets"][7]["mkt_display"] = "LTC / BTC";
	
	$GLOBALS["markets"][8]["index"] = 8;
	$GLOBALS["markets"][8]["exch_id"] = "poloniex";
	$GLOBALS["markets"][8]["exch_display"] = "Poloniex";
	$GLOBALS["markets"][8]["mkt_id"] = "USDT_BTC";
	$GLOBALS["markets"][8]["mkt_display"] = "BTC / USD";
	
	$GLOBALS["markets"][9]["index"] = 9;
	$GLOBALS["markets"][9]["exch_id"] = "cryptopia";
	$GLOBALS["markets"][9]["exch_display"] = "Cryptopia";
	$GLOBALS["markets"][9]["mkt_id"] = "2370";
	$GLOBALS["markets"][9]["mkt_display"] = "SBC / BTC";
	
	
/******************************************************************************
	Required Modules and Wrappers
******************************************************************************/

	require_once ($GLOBALS["path"]["lib"]."spectra_lib_layout.php");
	require_once ($GLOBALS["path"]["lib"]."spectra_lib_parser.php");
	require_once ($GLOBALS["path"]["lib"]."spectra_lib_utility.php");

	require_once ($GLOBALS["path"]["lib"]."spectra_wrap_exchange.php");
	require_once ($GLOBALS["path"]["lib"]."spectra_wrap_mysqli.php");
	require_once ($GLOBALS["path"]["lib"]."spectra_wrap_node.php");

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>
