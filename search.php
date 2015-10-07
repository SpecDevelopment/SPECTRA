<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

	spectra_site_head ("Search Results");
	
	echo "	<h1> ".$GLOBALS["currency"]["name"]." Blockchain Search </h1> \n\n";

	echo "	<div class=\"panel_wide\"> \n\n";
	
//	Search for matching addresses
	spectra_search_address ($_POST["searchtext"]);
	
//	Search for matcching transaction IDs
	spectra_search_tx ($_POST["searchtext"]);

//	Search for matching block hashes	
	spectra_search_block ($_POST["searchtext"]);
	
	echo "	</div> \n\n";
	
	spectra_site_foot ();

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>