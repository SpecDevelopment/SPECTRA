<?php 
//	Enable the spectra functionality
	require_once ("../lib/spectra_config.php");
	
//	check for a block number in the command line
	if (!isset ($argv[1]) or $argv[1] == "")
	{
		echo "You must provide a block height.\n\n";
		exit;
	}
	
//	Retrieve the specified block hash
	$hash = getblockhash ((int) $argv[1]);
	
//	Retrieve the block and match it to the requested height 
	$block = getblock ($hash);
	
	if (!isset ($block["hash"]))
	{
		echo "Unable to retrieve block ".$hash."\n";
	}
	
	if ($block["height"] != $argv[1])
	{
		echo "Block Data Mismatch for block at height ".$argv[1]."\n";
	}
	
//	Delete all data related to this block
	spectra_orphan_wipe ($hash);
	
	echo "Deleted ".$hash."\n\n";
	
//	Some data for the log
//	spectra_log_write (1, $forksize." Invalid Blocks Inserted");

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>