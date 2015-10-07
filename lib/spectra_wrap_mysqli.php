<?php 
//	This script provides the MySQL configuration and status-enabled 
//	wrappers for some commonly used database functions and enables
//	other task-specific libraries used by the SPECTRA Block Explorer.

/******************************************************************************
	All scripts share a common database connection	
/*****************************************************************************/

	$GLOBALS["db"]["obj"] = new mysqli($GLOBALS["db"]["host"], $GLOBALS["db"]["user"], $GLOBALS["db"]["pass"], $GLOBALS["db"]["name"]);
	
	if (mysqli_connect_errno($GLOBALS["db"]["obj"])) 
	{
	    spectra_page_error ("Database Connection Error", "Unable to connect to MySQL Database Server", mysqli_connect_errno($GLOBALS["db"]["obj"]));
	}

//	When invoked from the command line the block explorer loads a set
//	of prepared statements used to manage the block data
	if (!isset  ($_SERVER["HTTP_HOST"]) && mysqli_istable ($GLOBALS["tables"]["block"]))
	{
		$GLOBALS["db"]["prep"]["block"] = $GLOBALS["db"]["obj"]->prepare ("INSERT INTO `".$GLOBALS["tables"]["block"]."` (`hash`, `proofhash`, `size`, `height`, `version`, `mint`, `flags`, `entropybit`, `merkleroot`, `tx`, `time`, `nonce`, `bits`, `difficulty`, `modifier`, `modifierchecksum`, `signature`, `previousblockhash`, `nextblockhash`, `val_in`, `val_out`, `val_fee`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		
		if (!$GLOBALS["db"]["prep"]["block"])
		{
			spectra_log_write (1, "(prep_block: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}
		
		$GLOBALS["db"]["prep"]["tx"] = $GLOBALS["db"]["obj"]->prepare ("INSERT INTO `".$GLOBALS["tables"]["tx"]."` (`in_block`, `txid`, `version`, `time`, `locktime`, `blocktime`, `tx-comment`, `val_in`, `val_out`, `val_fee`) VALUES (?,?,?,?,?,?,?,?,?,?)");
		
		if (!$GLOBALS["db"]["prep"]["tx"])
		{
			spectra_log_write (1, "(prep_tx: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}
		
		$GLOBALS["db"]["prep"]["vin"] = $GLOBALS["db"]["obj"]->prepare ("INSERT INTO `".$GLOBALS["tables"]["vin"]."` (`src_block`, `src_tx`, `src_vout`, `src_address`, `src_value`, `in_block`, `in_tx`, `time`, `coinbase`, `sequence`) VALUES (?,?,?,?,?,?,?,?,?,?)");
		
		if (!$GLOBALS["db"]["prep"]["vin"])
		{
			spectra_log_write (1, "(prep_vin: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}
		
		$GLOBALS["db"]["prep"]["vout"] = $GLOBALS["db"]["obj"]->prepare ("INSERT INTO `".$GLOBALS["tables"]["vout"]."` (`in_block`, `in_tx`, `time`, `value`, `n`, `reqsigs`, `type`, `addresses`) VALUES (?,?,?,?,?,?,?,?)");
		
		if (!$GLOBALS["db"]["prep"]["vout"])
		{
			spectra_log_write (1, "(prep_vout: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}
		
		$GLOBALS["db"]["prep"]["address"] = $GLOBALS["db"]["obj"]->prepare ("INSERT INTO `".$GLOBALS["tables"]["ledger"]."` (`address`, `firstblock`, `tx_in`, `received`, `tx_out`, `spent`, `balance`, `owner`) VALUES (?,?,?,?,?,?,?,?)");
		
		if (!$GLOBALS["db"]["prep"]["address"])
		{
			spectra_log_write (1, "(prep_address: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}
		
		$GLOBALS["db"]["prep"]["addvin"] = $GLOBALS["db"]["obj"]->prepare ("UPDATE `".$GLOBALS["tables"]["ledger"]."` SET `tx_out` = ?, `spent` = ?, `balance` = ? WHERE `address` = ?");
		
		if (!$GLOBALS["db"]["prep"]["addvin"])
		{
			spectra_log_write (1, "(prep_addvin: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}
		
		$GLOBALS["db"]["prep"]["addvout"] = $GLOBALS["db"]["obj"]->prepare ("UPDATE `".$GLOBALS["tables"]["ledger"]."` SET `tx_in` = ?, `received` = ?, `balance` = ? WHERE `address` = ?");
		
		if (!$GLOBALS["db"]["prep"]["addvout"])
		{
			spectra_log_write (1, "(prep_addvout: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}
	}
	
//	This function performs a reset on the prepared statments to enhance
//	database performance when loading a large chain.
	function spectra_prep_reset ()
	{
		$GLOBALS["db"]["prep"]["block"]->reset ();
		
		$GLOBALS["db"]["prep"]["tx"]->reset ();
		
		$GLOBALS["db"]["prep"]["vin"]->reset ();
		
		$GLOBALS["db"]["prep"]["vout"]->reset ();
		
		$GLOBALS["db"]["prep"]["address"]->reset ();
		
		$GLOBALS["db"]["prep"]["addvin"]->reset ();
		
		$GLOBALS["db"]["prep"]["addvout"]->reset ();
	}
		
/******************************************************************************
	Output Formatting
******************************************************************************/
	function mysqli_wrap_response ($int_success, $mix_response, $function, $number)
	{
		$wrapped_response["success"] = $int_success;
		$wrapped_response["data"] = $mix_response;
		$wrapped_response["function"] = $function;
		$wrapped_response["number"] = $number;

		return ($wrapped_response);		
	}
	
/******************************************************************************
	Basic Mysqli Functionality	
******************************************************************************/
	
	function mysqli_istable ($table_name)
	{
		$result = $GLOBALS["db"]["obj"]->query ("SHOW TABLES LIKE '".$table_name."'");

	//	This function does not use the response wrapper
		if (!is_object ($result) || $result->num_rows == 0 )
		{
			return FALSE;
		}
		
		else
		{
			return TRUE;
		}
	}
	
	function mysqli_isrow ($table, $where)
	{
		$result = $GLOBALS["db"]["obj"]->query ("SELECT * FROM `".$table."` WHERE ".$where);

		if (!is_object ($result) || $result->num_rows == 0 )
		{
			return mysqli_wrap_response (1, FALSE, __FUNCTION__, __LINE__);
		}
		
		else
		{
			return mysqli_wrap_response (1, TRUE, __FUNCTION__, __LINE__);
		}
	}
	
	function mysqli_getrow ($table, $where)
	{
		$result = $GLOBALS["db"]["obj"]->query ("SELECT * FROM `".$table."` WHERE ".$where);

		return mysqli_wrap_response (1, $result->fetch_assoc (), __FUNCTION__, __LINE__);;
	}
	
	function mysqli_getset ($table, $where)
	{
		$result = $GLOBALS["db"]["obj"]->query ("SELECT * FROM `".$table."` WHERE ".$where);

		if ($result->num_rows <= 0)
		{
			return mysqli_wrap_response (1, FALSE, __FUNCTION__, __LINE__);;
		}
		
		$rows_read = 0;
		
		while ($rows_read < $result->num_rows)
		{
			$fetched_data[] = $result->fetch_assoc ();
			$rows_read ++;
		}
		
		return mysqli_wrap_response (1, $fetched_data, __FUNCTION__, __LINE__);;
	}
	
	function mysqli_getfield ($table, $field, $where)
	{
		$result = $GLOBALS["db"]["obj"]->query ("SELECT `".$field."` FROM `".$table."` WHERE ".$where);
		
		if ($result === FALSE)
		{	
			return mysqli_wrap_response (1, FALSE, __FUNCTION__, __LINE__);;
		}
	
		$field_data = $result->fetch_assoc ();
		
		return mysqli_wrap_response (1, $field_data[$field], __FUNCTION__, __LINE__);;
	}
	
	function mysqli_setfield ($table, $field, $value, $where)
	{

		$result = $GLOBALS["db"]["obj"]->query ("UPDATE `".$table."` SET `".$field."` = '".$value."' WHERE ".$where);
		
		return mysqli_wrap_response (1, $GLOBALS["db"]["obj"]->affected_rows, __FUNCTION__, __LINE__);;
	}
	
	function mysqli_setrow ($table, $data)
	{
		$processed_fields = 0;
		$fields = "";
		$values = "";

		foreach ($data as $field => $value)
		{
			$fields .= "`".$field."`";
			$values .= "'".$value."'";
			
			$processed_fields ++;
			
			if ($processed_fields < count($data))
			{
				$fields .= ", ";
				$values .= ", ";
			}
		}

		$result = $GLOBALS["db"]["obj"]->query ("INSERT INTO `".$table."` (".$fields.") VALUES (".$values.")");
		
		return mysqli_wrap_response (1, $GLOBALS["db"]["obj"]->affected_rows, __FUNCTION__, __LINE__);;
	}

	function mysqli_delete ($table, $where)
	{
		
		$result = $GLOBALS["db"]["obj"]->query ("DELETE FROM `".$table."` WHERE ".$where);
		
		return mysqli_wrap_response (1, $GLOBALS["db"]["obj"]->affected_rows, __FUNCTION__, __LINE__);;
	}

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>