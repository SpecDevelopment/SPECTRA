<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

	spectra_site_head ($GLOBALS["currency"]["code"]." Block Explorer");
	
	spectra_front_stats ();
	
	spectra_block_browser (20);
	
	spectra_site_foot ();

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>