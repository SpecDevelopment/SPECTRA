<?php 
//	This script provides general back-end functions required by the 
//	SPECTRA Block Explorer.

/******************************************************************************
	Maintenance and Logging
******************************************************************************/

//	Format log and console output
	function spectra_log_write ($error_code, $message)
	{
	//	Add additional dtails to the message
		$log["time"] = date ("m/d/Y H:i:s", time ());
		$log["code"] = $error_code;
		$log["message"] = $message;
			
	//	Encode the message in JSON for easier recovery
		$log_entry = "\r\n".json_encode ($log);
		
	//	Write the log information ot the log file
		file_put_contents ($GLOBALS["path"]["logfile"], $log_entry, FILE_APPEND);
		
	//	If the debug flag is set to 1, output log data to the console
		if ($GLOBALS["debug_output"] > 0)
		{
			echo "\t".$log["time"]." - ".$log["code"].": ".$log["message"]."\n";
		}
		
	//	If the error code was set the script will exit.
		if ($error_code > 0)
		{
			exit;
		}
	}

//	Set a numeric flag value in a database table.
	function system_flag_set ($flag_name, $flag_value)
	{
		$flag_exists = mysqli_isrow ($GLOBALS["tables"]["flag"], "`name` = '".$flag_name."'");

		if ($flag_exists["data"])
		{
			$inserted = mysqli_setfield ($GLOBALS["tables"]["flag"], "value", $flag_value, "`name` = '".$flag_name."'");
		}
		
		else
		{
			$flag["name"] = $flag_name;
			$flag["value"] = $flag_value;
			
			$inserted = mysqli_setrow ($GLOBALS["tables"]["flag"], $flag);
		}
		
		return $inserted["success"];
	}

//	Retrieve a numreic flag value from a database table
	function system_flag_get ($flag_name)
	{
		$flag_check = mysqli_isrow ($GLOBALS["tables"]["flag"], "`name` = '".$flag_name."'");

		if ($flag_check["data"] = TRUE)
		{
			$flag_value = mysqli_getfield ($GLOBALS["tables"]["flag"], "value", "`name` = '".$flag_name."'");
			return $flag_value["data"];
		}
		
		else
		{
			$flag["name"] = $flag_name;
			$flag["value"] = 0;
			
			mysqli_setrow ("cxwrap_flag_account", $flag);
			
			return 0;
		}
		
	}

//	This function sets a flag to override the "verbose" switch for block 
//	retrieval if the node in use already provides verbose output.
	function spectra_block_setformat ()
	{
		if (!spectra_node_isalive ())
		{
			spectra_log_write (1, "Unable to connect to ".$GLOBALS["currency"]["name"]." node.");
		}
	
	//	Retrieve the first block from the chain without specifying 
	//	verbose mode
		$hash = getblockhash (1);
		$block = getblock ($hash);

	//	Determine the format of the returned block data
		if (is_array ($block))
		{
			system_flag_set ("block_req_verbose", 0);
			spectra_log_write (0, "Block format set to ignore verbose mode.");
		}
		
		else
		{
			system_flag_set ("block_req_verbose", 1);
			spectra_log_write (0, "Block format set to require verbose mode.");
		}	
	}
	
