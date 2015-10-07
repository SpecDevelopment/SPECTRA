<?php

//	Enable the spectra functionality
	require_once ("lib/spectra_config.php");

	spectra_site_head ($GLOBALS["currency"]["name"]." Address Claim Form");
	
	echo "	<h1> ".$GLOBALS["currency"]["name"]." Address Claim Form </h1> \n\n";
	
	echo "	<div class=\"panel_wide\"> \n\n";
	
	echo "		<p> \n";
	echo "			This wizard takes you through the process of claiming your \n";
	echo "			".$GLOBALS["currency"]["name"]." address in the block explorer \n";
	echo "			database. \n";
	echo "		</p> \n\n";
	
	echo "		<p> \n";
	echo "			Claiming your address means that the name you enter will \n";
	echo "			be shown on the balances page and other people may see \n";
	echo "			which addresses you own. \n";
	echo "		</p> \n\n";

	echo "		<p> \n";
	echo "			The process registers one address at a time. Since there \n";
	echo "			is no way to tell from the block chain which addresses \n";
	echo "			are associated with the same wallet, you may need to \n";
	echo "			claim each address in your wallet if you want all of your \n";
	echo "			".$GLOBALS["currency"]["code"]." addresses to be announced \n";
	echo "			to others on the block explorer. \n";
	echo "		</p> \n\n";

	if (isset ($_GET["address"]))
	{
	//	The address is hashed to provide a unique key
		$address_hash = hash_hmac ("sha512", $_GET["address"], md5 ($_GET["address"]));

		echo "		<h2> Generating Address / Signature Hash </h2> \n\n";

		echo "			<p> Please sign this hash using your ".$GLOBALS["currency"]["code"]." wallet: </p> \n\n";

		echo "			<p> ".$address_hash." </p> \n\n";

		echo "			<p> Be careful not to include any trailing or leading spaces \n";
		echo "			as this will cause the verification process to fail. </p> \n\n";

		echo "		<h2> Additional Information Required: </h2> \n\n";

		echo "		<p> \n";
		echo "			After creating the signature, enter the signature and the \n";
		echo "			name you want to be displayed on the block explorer in \n";
		echo "			the following form. \n";
		echo "		</p> \n\n";

		echo "		<form method=\"post\" action=\"".$_SERVER["PHP_SELF"]."\"> \n";
		echo "			Enter Your Signature: &nbsp; \n";
		echo "			<input type=\"text\" name=\"signature\" size=\"60\"> \n";
		echo "			<br><br> \n";
		
		echo "			Enter Your Name: &nbsp; \n";
		echo "			<input type=\"text\" name=\"owner_name\" size=\"60\"> \n";
		echo "			<br><br> \n";
		
		echo "			Claim Your Address: &nbsp; \n";
		echo "			<input type=\"submit\" name=\"claim_address\" value=\"Verify And Claim\"> \n";
		echo "			<br><br> \n";

		echo "			<input type=\"hidden\" name=\"address_hash\" value=\"".$address_hash."\"> \n";
		echo "			<input type=\"hidden\" name=\"address\" value=\"".$_GET["address"]."\"> \n";
		echo "		</form> \n\n";
	}
	
	elseif (isset ($_POST["claim_address"]))
	{
		echo "		<h2> Verification And Registry </h2> \n\n";

		$verified = verifymessage ($_POST["address"], $_POST["signature"], $_POST["address_hash"]);
		
		if ($verified == "true")
		{
			echo "		<p> Signature Verified! </p> \n\n";
			
			echo "		<p> Updating Address Record: </p> \n\n";
			
			$updated = spectra_address_addowner ($_POST["owner_name"], $_POST["address"]);

			if ($updated)
			{
				echo "		<p> Address Record Updated </p> \n\n";
				
				echo "		<p> \n";
				echo "			The name you entered will now be displayed as \n";
				echo "			the owner of this address in the balances list. \n";
				echo "		</p> \n\n";
			}
			
			else
			{
				echo "		<p> Unable to update address. </p> \n\n";
				
				echo "		<p> \n";
				echo "			This error may be temporary, you can try  \n";
				echo "			again in a few moments. \n";
				echo "		</p> \n\n";
				
				echo "		<p> \n";
				echo "			Your signature was verified correctly, \n";
				echo "			but the wizard is unable to update the database \n";
				echo "			record for this address. \n";
				echo "		</p> \n\n";
							
				echo "		<p> \n";
				echo "			If you continue to experience errors please \n";
				echo "			contact the site admin for additional help \n";
				echo "			with completing your claim. \n";
				echo "		</p> \n\n";
			}
		}
		
		else
		{
			echo "		<p> Unable to Verify Signature. </p> \n\n";
				
			echo "		<p> \n";
			echo "			This error may be temporary, you can try  \n";
			echo "			again in a few moments. \n";
			echo "		</p> \n\n";
				
			echo "		<p> \n";
			echo "			Please ensure that you have not copied or \n";
			echo "			pasted any blacnk spaces along with your hash \n";
			echo "			or signature data. \n";
			echo "		</p> \n\n";
							
			echo "		<p> \n";
			echo "			If you continue to experience errors please \n";
			echo "			contact the site admin for additional help \n";
			echo "			with completing your claim. \n";
			echo "		</p> \n\n";
		}
	}
	
	else
	{
		echo "		<h2> Address Is Required: </h2> \n\n";

		echo "		<p> \n";
		echo "			Please return to the <a href=\"balance.php\" title=\"Address List\"> \n";
		echo "			address list</a> and select the address you \n";
		echo "			wish to claim. \n";
		echo "		</p> \n\n";
	}	
	
	echo "	</div> \n\n";
	
	spectra_site_foot ();

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>

