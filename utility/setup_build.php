<?php 
/******************************************************************************
	This script creates the tables used by the block explorer to
	sort and report on the block chain data. This script is usually
	run only once during the initial block explorer setup.
******************************************************************************/

//	Enable the spectra functionality
	require_once ("../lib/spectra_config.php");

//	Status for the console
	spectra_log_write (0, "Beginning SPECTRA Table Creation");
	
//	Make sure the node is alive before proceeding, it is required 
//	to determine the block format and successfully complete this script
	if (!spectra_node_isalive ())
	{
		spectra_log_write (1, "Unable to connect to ".$GLOBALS["currency"]["name"]." node.");
	}
	
	
//	Build the queries to create the block data table
	$block_create = "CREATE TABLE IF NOT EXISTS `".$GLOBALS["tables"]["block"]."` (";
	$block_create .= "`sys_index` bigint(20) NOT NULL,";
	$block_create .= "`hash` tinytext NOT NULL,";
	$block_create .= "`proofhash` tinytext NOT NULL,";
	$block_create .= "`size` int(11) NOT NULL,";
	$block_create .= "`height` bigint(20) NOT NULL,";
	$block_create .= "`version` int(11) NOT NULL,";
	$block_create .= "`mint` decimal(32,8) NOT NULL,";
	$block_create .= "`flags` tinytext NOT NULL,";
	$block_create .= "`entropybit` tinytext NOT NULL,";
	$block_create .= "`merkleroot` tinytext NOT NULL,";
	$block_create .= "`tx` text NOT NULL,";
	$block_create .= "`time` bigint(20) NOT NULL,";
	$block_create .= "`nonce` bigint(20) NOT NULL,";
	$block_create .= "`bits` tinytext NOT NULL,";
	$block_create .= "`difficulty` decimal(32,8) NOT NULL,";
	$block_create .= "`modifier` tinytext NOT NULL,";
	$block_create .= "`modifierchecksum` tinytext NOT NULL,";
	$block_create .= "`signature` tinytext NOT NULL,";
	$block_create .= "`previousblockhash` tinytext NOT NULL,";
	$block_create .= "`nextblockhash` tinytext NOT NULL,";
	$block_create .= "`val_in` decimal(32,8) NOT NULL,";
	$block_create .= "`val_out` decimal(32,8) NOT NULL,";
	$block_create .= "`val_fee` decimal(32,8) NOT NULL";
	$block_create .= ")";

	$block_index = "ALTER TABLE `".$GLOBALS["tables"]["block"]."` ADD PRIMARY KEY (`sys_index`)";
	$block_increment = "ALTER TABLE `".$GLOBALS["tables"]["block"]."` MODIFY `sys_index` bigint(20) NOT NULL AUTO_INCREMENT";

//	Create the block data table
	spectra_log_sql ("Create Block Table", $block_create);
	spectra_log_sql ("Define Block Index", $block_index);
	spectra_log_sql ("Block Auto Increment", $block_increment);
	
//	Build the queries to create the TX data table
	$tx_create = "CREATE TABLE IF NOT EXISTS `".$GLOBALS["tables"]["tx"]."` (";
	$tx_create .= "`sys_index` bigint(20) NOT NULL,";
	$tx_create .= "`in_block` tinytext NOT NULL,";
	$tx_create .= "`txid` tinytext NOT NULL,";
	$tx_create .= "`version` int(11) NOT NULL,";
	$tx_create .= "`time` bigint(20) NOT NULL,";
	$tx_create .= "`locktime` bigint(20) NOT NULL,";
	$tx_create .= "`blocktime` bigint(20) NOT NULL,";
	$tx_create .= "`tx-comment` mediumtext NOT NULL,";
	$tx_create .= "`val_in` decimal(32,8) NOT NULL,";
	$tx_create .= "`val_out` decimal(32,8) NOT NULL,";
	$tx_create .= "`val_fee` decimal(32,8) NOT NULL";
	$tx_create .= ")";

	$tx_index = "ALTER TABLE `".$GLOBALS["tables"]["tx"]."` ADD PRIMARY KEY (`sys_index`)";
	$tx_increment = "ALTER TABLE `".$GLOBALS["tables"]["tx"]."` MODIFY `sys_index` bigint(20) NOT NULL AUTO_INCREMENT";

//	Create the block data table
	spectra_log_sql ("Create TX Table", $tx_create);
	spectra_log_sql ("Define TX Index", $tx_index);
	spectra_log_sql ("TX Auto Increment", $tx_increment);

//	Build the queries to create the VIN data table
	$vin_create = "CREATE TABLE IF NOT EXISTS `".$GLOBALS["tables"]["vin"]."` (";
	$vin_create .= "`sys_index` bigint(20) NOT NULL,";
	$vin_create .= "`src_block` tinytext NOT NULL,";
	$vin_create .= "`src_tx` tinytext NOT NULL,";
	$vin_create .= "`src_vout` int(11) NOT NULL,";
	$vin_create .= "`src_address` tinytext NOT NULL,";
	$vin_create .= "`src_value` decimal(32,8) NOT NULL,";
	$vin_create .= "`in_block` tinytext NOT NULL,";
	$vin_create .= "`in_tx` tinytext NOT NULL,";
	$vin_create .= "`time` bigint(20) NOT NULL,";
	$vin_create .= "`coinbase` text NOT NULL,";
	$vin_create .= "`sequence` tinytext NOT NULL";
	$vin_create .= ")";

	$vin_index = "ALTER TABLE `".$GLOBALS["tables"]["vin"]."` ADD PRIMARY KEY (`sys_index`)";
	$vin_increment = "ALTER TABLE `".$GLOBALS["tables"]["vin"]."` MODIFY `sys_index` bigint(20) NOT NULL AUTO_INCREMENT";

