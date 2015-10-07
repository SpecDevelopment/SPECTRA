<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

//	This code creates the 'raw block data' view used in the popup
	if (isset ($_GET["raw"]) && $_GET["raw"] == 1)
	{
		if (!isset ($_REQUEST["hash"]) || $_REQUEST["hash"] == "")
		{
			echo "<p> hash parameter is required for raw data request</p>\n\n";
		}
		
		$rawblock = getblock ($_REQUEST["hash"]);
		
		echo "<pre>\n".print_r ($rawblock, 1)."\n</pre>\n";
		
		exit;
	}

//	Verify that a block descriptor was provided and attempt retrieval	
	if (isset ($_REQUEST["height"]) && $_REQUEST["height"] != "")
	{
		$block = mysqli_getrow ($GLOBALS["tables"]["block"], "`height` = '".$_REQUEST["height"]."'");
	}
	
	elseif (isset ($_REQUEST["hash"]) && $_REQUEST["hash"] != "")
	{
		$block = mysqli_getrow ($GLOBALS["tables"]["block"], "`hash` = '".$_REQUEST["hash"]."'");
	}
	
	else
	{
		$message  = "<p> \n";
		$message .= "	You must provide either a block hash or a block height to use this page. \n";
		$message .= "</p> \n\n";
	
		spectra_page_error ("Missing Block Descriptor", $message);
	}
	
//	Verify that block data was retrieved
	if ($block["success"] < 1 || $block["data"] == "")
	{
		$message  = "<p> \n";
		$message .= "	Unable To Retrieve Specified Block \n";
		$message .= "</p> \n\n";

		$message .= "<p> \n";
		$message .= "	Please verify your block hash/height and try again. \n";
		$message .= "</p> \n\n";

		spectra_page_error ("Invalid Block Descriptor", $message);
	}

//	If there were no errors render the block detail view
	spectra_site_head ($GLOBALS["currency"]["code"]." Block Detail");
	
	spectra_block_detail ($block["data"]);
	
	spectra_site_foot ();

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>