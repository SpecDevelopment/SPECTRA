<?php 
/******************************************************************************
	This script rebuilds the ledger in case of an orphaned block 
	or other rollback. This script is normally only invoked via
	maint_crontab.php.
******************************************************************************/

//	Enable the SPECTRA functionality
	require_once ("../lib/spectra_config.php");

//	Check to make sure that the rebalance flag is set
	if (system_flag_get ("balance_rebuild") < 1)
	{
		system_flag_set ("balance_rebuild", 1);
	}

//	Some data for the log
	spectra_log_write (0, "Beginning Balance Rebuild");
	
//	Wipe all input counts from the account ledger
	$wiped = mysqli_setfield ($GLOBALS["tables"]["ledger"], "tx_in", 0, "1=1");

//	Wipe all input values from the account ledger
	$wiped = mysqli_setfield ($GLOBALS["tables"]["ledger"], "received", 0, "1=1");

//	Wipe all output counts from the account ledger
	$wiped = mysqli_setfield ($GLOBALS["tables"]["ledger"], "tx_out", 0, "1=1");

//	Wipe all output values from the account ledger
	$wiped = mysqli_setfield ($GLOBALS["tables"]["ledger"], "spent", 0, "1=1");

//	Wipe all balance values from the account ledger
	$wiped = mysqli_setfield ($GLOBALS["tables"]["ledger"], "balance", 0, "1=1");

//	Some data for the log
	spectra_log_write (0, "Existing Balances Were Deleted");

//	For this version we will be iterating the list of addresses in the chain
	$response = $GLOBALS["db"]["obj"]->query ("SELECT count(*) as `count` FROM `".$GLOBALS["tables"]["ledger"]."`");
	$record = $response->fetch_assoc ();
	$count_addr = $record["count"];

//	Some data for the log
	spectra_log_write (0, "Updating Account Ledger (".$count_addr." Addresses)");

//	Addresses are handled in chunks to avoid memory issues
	$count_loop = 0;
	$count_req = ceil ($count_addr / 1000);

	while ($count_loop < $count_req)
	{
	//	Output for the log and to keep the console active
		spectra_log_write (0, "Group ".($count_loop + 1)." Of ".$count_req);
		
	//	Retrieve the addresses
		$cycle = mysqli_getset ($GLOBALS["tables"]["ledger"], "1=1 ORDER BY `sys_index` ASC LIMIT 1000 OFFSET ".($count_loop * 1000));

		foreach ($cycle["data"] as $entry)
		{
		//	Fetch the vins for the address
			$list_vin = mysqli_getset ($GLOBALS["tables"]["vin"], "`src_address` LIKE '%".$entry["address"]."%'");
			
		//	Sum the vins
			$sum_vin = 0;
			$num_vin = 0;
			
			if ($list_vin["data"] != "")
			{
				foreach ($list_vin["data"] as $vin)
				{
					$sum_vin = bcadd ($sum_vin, $vin["src_value"], 8);
					$num_vin ++;
				}
			}
			
		//	Fetch the vouts for the address
			$list_vout = mysqli_getset ($GLOBALS["tables"]["vout"], "`addresses` LIKE '%".$entry["address"]."%'");
			
		//	Sum the vouts
			$sum_vout = 0;
			$num_vout = 0;
			
			if ($list_vout["data"] != "")
			{
				foreach ($list_vout["data"] as $vout)
				{
					$sum_vout = bcadd ($sum_vout, $vout["value"], 8);
					$num_vout++;
				}
			}
			
		//	Calculate the balance
			$balance = bcsub ($sum_vout, $sum_vin, 8);
			
		//	Update the ledger record
			$result = $GLOBALS["db"]["obj"]->query ("UPDATE `".$GLOBALS["tables"]["ledger"]."` SET `tx_in` = '".$num_vout."', `received` = '".$sum_vout."', `tx_out` = '".$num_vin."', `spent` = '".$sum_vin."', `balance` = '".$balance."' WHERE `address` = '".$entry["address"]."'");
		}
	
	//	Increment the set counter
		$count_loop++;
	}
	
//	Reset the balance rebuild flag
	system_flag_set ("balance_rebuild", 0);
	
//	Some data for the log
	spectra_log_write (0, "Rebalance Complete");

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>