//	Create the block data table
	spectra_log_sql ("Create Vin Table", $vin_create);
	spectra_log_sql ("Define Vin Index", $vin_index);
	spectra_log_sql ("Vin Auto Increment", $vin_increment);

//	Build the queries to create the VOUT data table
	$vout_create = "CREATE TABLE IF NOT EXISTS `".$GLOBALS["tables"]["vout"]."` (";
	$vout_create .= "`sys_index` bigint(20) NOT NULL,";
	$vout_create .= "`in_block` tinytext NOT NULL,";
	$vout_create .= "`in_tx` tinytext NOT NULL,";
	$vout_create .= "`time` bigint(20) NOT NULL,";
	$vout_create .= "`value` decimal(32,8) NOT NULL,";
	$vout_create .= "`n` int(11) NOT NULL,";
	$vout_create .= "`reqsigs` int(11) NOT NULL,";
	$vout_create .= "`type` tinytext NOT NULL,";
	$vout_create .= "`addresses` mediumtext NOT NULL";
	$vout_create .= ")";

	$vout_index = "ALTER TABLE `".$GLOBALS["tables"]["vout"]."` ADD PRIMARY KEY (`sys_index`)";
	$vout_increment = "ALTER TABLE `".$GLOBALS["tables"]["vout"]."` MODIFY `sys_index` bigint(20) NOT NULL AUTO_INCREMENT";

//	Create the block data table
	spectra_log_sql ("Create Vout Table", $vout_create);
	spectra_log_sql ("Define Vout Index", $vout_index);
	spectra_log_sql ("Vout Auto Increment", $vout_increment);

//	Build the queries to create the Ledger table
	$ledger_create = "CREATE TABLE IF NOT EXISTS `".$GLOBALS["tables"]["ledger"]."` (";
	$ledger_create .= "`sys_index` bigint(20) NOT NULL,";
	$ledger_create .= "`address` tinytext NOT NULL,";
	$ledger_create .= "`firstblock` tinytext NOT NULL,";
	$ledger_create .= "`tx_in` int(11) NOT NULL,";
	$ledger_create .= "`received` decimal(32,8) NOT NULL,";
	$ledger_create .= "`tx_out` int(11) NOT NULL,";
	$ledger_create .= "`spent` decimal(32,8) NOT NULL,";
	$ledger_create .= "`balance` decimal(32,8) NOT NULL,";
	$ledger_create .= "`owner` tinytext NOT NULL";
	$ledger_create .= ")";

	$ledger_index = "ALTER TABLE `".$GLOBALS["tables"]["ledger"]."` ADD PRIMARY KEY (`sys_index`)";
	$ledger_increment = "ALTER TABLE `".$GLOBALS["tables"]["ledger"]."` MODIFY `sys_index` bigint(20) NOT NULL AUTO_INCREMENT";

//	Create the block data table
	spectra_log_sql ("Create Ledger Table", $ledger_create);
	spectra_log_sql ("Define Ledger Index", $ledger_index);
	spectra_log_sql ("Ledger Auto Increment", $ledger_increment);

//	Build the queries to create the system flag table
	$flag_create = "CREATE TABLE IF NOT EXISTS `".$GLOBALS["tables"]["flag"]."` (";
	$flag_create .= "`sys_index` bigint(20) NOT NULL,";
	$flag_create .= "`name` tinytext NOT NULL,";
	$flag_create .= "`value` int(11) NOT NULL";
	$flag_create .= ")";

	$flag_index = "ALTER TABLE `".$GLOBALS["tables"]["flag"]."` ADD PRIMARY KEY (`sys_index`)";
	$flag_increment = "ALTER TABLE `".$GLOBALS["tables"]["flag"]."` MODIFY `sys_index` bigint(20) NOT NULL AUTO_INCREMENT";

//	Create the block data table
	spectra_log_sql ("Create Flag Table", $flag_create);
	spectra_log_sql ("Define Flag Index", $flag_index);
	spectra_log_sql ("Flag Auto Increment", $flag_increment);
	
//	This function samples a block from the node and sets a formatting
//	flag in the database. 
	spectra_block_setformat ();
	
//	Status for the console
	echo "\n\tSPECTRA Table Creation Is Complete\n";
	exit;

//	This function executes each query and logs the results.
//	It will also halt the script in case of any error.
	function spectra_log_sql ($message, $query)
	{
		$result = $GLOBALS["db"]["obj"]->query ($query);
		
		if ($GLOBALS["db"]["obj"]->error)
		{
			$buildlog = "Error: ".$message." - ".$GLOBALS["db"]["obj"]->error;
			
			spectra_log_write (1, $buildlog);
		}
		
		else
		{
			$buildlog = "Success: ".$message;
		
			spectra_log_write (0, $buildlog);
		}

	}
	
/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>