//	This function removes the data from a block that has been orphaned
	function spectra_orphan_wipe ($blockhash)
	{
	//	Write alog message
		spectra_log_write (0, "Deleting data for block: ".$blockhash);
		
	//	Remove Related vin data
		$clear = mysqli_delete ($GLOBALS["tables"]["vin"], "`in_block` = '".$blockhash."'");
		if ($clear["success"] < 1)
		{
			spectra_log_write (1, "Unable to delete vin data for block: ".$blockhash);
		}
		
	//	Remove related vout data
		$clear = mysqli_delete ($GLOBALS["tables"]["vout"], "`in_block` = '".$blockhash."'");
		if ($clear["success"] < 1)
		{
			spectra_log_write (1, "Unable to delete vout data for block: ".$blockhash);
		}
		
	//	Remove related tx data
		$clear = mysqli_delete ($GLOBALS["tables"]["tx"], "`in_block` = '".$blockhash."'");
		if ($clear["success"] < 1)
		{
			spectra_log_write (1, "Unable to delete tx data for block: ".$blockhash);
		}
		
	//	Remove any addresses first seen in this block
		$clear = mysqli_delete ($GLOBALS["tables"]["ledger"], "`firstblock` = '".$blockhash."'");
		if ($clear["success"] < 1)
		{
			spectra_log_write (1, "Unable to delete tx data for block: ".$blockhash);
		}
		
	//	Remove the block record
		$clear = mysqli_delete ($GLOBALS["tables"]["block"], "`hash` = '".$blockhash."'");
		if ($clear["success"] < 1)
		{
			spectra_log_write (1, "Unable to delete block data for block: ".$blockhash);
		}
	}

//	This function performs a check to see if the specified 
//	node is responding and returns a boolean value
	function spectra_node_isalive ()
	{
		$info = getinfo ();
		
		if (isset ($info["error"]) && is_array ($info["error"]))
		{
			return FALSE;
		}
		
		else
		{
			return TRUE;
		}
	}
	
/******************************************************************************
	Text Search
******************************************************************************/

//	Find all transaction IDs that match the provided string
	function spectra_search_tx ($search_text)
	{
		echo "		<h2> Transaction Search Results: </h2> \n\n";

		$tx = mysqli_getset ($GLOBALS["tables"]["tx"], "`txid` LIKE '%".$_POST["searchtext"]."%'");

		if (!isset ($tx["data"]) || $tx["data"] == "")
		{
			echo "		<p class=\"search_dark\"> No Matching Transaction Records </p> \n\n";
		}

		else
		{
			echo "		<p class=\"search_dark\"> Transaction IDs Containing \"".substr ($_POST["searchtext"], 0, 32)." ...\": </p> \n\n";

			foreach ($tx["data"] as $transaction)
			{
				echo "		<p> \n";
				echo "		<a href=\"tx.php?tx=".$transaction["txid"]."\" title=\"Transaction Detaiil Page\"> \n";
				echo "			".$transaction["txid"]."\n";
				echo "		</a> \n";
				echo "		</p> \n\n";
			}
		}
	}

//	Find all block hashes that match the provided string
	function spectra_search_block ($search_text)
	{
		echo "		<h2> Block Hash Search Results: </h2> \n\n";

		$block = mysqli_getset ($GLOBALS["tables"]["block"], "`hash` LIKE '%".$_POST["searchtext"]."%'");

		if (!isset ($block["data"]) || $block["data"] == "")
		{
			echo "		<p class=\"search_dark\"> No Matching Block Records </p> \n\n";
		}

		else
		{
			echo "		<p class=\"search_dark\"> Block Hashes Containing \"".substr ($_POST["searchtext"], 0, 32)."...\": </p> \n\n";

			foreach ($block["data"] as $block_data)
			{
				echo "		<p> \n";
				echo "		<a href=\"block.php?hash=".$block_data["hash"]."\" title=\"Block Detaiil Page\"> \n";
				echo "			".$block_data["hash"]."\n";
				echo "		</a> \n";
				echo "		</p> \n\n";
			}
		}
	}

//	Find all addresses that match the provided string
	function spectra_search_address ($search_text)
	{
		echo "		<h2> Address Search Results: </h2> \n\n";

		$address = mysqli_getset ($GLOBALS["tables"]["ledger"], "`address` LIKE '%".$search_text."%'");

		if (!isset ($address["data"]) || $address["data"] == "")
		{
			echo "		<p class=\"search_dark\"> No Matching Address Records </p> \n\n";
		}

		else
		{
			echo "		<p class=\"search_dark\"> Addresses Containing \"".substr ($search_text, 0, 32)."\": </p> \n\n";

			foreach ($address["data"] as $address)
			{
				echo "		<p> \n";
				echo "		<a href=\"address.php?address=".$address["address"]."\" title=\"Address Detaiil Page\"> \n";
				echo "			".$address["address"]."\n";
				echo "		</a> \n";
				echo "		</p> \n\n";
			}
		}
	}



