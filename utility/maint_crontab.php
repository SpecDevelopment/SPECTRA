<?php 
/******************************************************************************
	This is the script that will maintain the SPECTRA Block 
	Explorer database.  You should configure this script to run
	at regular intervals using crontab or another scheduler.
******************************************************************************/

//	Enable the spectra functionality
	require_once ("/var/www/html/spectra/lib/spectra_config.php");

//	Begin logging
	spectra_log_write (0, "Beginning maintenance.");
	
/******************************************************************************
	Maintenance Check
******************************************************************************/

//	Check for a running copy of the maintenance script
	if (system_flag_get("maintenance") == "1")
	{
	//	If the script is already running, abort
		spectra_log_write (1, "Maintenance mode detected, exiting.");
	}
	
	else
	{
	//	If the script is not running, set the maintenance flag
		system_flag_set ("maintenance", "1");
	}

/******************************************************************************
	Sync Check And Orphan Cleanup
******************************************************************************/

//	Get the current block height from the explorer
	$height_exp = spectra_block_height ();

//	Retrieve the explorer data for this block
	$data_exp = mysqli_getrow ($GLOBALS["tables"]["block"], "`height` = '".$height_exp."'");

//	Get the matching block hash from the node
	$hash_node = getblockhash ((int) $height_exp);

//	If there was not a valid response from the node, the script will exit
	if (is_array ($hash_node) || $hash_node == "")
	{
	//	Disable the maintenance flag
		system_flag_set ("maintenance", "1");

	//	Log the communication error and terminate
		spectra_log_write (1, "Invalid or empty response from node, exiting.");
	}
	
//	If the hashes don't match, the block chain has had a fork/orphan
//	since the last time the script ran.
	if ($data_exp["data"]["hash"] != $hash_node)
	{
	//	A flag is set to rebuild balances at the end of the script
		system_flag_set ("balance_rebuild", 1);
	
	//	The script will scan back along the chain until it gets a match
		while ($data_exp["data"]["hash"] != $hash_node)
		{
		//	If the block hashes do not match, we have a fork.
			spectra_log_write (0, "Block Data Mismatch at block".$height_exp);

		//	The invalid block and it's data are removed from the database
			spectra_orphan_wipe ($data_exp["data"]["hash"]);

		//	The explorer block height is decremented
			$height_exp = $height_exp - 1;

		//	The next block is retrieved from the explorer
			$data_exp = mysqli_getrow ($GLOBALS["tables"]["block"], "`height` = '".$height_exp."'");

		//	The next hash is retrieved from the node	
			$hash_node = getblockhash ($height_exp);

		//	The while loop will perform the comparison against the new values
		}

	//	The block height of the match found is written to the log	
		spectra_log_write (0, "Found sync at block ".$height_exp);
	}

/******************************************************************************
	Load New Blocks
******************************************************************************/

//	Block heights are reloaded in case of a resync
	$start = spectra_block_height ();
	$top = getblockcount ();

//	If there is not a new block, end the checks
	if ($start >= $top)
	{
	//	Make a note to the maintenance log
		spectra_log_write (0, "No new block, loading complete.");
	}
	
	else
	{
	//	Log the new block height
		spectra_log_write (0, "Explorer Block: ".$start." - Node Block: ".$top);
		
	//	Get the data for the prior block from the node
		$hash_back = getblockhash ((int) $start);
		$data_node = getblock ($hash_back);

	//	Update the prior block record
		$result = mysqli_setfield ($GLOBALS["tables"]["block"], "nextblockhash", $data_node["nextblockhash"], "`hash` = '".$data_node["hash"]."'");

	//	Initialize a block counter for logging
		$count_loaded = 0;
		
	//	Load any new blocks available from the node
		while ($start < $top)
		{
		//	Increment the explorer height to indicate the next desired block
			$start++;
			
			spectra_log_write (0, "Loading Block: ".$start);
		
		//	The next block is loaded
			spectra_block_load ($start);
		
		//	Increment the block count
			$count_loaded ++;
		}

	//	Log the number of blocks loaded
		spectra_log_write (0, "Loaded ".$count_loaded." Blocks.");
	}
	
/******************************************************************************
	Rebuild Balances If Indicated
******************************************************************************/

//	Check for the balances flag and rebuild if requested
	if (system_flag_get ("balance_rebuild") > 0)
	{
	//	There is another script that will handle this
		include ("maint_rebalance.php");
	}

/******************************************************************************
	Reset Maintenance Flags
******************************************************************************/

//	Reset the maintenance mode flag
	system_flag_set ("maintenance", 0);
	
//	Log the time of completion
	spectra_log_write (0, "Script maint_crontab.php complete.");

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>