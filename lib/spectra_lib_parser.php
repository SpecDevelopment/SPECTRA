<?php 
//	This script is used to parse the output of a bitcoin-type crypto
//	currency node and insert it into the SPECTRA Block Explorer database.

/******************************************************************************
	Block Processing
******************************************************************************/

	function spectra_block_load ($block_height)
	{
	//	Fetch the hash for the specified block height
		$hash = getblockhash ($block_height);

	//	Fetch the block data from the node
		$block = getblock ($hash, TRUE);

	//	The block-level data is sorted into an array to match the 
	//	formatting of the prepared statement
		$parsed_block = spectra_block_parse ($block);

	//	Parse and calculate each transaction in the block
		foreach ($block["tx"] as $txid)
		{
		//	Parse the transaction by ID
			$tx_parsed = spectra_tx_parse ($txid);

		//	Process the transaction vins
			foreach ($tx_parsed["vin"] as $index => $vin)
			{
			//	Parse the vin data together with the tx data
				$parsed_vin = spectra_vin_parse ($tx_parsed, $vin);

			//	Bind and insert the vin data
				$bound = $GLOBALS["db"]["prep"]["vin"]->bind_param ("ssisdssiss", $parsed_vin["src_block"], $parsed_vin["src_tx"], $parsed_vin["src_vout"], $parsed_vin["src_address"], $parsed_vin["src_value"], $parsed_vin["in_block"], $parsed_vin["in_tx"], $parsed_vin["time"], $parsed_vin["coinbase"], $parsed_vin["sequence"]);

				if (!$bound)
				{
					spectra_log_write (1, "(prep_vin: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
				}

				$response = $GLOBALS["db"]["prep"]["vin"]->execute ();

				if ($GLOBALS["db"]["obj"]->errno > 0)
				{
					spectra_log_write (1, "(prep_vin: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
				}

			//	Update the transaction 
				$tx_parsed["val_in"] = bcadd ($tx_parsed["val_in"], $parsed_vin["src_value"], 8);

			//	If this is not a generation, update the sending address
				if ($parsed_vin["src_address"] !== "Generated")
				{
				//	decode the source address
					$addr_src = json_decode ($parsed_vin["src_address"], 1);

				//	Retrieve information about the source address
					$record = mysqli_getrow ($GLOBALS["tables"]["ledger"], "`address` = '".$addr_src[0]."'");

				//	Increment the outgoing transaction counter for the source address
					$tx_outcount = $record["data"]["tx_out"] + 1;

				//	Increment the outgoing transaction value for the source address
					$spent = bcadd ($record["data"]["spent"], $parsed_vin["src_value"], 8);

				//	Adjust the source account balance
					$balance = bcsub($record["data"]["balance"], $parsed_vin["src_value"], 8);

				//	Bind and insert the updated values
					$bound = $GLOBALS["db"]["prep"]["addvin"]->bind_param ("idds", $tx_outcount, $spent, $balance, $addr_src[0]);

					if (!$bound)
					{
						spectra_log_write (1, "(prep_addvin: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
					}

					$response = $GLOBALS["db"]["prep"]["addvin"]->execute ();

					if ($GLOBALS["db"]["obj"]->errno > 0)
					{
						spectra_log_write (1, "(prep_addvin: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
					}
				}
			}

		//	It is necessary to watch for a stake modifier in order
		//	to separate coins generated in a block-split from any 
		//	tx fees in the block.
			$modifier = 0;
			
		//	Process the transaction vouts
			foreach ($tx_parsed["vout"] as $index => $vout)
			{
			//	Parse the vout
				$parsed_vout = spectra_vout_parse ($parsed_block["flags"], $tx_parsed, $vout);
			
			//	Set the modiier flag if this vout is a stake-modifier
				if (strcasecmp (substr ($parsed_vout["addresses"], 2, 14), "Stake Modifier") == 0)
				{
					$modifier = 1;
				}
				
			//	Insert the vout data
				$bound = $GLOBALS["db"]["prep"]["vout"]->bind_param ("ssidiiss", $parsed_vout["in_block"], $parsed_vout["in_tx"], $parsed_vout["time"], $parsed_vout["value"], $parsed_vout["n"], $parsed_vout["reqsigs"], $parsed_vout["type"], $parsed_vout["addresses"]);

				if (!$bound)
				{
					spectra_log_write (1, "(prep_vout: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
				}

				$response = $GLOBALS["db"]["prep"]["vout"]->execute ();

				if ($GLOBALS["db"]["obj"]->errno > 0)
				{
					spectra_log_write (1, "(prep_vout: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
				}
		
			//	Update the transaction 
				$tx_parsed["val_out"] = bcadd ($tx_parsed["val_out"], $parsed_vout["value"], 8);

			//	If this address is unknown a record is created
				foreach (json_decode ($parsed_vout["addresses"], 1) as $address)
				{
					$exists = mysqli_isrow ($GLOBALS["tables"]["ledger"], "`address` = '".$address."'");

					if (!$exists["data"])
					{				
					//	Format an empty address record
						$address_new = spectra_address_new ();
		
					//	Populate the empty record with the supplied values
						$address_new["address"] = $address;
						$address_new["firstblock"] = $block["hash"];
						
					//	Bind the record for insertion
						$bound = $GLOBALS["db"]["prep"]["address"]->bind_param ("ssididds", $address_new["address"], $address_new["firstblock"], $address_new["tx_in"], $address_new["received"], $address_new["tx_out"], $address_new["spent"], $address_new["balance"], $address_new["owner"]);

						if (!$bound)
						{
							spectra_log_write (1, "(prep_address: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
						}
					
					//	Execute the prepared statement 
						$response = $GLOBALS["db"]["prep"]["address"]->execute ();

						if ($GLOBALS["db"]["obj"]->errno > 0)
						{
							spectra_log_write (1, "(prep_address: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
						}
					}
				}

			//	For balancing purposes, in case of a multi-sig tx 
			//	Only the first address in the list is credited with
			//	the balance from the transaction

			//	Update address information with values from this tx
			//	Decode the addresses field from the parsed vout
				$addresses = json_decode ($parsed_vout["addresses"], 1);

			//	Retrieve information about the receiving address
				$record = mysqli_getrow ($GLOBALS["tables"]["ledger"], "`address` = '".$addresses[0]."'");

			//	Increment the incoming transaction counter for the receiving address
				$tx_incount = $record["data"]["tx_in"] + 1;

			//	Increment the incoming transaction value for the receiving address
				$received = bcadd ($record["data"]["received"], $parsed_vout["value"], 8);

			//	Adjust the receiving account balance
				$balance = bcadd($record["data"]["balance"], $parsed_vout["value"], 8);

			//	Bind and insert the updated values
				$bound = $GLOBALS["db"]["prep"]["addvout"]->bind_param ("idds", $tx_incount, $received, $balance, $addresses[0]);

				if (!$bound)
				{
					spectra_log_write (1, "(prep_addvout: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
				}

				$response = $GLOBALS["db"]["prep"]["addvout"]->execute ();

				if ($GLOBALS["db"]["obj"]->errno > 0)
				{
					spectra_log_write (1, "(prep_addvout: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
				}
			}

		//	Calculate the fee value for this transaction
			if ($modifier == 0)
			{
				$tx_parsed["val_fee"] = bcsub ($tx_parsed["val_in"], $tx_parsed["val_out"], 8);
			}
			
			else
			{
				$tx_parsed["val_fee"] = 0;
			}
			
		//	Bind and insert the parsed tx
			$bound = $GLOBALS["db"]["prep"]["tx"]->bind_param ("ssiiiisddd", $tx_parsed["in_block"], $tx_parsed["txid"], $tx_parsed["version"], $tx_parsed["time"], $tx_parsed["locktime"], $tx_parsed["blocktime"], $tx_parsed["tx-comment"], $tx_parsed["val_in"], $tx_parsed["val_out"], $tx_parsed["val_fee"]);

			if (!$bound)
			{
				spectra_log_write (1, "(prep_tx: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
			}

			$response = $GLOBALS["db"]["prep"]["tx"]->execute ();

			if ($GLOBALS["db"]["obj"]->errno > 0)
			{
				spectra_log_write (1, "(prep_tx: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
			}
		
		//	Update the block with the values
			$parsed_block["val_in"] = bcadd ($parsed_block["val_in"], $tx_parsed["val_in"], 8);
			
			$parsed_block["val_out"] = bcadd ($parsed_block["val_out"], $tx_parsed["val_out"], 8);
			
			$parsed_block["val_fee"] = bcadd ($parsed_block["val_fee"], $tx_parsed["val_fee"], 8);
		}

	//	Insert the block data into the database
		$bound = $GLOBALS["db"]["prep"]["block"]->bind_param ("ssiiidssssiisdsssssddd", $parsed_block["hash"], $parsed_block["proofhash"], $parsed_block["size"], $parsed_block["height"], $parsed_block["version"], $parsed_block["mint"], $parsed_block["flags"], $parsed_block["entropybit"], $parsed_block["merkleroot"], $parsed_block["tx"], $parsed_block["time"], $parsed_block["nonce"], $parsed_block["bits"], $parsed_block["difficulty"], $parsed_block["modifier"], $parsed_block["modifierchecksum"], $parsed_block["signature"], $parsed_block["previousblockhash"], $parsed_block["nextblockhash"], $parsed_block["val_in"], $parsed_block["val_out"], $parsed_block["val_fee"]);
		
		if (!$bound)
		{
			spectra_log_write (1, "(prep_block: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}
		
		$response = $GLOBALS["db"]["prep"]["block"]->execute ();
		
		if ($GLOBALS["db"]["obj"]->errno > 0)
		{
			spectra_log_write (1, "(prep_block: ".__LINE__.") ".$GLOBALS["db"]["obj"]->error);
		}

	//	Reset prepared statements for performance
		spectra_prep_reset ();
	}

/******************************************************************************
	Structure Parsing
******************************************************************************/

	function spectra_block_parse ($block)
	{
	//	Prepare a parsed block array for values		
		$parsed_block = spectra_block_new ();

	//	Insert values for this block into the parsed block array
		if (isset ($block["hash"]) && $block["hash"] != "")
		{
			$parsed_block["hash"] = $block["hash"];
		}

		if (isset ($block["proofhash"]) && $block["proofhash"] != "")
		{
			$parsed_block["proofhash"] = $block["proofhash"];
		}

		if (isset ($block["size"]) && $block["size"] != "")
		{
			$parsed_block["size"] = $block["size"];
		}
		
		if (isset ($block["height"]) && $block["height"] != "")
		{
			$parsed_block["height"] = $block["height"];
		}
		
		if (isset ($block["version"]) && $block["version"] != "")
		{
			$parsed_block["version"] = $block["version"];
		}
		
		if (isset ($block["mint"]) && $block["mint"] != "")
		{
			$parsed_block["mint"] = $block["mint"];
		}

		if (isset ($block["flags"]) && $block["flags"] != "")
		{
			$parsed_block["flags"] = $block["flags"];
		}

		if (isset ($block["entropybit"]) && $block["entropybit"] != "")
		{
			$parsed_block["entropybit"] = $block["entropybit"];
		}

		if (isset ($block["merkleroot"]) && $block["merkleroot"] != "")
		{
			$parsed_block["merkleroot"] = $block["merkleroot"];
		}
		
		if (isset ($block["tx"]) && $block["tx"] != "")
		{
			$parsed_block["tx"] = json_encode ($block["tx"]);
		}
		
		if (isset ($block["time"]) && $block["time"] != "")
		{
		//	Some chains included a pre-formatted date in the block data	
			if (!is_numeric ($block["time"]))
			{
				$parsed_block["time"] = strtotime ($block["time"]);
			}
			
			else
			{
				$parsed_block["time"] = $block["time"];
			}
		}
		
		if (isset ($block["nonce"]) && $block["nonce"] != "")
		{
			$parsed_block["nonce"] = $block["nonce"];
		}
		
		if (isset ($block["bits"]) && $block["bits"] != "")
		{
			$parsed_block["bits"] = $block["bits"];
		}
		
		if (isset ($block["difficulty"]) && $block["difficulty"] != "")
		{
			$parsed_block["difficulty"] = $block["difficulty"];
		}
		
		if (isset ($block["modifier"]) && $block["modifier"] != "")
		{
			$parsed_block["modifier"] = $block["modifier"];
		}

		if (isset ($block["modifierchecksum"]) && $block["modifierchecksum"] != "")
		{
			$parsed_block["modifierchecksum"] = $block["modifierchecksum"];
		}

		if (isset ($block["signature"]) && $block["signature"] != "")
		{
			$parsed_block["signature"] = $block["signature"];
		}

		if (isset ($block["previousblockhash"]) && $block["previousblockhash"] != "")
		{
			$parsed_block["previousblockhash"] = $block["previousblockhash"];
		}
		
		if (isset ($block["nextblockhash"]) && $block["nextblockhash"] != "")
		{
			$parsed_block["nextblockhash"] = $block["nextblockhash"];
		}
		
		else
		{
			$parsed_block["nextblockhash"] = "Pending";
		}

		return $parsed_block;
	}	
	
	function spectra_tx_parse ($txid)
	{
	//	Prepare an array for the parsed and calculated data
		$parsed_tx = spectra_tx_new ();
		
	//	Fetch the detailed transaction data from the node
		$tx_verbose = getrawtransaction ($txid);

	//	Populate the array with known data
		if (isset ($tx_verbose["blockhash"]) && $tx_verbose["blockhash"] != "")
		{
			$parsed_tx["in_block"] = $tx_verbose["blockhash"];
		}
		
		if (isset ($tx_verbose["txid"]) && $tx_verbose["txid"] != "")
		{
			$parsed_tx["txid"] = $tx_verbose["txid"];
		}
		
		if (isset ($tx_verbose["version"]) && $tx_verbose["version"] != "")
		{
			$parsed_tx["version"] = $tx_verbose["version"];
		}
		
		if (isset ($tx_verbose["time"]) && $tx_verbose["time"] != "")
		{
			$parsed_tx["time"] = $tx_verbose["time"];
		}
		
		if (isset ($tx_verbose["locktime"]) && $tx_verbose["locktime"] != "")
		{
			$parsed_tx["locktime"] = $tx_verbose["locktime"];
		}
		
		if (isset ($tx_verbose["blocktime"]) && $tx_verbose["blocktime"] != "")
		{
			$parsed_tx["blocktime"] = $tx_verbose["blocktime"];
		}
		
		if (isset ($tx_verbose["tx-comment"]) && $tx_verbose["tx-comment"] != "")
		{
			$parsed_tx["tx-comment"] = $tx_verbose["tx-comment"];
		}
		
	//	These fields are not cached, but they are used to parse the transaction values
		if (isset ($tx_verbose["vin"]) && $tx_verbose["vin"] != "")
		{
			$parsed_tx["vin"] = $tx_verbose["vin"];
		}
		
		if (isset ($tx_verbose["vout"]) && $tx_verbose["vout"] != "")
		{
			$parsed_tx["vout"] = $tx_verbose["vout"];
		}
		
	//	Return the parsed tx to the block parser for use in calculating block values
		return $parsed_tx;
	}

	function spectra_vin_parse ($parsed_tx, $vin)
	{
	//	An empty vin record is creatd to populate all expected fields
		$vin_parsed = spectra_vin_new ();
		
	//	Add information about the vin location
		$vin_parsed["in_block"] = $parsed_tx["in_block"];
		$vin_parsed["in_tx"] = $parsed_tx["txid"];
		$vin_parsed["time"] = $parsed_tx["time"];
		
	//	Move in the values from the current vin	
		if (isset ($vin["coinbase"]) && $vin["coinbase"] != "")
		{
		//	This is a coinbase transaction
			$vin_parsed["coinbase"] = $vin["coinbase"];
			$vin_parsed["sequence"] = $vin["sequence"];
			
		//	Calculate the value out
			$val_out = 0;
			
			foreach ($parsed_tx["vout"] as $spend)
			{
				$val_out = bcadd ($val_out, $spend["value"], 8);
			}
			
		//	Update the vin with the coinbase value
			$vin_parsed["src_block"] = $parsed_tx["in_block"];
			$vin_parsed["src_tx"] = "Coinbase";
			$vin_parsed["src_vout"] = 0;
			$vin_parsed["src_address"] = "Generated";
			$vin_parsed["src_value"] = $val_out;
		}
		
		else
		{
		//	This is a p2p transaction
		//	Fetch the origin vout
			$vout_orig = mysqli_getrow ($GLOBALS["tables"]["vout"], "`in_tx` = '".$vin["txid"]."' AND `n` = '".$vin["vout"]."'");
			
		//	Add information from the origin vout
			$vin_parsed["src_block"] = $vout_orig["data"]["in_block"];
			$vin_parsed["src_tx"] = $vout_orig["data"]["in_tx"];
			$vin_parsed["src_vout"] = $vin["vout"];
			$vin_parsed["src_address"] = $vout_orig["data"]["addresses"];
			$vin_parsed["src_value"] = $vout_orig["data"]["value"];
		}

	//	Return expanded vin data
		return $vin_parsed;
	}
	
	function spectra_vout_parse ($blocktype, $parsed_tx, $vout)
	{
	//	Format an empty vout record
		$vout_parsed = spectra_vout_new	();
		
	//	Add information about the vout location
		$vout_parsed["in_block"] = $parsed_tx["in_block"];
		$vout_parsed["in_tx"] = $parsed_tx["txid"];
		$vout_parsed["time"] = $parsed_tx["time"];
		
	//	Add vout-specific details
		if (isset ($vout["value"]) && $vout["value"] != "")
		{	
			$vout_parsed["value"] = $vout["value"];
		}
		
		if (isset ($vout["n"]) && $vout["n"] != "")
		{	
			$vout_parsed["n"] = $vout["n"];
		}
					
		if (isset ($vout["scriptPubKey"]["reqSigs"]) && $vout["scriptPubKey"]["reqSigs"] != "")
		{	
			$vout_parsed["reqsigs"] = $vout["scriptPubKey"]["reqSigs"];
		}
		
		if (isset ($vout["scriptPubKey"]["type"]) && $vout["scriptPubKey"]["type"] != "")
		{	
			$vout_parsed["type"] = $vout["scriptPubKey"]["type"];
		}
		
		if (isset ($vout["scriptPubKey"]["addresses"]) && $vout["scriptPubKey"]["addresses"] != "")
		{	
			$vout_parsed["addresses"] = json_encode ($vout["scriptPubKey"]["addresses"]);
		}
		
		elseif (!isset ($vout["scriptPubKey"]["addresses"]) && strcasecmp (substr ($blocktype, 0, 14), "proof-of-stake") == 0)
		{
			$vout_parsed["addresses"] = "[\"Stake Modifier\"]";
		}
		
		elseif (!isset ($vout["scriptPubKey"]["addresses"]) && $vout_parsed["type"] == "nonstandard")
		{
			$vout_parsed["addresses"] = "[\"Output Modifier\"]";
		}
		
		else
		{
			$vout_parsed["addresses"] = "[\"No Address Found\"]";
		}
		
	//	Return the parsed vout
		return $vout_parsed;
	}
	
/******************************************************************************
	Structures
******************************************************************************/

	function spectra_block_new ()
	{
		$block["hash"] = "";
		$block["proofhash"] = "";
		$block["size"] = 0;
		$block["height"] = 0;
		$block["version"] = 0;
		$block["mint"] = "";
		$block["flags"] = "";
		$block["entropybit"] = "";
		$block["merkleroot"] = "";
		$block["tx"] = "";
		$block["time"] = 0;
		$block["nonce"] = "";
		$block["bits"] = "";
		$block["difficulty"] = 0;
		$block["modifier"] = "";
		$block["modifierchecksum"] = "";
		$block["signature"] = "";
		$block["previousblockhash"] = "";
		$block["nextblockhash"] = "";
		$block["val_in"] = 0;
		$block["val_out"] = 0;
		$block["val_fee"] = 0;
	
		return $block;
	}
	
	function spectra_tx_new ()
	{
		$tx["in_block"] = "";
		$tx["txid"] = "";
		$tx["version"] = 0;
		$tx["time"] = 0;
		$tx["locktime"] = 0;
		$tx["blocktime"] = 0;
		$tx["tx-comment"] = "";
		$tx["val_in"] = 0;
		$tx["val_out"] = 0;
		$tx["val_fee"] = 0;
	
		return $tx;
	}
	
	function spectra_vin_new ()
	{
		$vin["src_block"] = "";
		$vin["src_tx"] = "";
		$vin["src_vout"] = 0;
		$vin["src_address"] = "";
		$vin["src_value"] = 0;
		$vin["in_block"] = "";
		$vin["in_tx"] = "";
		$vin["time"] = "";
		$vin["coinbase"] = "";
		$vin["sequence"] = "";
	
		return $vin;
	}

	function spectra_vout_new ()
	{
		$vout["in_block"] = "";
		$vout["in_tx"] = "";
		$vout["time"] = "";
		$vout["value"] = 0;
		$vout["n"] = 0;
		$vout["reqsigs"] = 0;
		$vout["type"] = "";
		$vout["addresses"] = "";
	
		return $vout;
	}

	function spectra_address_new ()
	{
		$address["address"] = "";
		$address["firstblock"] = "";
		$address["tx_in"] = 0;
		$address["received"] = 0;
		$address["tx_out"] = 0;
		$address["spent"] = 0;
		$address["fees"] = 0;
		$address["balance"] = 0;
		$address["owner"] = "";
		$address["sign"] = "";

		return $address;
	}
	
/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>