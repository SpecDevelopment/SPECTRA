<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

//	This code creates the 'raw tx data' view used in the popup
	if (isset ($_GET["raw"]) && $_GET["raw"] == 1)
	{
	//	Error handling for missing txid
		if (!isset ($_REQUEST["tx"]) || $_REQUEST["tx"] == "")
		{
			echo "<p> tx parameter (txid) is required </p>\n";
		}
		
	//	Attempt to retrieve the tx from the node
		$rawtx = getrawtransaction ($_REQUEST["tx"]);
		
	//	The response data is formatted and returned to the browser
		echo "<pre>\n".print_r ($rawtx, 1)."\n</pre>\n";
		
	//	Exit without rendering the full transaction page
		exit;
	}
	
//	Error handling for a missing txid
	if (!isset ($_REQUEST["tx"]) || $_REQUEST["tx"] == "")
	{
		$message  = "<p> \n";
		$message .= "	You must provide a transaction ID to use this page. \n";
		$message .= "</p> \n\n";

		spectra_page_error ("Missing Transaction ID", $message);
	}
	
//	Attempt to retrieve the specified transaction
	$tx = mysqli_getrow ($GLOBALS["tables"]["tx"], "`txid` = '".$_REQUEST["tx"]."'");
	
//	Error handling for unrecognized transaction
	if ($tx["success"] < 1 || $tx["data"] == "")
	{
		$message  = "<p> \n";
		$message .= "	Invalid or Unrecognized TX ID \n";
		$message .= "</p> \n\n";

		$message .= "<p> \n";
		$message .= "	Please verify your TX ID and try again. \n";
		$message .= "</p> \n\n";

		spectra_page_error ("Invalid Transaction ID", $message);
	}
	
//	The rest of the code generates the normal tx detail page for the site
	spectra_site_head ($GLOBALS["currency"]["code"]." Transaction Detail");
	
	spectra_tx_detail ($tx["data"]);
	
	spectra_site_foot ();
	
/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>