/******************************************************************************
	Explorer Statistics
******************************************************************************/

//	Return the current block height for the explorer
	function spectra_block_height ()
	{
		$response = $GLOBALS["db"]["obj"]->query ("SELECT MAX(`height`) as `height` FROM `".$GLOBALS["tables"]["block"]."`");
		$result = $response->fetch_assoc ();
		
		return $result["height"];
	}

//	Return the node data for the current highest block
	function spectra_block_top ()
	{
		$hash = getbestblockhash ();
		$block = getblock ($hash);
		
		return $block;
	}
	
//	This funciton returns the number of transactions int he specified block	
	function spectra_block_txcount ($blockhash)
	{
		$tx_list = mysqli_getset ($GLOBALS["tables"]["tx"], "`in_block` = '".$blockhash."'");
		
		return count ($tx_list["data"]);
	}

/******************************************************************************
	Address / Balance Statistics
******************************************************************************/

//	Return a count of all found addresses
	function spectra_address_count ()
	{
		$response = $GLOBALS["db"]["obj"]->query ("SELECT COUNT(*) AS `found` FROM `".$GLOBALS["tables"]["ledger"]."` WHERE 1");
		$result = $response->fetch_assoc ();
		
		if (!isset ($result["found"]) || $result["found"] == 0)
		{
			return "Unknown";
		}
		
		else
		{
			return $result["found"];
		}
	}
	
//	Check for the existence of a specific address
	function spectra_address_exists ($address)
	{
		$response = $GLOBALS["db"]["obj"]->query ("SELECT COUNT(*) AS `found` FROM `".$GLOBALS["tables"]["ledger"]."` WHERE `address` = '".$address."'");
		$result = $response->fetch_assoc ();
		
		if (!isset ($result["found"]) || $result["found"] == 0)
		{
			return FALSE;
		}
		
		else
		{
			return TRUE;
		}
	}
	
//	Returns the balance of a single address
	function spectra_address_balance ($address)
	{
		if (system_flag_get ("balance_rebuild") > 0)
		{
			return "Re-Balancing";
		}
		
		$response = $GLOBALS["db"]["obj"]->query ("SELECT `balance` FROM `".$GLOBALS["tables"]["ledger"]."` WHERE `address` = '".$address."'");
		$result = $response->fetch_assoc ();
		
		if (!isset ($result["balance"]))
		{
			return "Unavailable";
		}
		
		else
		{
			return $result["balance"];
		}
	}

//	Calculates the sum of all current balances
	function spectra_money_supply ()
	{
		if (system_flag_get ("balance_rebuild") > 0)
		{
			return "Re-Balancing";
		}
		
		$response = $GLOBALS["db"]["obj"]->query ("SELECT SUM(`balance`) AS `supply` FROM `".$GLOBALS["tables"]["ledger"]."`");
		$result = $response->fetch_assoc ();
		
		if (!isset ($result["supply"]))
		{
			return "Unavailable";
		}
		
		else
		{
			return $result["supply"];
		}
	}
	
//	Calculates the sum of the top 10 balances
	function spectra_money_top10 ()
	{
		if (system_flag_get ("balance_rebuild") > 0)
		{
			$balance_calc["total"] = "Re-Balancing";
			$balance_calc["percent"] = "0.00";
		
			return $balance_calc;
		}
		
		$accounts = mysqli_getset ($GLOBALS["tables"]["ledger"], "1 ORDER BY `balance` DESC LIMIT 10 OFFSET 0");

		if ($accounts["success"] < 1 || $accounts["data"] == "")
		{
			$balance_calc["total"] = "Unavailable";
			$balance_calc["percent"] = "0.00";
		
			return $balance_calc;
		}
		
	//	Initialize Calculation Result
		$sum_balances = 0;
		
		foreach ($accounts["data"] as $account)
		{
			$sum_balances = bcadd ($sum_balances, $account["balance"], 8);
		}
		
		$calc_perc = bcdiv ($sum_balances, spectra_money_supply (), 8);
	
		$balance_calc["total"] = $sum_balances;
		$balance_calc["percent"] = ($calc_perc * 100);
		
		return $balance_calc;
	}
	
