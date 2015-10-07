<?php 
/******************************************************************************
	This script assumes there are no blocks loaded in the explorer
	database and should only be used with a fresh installation.
	
	For ongoing maintenance you should configure your scheduler to 
	run the script named "maint_crontab.php" instead.
******************************************************************************/

//	Enable the spectra functionality
	require_once ("../lib/spectra_config.php");
	
//	Determine the current block height from the node
	$targ = getblockcount ();
	
	if (is_array ($targ))
	{
		spectra_log_write (1, "Communication Error: ".print_r ($targ["error"], 1));
	}
	
//	Most wallets do not display the genesis (0) block.  The script
//	will begin loading with the first mined block at block 1.
	$load = 1;
	
//	Status for the log	
	spectra_log_write (0, "Loading ".$GLOBALS["currency"]["name"]." Block Chain");
	spectra_log_write (0, "Node Height: ".$targ);
	
//	The blocks are loaded from the node.
	while ($load <= $targ)
	{
	//	Each block is written to the log to ensure that any errors
	//	generated can be tracked to the specific block
		spectra_log_write (0, "Loading Block: ".$load);
		
	//	The current block is parsed into the database
		spectra_block_load ($load);
	
	//	The block counter is updated.
		$load++;
	}
	
//	Completed status for the log
	spectra_log_write (0, "No New Block (".$load.")");

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>