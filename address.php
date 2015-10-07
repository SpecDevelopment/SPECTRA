<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

//	Error handling for missing address
	if (!isset ($_REQUEST["address"]) || $_REQUEST["address"] == "")
	{
		$message  = "<p> \n";
		$message .= "	You must provde an address to use this page. \n";
		$message .= "</p> \n\n";

		spectra_page_error ("No Address Provided", $message);
	}
	
//	Attempt to fetch the specified address	
	$address = mysqli_getrow ($GLOBALS["tables"]["ledger"], "`address` = '".$_REQUEST["address"]."'");

//	Error handling for invalid or unrecognized address	
	if ($address["success"] < 1 || $address["data"] == "")
	{
		$message  = "<p> \n";
		$message .= "	Invalid or Unrecognized Address \n";
		$message .= "</p> \n\n";

		$message .= "<p> \n";
		$message .= "	Please verify your address and try again. \n";
		$message .= "</p> \n\n";

		spectra_page_error ("Invalid Address", $message);
	}

//	If there were no error the address details are rendered
	spectra_site_head ($GLOBALS["currency"]["code"]." Address Detail");
	
	spectra_address_detail ($address["data"]);
	
	spectra_site_foot ();
	
/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>