<?php 
//	Enable the spectra functionality
	require_once ("../lib/spectra_config.php");
	
//	check for a block number in the command line
	if (!isset ($argv[1]) or $argv[1] == "")
	{
		echo "You must provide a block height.\n\n";
		exit;
	}
	
//	Verify that a block exists at this height
	$hash = getblockhash ((int) $argv[1]);
	$block = getblock ($hash);
	
	if (!isset ($block["hash"]))
	{
		spectra_log_write (1, "Unable to retrieve block at height ".$argv[1]);
	}
	
//	Process the block with the block parser
	spectra_block_load ((int) $argv[1]);
	
//	Some data for the log
	spectra_log_write (0, "Inserted block at height ".$argv[1]);

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>