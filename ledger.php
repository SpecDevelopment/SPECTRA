<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

	spectra_site_head ($GLOBALS["currency"]["code"]." Account Ledger");
	
//	If the system is rebalancing the ledger a placeholder is displayed
	if (system_flag_get ("balance_rebuild"))
	{
		echo "	<div class=\"panel_wide\"> \n\n";
		
		echo "		<h2> Rebuilding Balance List </h2> \n\n";
		
		echo "		<p> \n";
		echo "			The block explorer is currently re-calculating balances. \n";
		echo "		</p> \n\n";
		
		echo "		<p> \n";
		echo "			This is normal after a block has been orphaned, and \n";
		echo "			may be caused by other maintenance activities. \n";
		echo "		</p> \n\n";
		
		echo "		<p> \n";
		echo "			The process takes a few minutes, please check back soon. \n";
		echo "		</p> \n\n";
		
		echo "	</div> \n\n";
	
		spectra_site_foot ();
	}

//	Distribution statistics are displayed
	spectra_ledger_statistics ();
	
//	The full ledger browser is displayed
	spectra_ledger_browser (20);
	
	spectra_site_foot ();

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>