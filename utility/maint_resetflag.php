<?php 
//	Enable the spectra functionality
	require_once ("../lib/spectra_config.php");
	
//	Reset the maintenance flags
	system_flag_set ("balance_rebuild", 0);
	system_flag_set ("maintenance", 0);
	
	
//	Some data for the log
	spectra_log_write (0, "System Maintenance flags forced to 0");

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>