//	Calculates the sum of the (11 - 100) highest balances
	function spectra_money_top100 ()
	{
		if (system_flag_get ("balance_rebuild") > 0)
		{
			$balance_calc["total"] = "Re-Balancing";
			$balance_calc["percent"] = "0.00";
		
			return $balance_calc;
		}
		
		$accounts = mysqli_getset ($GLOBALS["tables"]["ledger"], "1 ORDER BY `balance` DESC LIMIT 90 OFFSET 10");

		if ($accounts["success"] < 1 || $accounts["data"] == "")
		{
			$balance_calc["total"] = "Unavailable";
			$balance_calc["percent"] = "0.00";
		
			return $balance_calc;
		}
		
	//	Initialize Calculation Result
		$sum_balances = 0;
		
		foreach ($accounts["data"] as $account)
		{
			$sum_balances = bcadd ($sum_balances, $account["balance"], 8);
		}
		
		$calc_perc = bcdiv ($sum_balances, spectra_money_supply (), 8);
	
		$balance_calc["total"] = $sum_balances;
		$balance_calc["percent"] = ($calc_perc * 100);
		
		return $balance_calc;
	}
	
//	Calculates the sum of th (101 - 1000) highest balances
	function spectra_money_top1000 ()
	{
		if (system_flag_get ("balance_rebuild") > 0)
		{
			$balance_calc["total"] = "Re-Balancing";
			$balance_calc["percent"] = "0.00";
		
			return $balance_calc;
		}
		
		$accounts = mysqli_getset ($GLOBALS["tables"]["ledger"], "1 ORDER BY `balance` DESC LIMIT 1000 OFFSET 100");

		if ($accounts["success"] < 1 || $accounts["data"] == "")
		{
			$balance_calc["total"] = "Unavailable";
			$balance_calc["percent"] = "0.00";
		
			return $balance_calc;
		}
		
	//	Initialize Calculation Result
		$sum_balances = 0;
		
		foreach ($accounts["data"] as $account)
		{
			$sum_balances = bcadd ($sum_balances, $account["balance"], 8);
		}
		
		$calc_perc = bcdiv ($sum_balances, spectra_money_supply (), 8);
	
		$balance_calc["total"] = $sum_balances;
		$balance_calc["percent"] = ($calc_perc * 100);
		
		return $balance_calc;
	}
	
//	update the "owner" field of an address when claimed
	function spectra_address_addowner ($owner, $address)
	{
		$prep = $GLOBALS["db"]["obj"]->prepare ("UPDATE `".$GLOBALS["tables"]["ledger"]."` SET `owner` = ? where `address` = ?");
		
		if (!$prep)
		{
			echo "Unable To Prepare Statement (".__FUNCTION__.": ".__LINE__.") ".$GLOBALS["db"]["obj"]->error;
			return FALSE;
		}
		
		$bound = $prep->bind_param ("ss", $owner, $address);
		
		if (!$bound)
		{
			echo "Unable To Bind Parameters (".__FUNCTION__.": ".__LINE__.") ".$GLOBALS["db"]["obj"]->error;
			return FALSE;
		}
		
		$response = $prep->execute ();
		
		if ($GLOBALS["db"]["obj"]->errno > 0)
		{
			echo "Unable To Execute Statement (".__FUNCTION__.": ".__LINE__.") ".$GLOBALS["db"]["obj"]->error;
			return FALSE;
		}
		
		return TRUE;
	}

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>