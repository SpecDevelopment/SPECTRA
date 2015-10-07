<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");
	
//	If there is no live node the page is a spew of errors
	if (!spectra_node_isalive ())
	{
		$message = "<p>";
		$message .= "	Unable to connect to the specified node.";
		$message .= "</p>";
		
		spectra_page_error ("No Network", $message);
	}

	spectra_site_head ($GLOBALS["currency"]["code"]." Network Status");
	
//	Network status and description
	spectra_network_statistics ();

//	Connection list for the specified node	
	spectra_network_nodelist ();

	spectra_site_foot ();

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>

