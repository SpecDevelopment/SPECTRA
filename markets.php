<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

//	This page can provide a market panel to itself in ajax fashion
	if (isset ($_REQUEST["mkt_index"]) && $_REQUEST["mkt_index"] != "")
	{
	//	Render the market panel for the requested exchange
		spectra_market_panel ($_REQUEST["mkt_index"]);
		
	//	Exit before any page rendering
		exit;
	}
	
//	If there were no special requests the market data page is rendered
	spectra_site_head ($GLOBALS["currency"]["code"]." Market Browser");
	
	echo "<h1>Market Tickers</h1> \n\n";
	
	foreach ($GLOBALS["markets"] as $market)
	{
		spectra_ticker_panel ($market);
	}
	
//	Force a little whitespace
	echo "	<br><br> \n\n";

//	Create the exchange selection menu
	echo "<h1>Detailed Market Information</h1> \n\n";
	
	echo "	<div id=\"spectra_market_menu\"> \n\n";

	foreach ($GLOBALS["markets"] as $market)
	{
		echo "		<a class=\"spectra_market_button\" href=\"javascript:panel_fetch('spectra_market_panel', '".$GLOBALS["url"]["home"]."markets.php?mkt_index=".$market["index"]."')\" title=\"".$market["exch_display"]." ".$market["mkt_display"]." Market Detail\"> \n";
		echo "			".$market["exch_display"]." \n";
		echo "			<hr> \n";
		echo "			".$market["mkt_display"]."\n";
		echo "		</a> \n";
	}
	echo "	</div> \n\n";

//	This panel will be loaded with information when a market is selected
	echo "	<div id=\"spectra_market_panel\" name=\"spectra_market_panel\"> \n";
	echo "		<p> Please Select An Exchange To Load Details </p> \n";
	echo "	</div> \n\n";
	
	spectra_site_foot ();

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>