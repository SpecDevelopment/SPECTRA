<?php 
//	This script is used to generate most of the HTML layout required
//	by the block explorer.  In most cases there will be no need to 
//	edit this script directly, Improper editing can cause the block 
//	explorer to be unable to render pages for the site.

/******************************************************************************
	Site-Wide Layout
******************************************************************************/
	
//	This function renders the header used on every page of the site
	function spectra_site_head ($title)
	{
	//	Document description and HTML inclusions
		echo "<!DOCTYPE html> \n";
		echo "<html> \n";
		echo "<head> \n";
		
		echo "	<title>".$title." ~ SPECTRA</title> \n";

		echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"".$GLOBALS["url"]["theme"]."spectra.css\"> \n";		
		echo "	<script type=\"text/javascript\" src=\"".$GLOBALS["url"]["home"]."spectra.js\"></script>\n";		
		
		echo "</head> \n";
		echo "<body> \n";
		echo "<div id=\"page_wrap\"> \n\n"; 
		
	//	Wrapper and inclusion for the customizable display header
		echo "	<div id=\"site_head\"> \n";
		include ($GLOBALS["path"]["theme"]."site_head_html.txt");
		echo "	\n\t</div> \n\n";
	
	//	Main navigation menu
		echo "	<div id=\"menu_wrap\"> \n";
		echo "	<div id=\"menu_line\"> \n";
		
		echo "		<a class=\"menu_button\" href=\"".$GLOBALS["url"]["home"]."index.php\" title=\"Home Page\"> \n";
		echo "			Home \n";
		echo "		</a> \n";
		
		echo "		<a class=\"menu_button\" href=\"".$GLOBALS["url"]["home"]."network.php\" title=\"Network Status\"> \n";
		echo "			Network \n";
		echo "		</a> \n";
		
		echo "		<a class=\"menu_button\" href=\"".$GLOBALS["url"]["home"]."ledger.php\" title=\"".$GLOBALS["currency"]["name"]." Ledger\"> \n";
		echo "			Ledger \n";
		echo "		</a> \n";
		
		echo "		<a class=\"menu_button\" href=\"".$GLOBALS["url"]["home"]."markets.php\" title=\"Market Information\"> \n";
		echo "			Markets \n";
		echo "		</a> \n";
		
		echo "		<a class=\"menu_button\" href=\"".$GLOBALS["url"]["home"]."api_guide.php\" title=\"API Guide\"> \n";
		echo "			API Guide \n";
		echo "		</a> \n";
		
		echo "	</div> \n";
		echo "	</div> \n\n";
		
	//	Address/tx/block hash search panel
		echo "		<div class=\"panel_search\"> \n";
		echo "		<form class=\"form_search\" method=\"post\" action=\"search.php\"> \n";
		echo "				<input type=\"text\" name=\"searchtext\" size=\"80\"> \n";
		echo "				<input type=\"submit\" name=\"searchsubmit\" value=\"Search Now\"> \n";
		echo "		</form> \n";
		echo "		</div> \n\n";
	}

//	This function renders the page footer used on every page of the site
	function spectra_site_foot ()
	{
	//	Wrapper and inclusion for the customizable display footer
		echo "	<div id=\"site_foot\"> \n";
		include ($GLOBALS["path"]["theme"]."site_foot_html.txt");
		echo "	\n\t</div> \n\n";
		
	//	Attribution and copyright information
		echo "	<div id=\"site_copyright\"> \n";
		echo "		<div id=\"copy_left\"> \n";
		echo "			<a href=\"https://github.com/SpecDevelopment/spectra\" title=\"SPECTRA On GitHub\"> \n";
		echo "				SPECTRA Block Explorer By Jake Paysnoe\n";
		echo "			</a> \n";
		echo "		</div> \n";
		echo "		<div id=\"copy_right\"> \n";
		echo "			<a href=\"https://github.com/SpecDevelopment/\" title=\"SPEC Development Team\"> \n";
		echo "				Copyright &copy; 2015 SPEC Development Team \n";
		echo "			</a> \n";
		echo "		</div> \n";
		echo "	</div> \n\n";

	//	HTML closures and script termination			
		echo "</div> \n";
		echo "</body> \n";
		echo "</html>";
		exit;
	}

//	This function calculates the values needed to paginate a query
	function spectra_page_describe ($page_name, $page_items, $page_per)
	{
	//	This function builds a descriptive array
		$page["name"] = $page_name;
		$page["items"] = $page_items;
		$page["per"] = $page_per;
		$page["count"] = ceil ($page["items"] / $page["per"]);
	
	//	Check for a page-specific request and set the page number
		if (isset ($_REQUEST[$page_name]) && $_REQUEST[$page_name] > 0)
		{
			$page["curr"] = (int) $_REQUEST[$page_name];
		}
		
		else
		{
			$page["curr"] = 1;
		}
	
	//	Calculate previous and next page numbers
		$page["next"] = $page["curr"] + 1;
		$page["prev"] = $page["curr"] - 1;
		
	//	Calculate an offset for the paged query
		$page["offset"] = ($page["curr"] - 1) * $page["per"];
		
	//	It is necesary to retain any other parameters used by the page
		$page["params"] = "";
		
		if (count ($_REQUEST) > 0)
		{
			foreach ($_REQUEST as $param => $value)
			{
				if ($param != $page["name"] && $param != "page_submit")
				{
					$page["params"] .= "&".$param."=".$value;
				}
			}
		}
	
		
	//	The descriptive array is returned for use rendering the page
		return $page;
	}

//	This function renders the page selection controls used by
//	the "browse" panels found on various pages of the site.
//	Input array is generated in spectra_page_describe.
	function spectra_page_control ($page)
	{
	//	If there's no need to paginate the function won't render controls
		if ($page["count"] < 2)
		{
			return TRUE;
		}
		
	//	Begin rendering the pagination controls
		echo "	<div class=\"pageselect\"> \n\n";
	
	//	Offer a form for direct page selection
		echo "		<div class=\"pageselect_left\"> \n\n";
		echo "			<form method=\"post\" action=\"".$_SERVER["PHP_SELF"]."?".$page["params"]."\">\n";
		echo "				Jump To Page: \n";
		echo "				<input type=\"text\" name=\"".$page["name"]."\" size=\"3\">\n";
		echo "				<input type=\"submit\" name=\"page_submit\" value=\"Go!\">\n";
		echo "			</form>\n";
		echo "		</div> \n\n";
		
	//	Browse buttons for page-by-page navigation
		echo "		<div class=\"pageselect_right\"> \n\n";		

		if ($page["curr"] <= 1)
		{
			echo "			<a href=\"#\" title=\"Not Available\"> \n";
			echo "				&lArr; Previous Page \n";
			echo "			</a> \n";
		}
		
		else
		{
			echo "			<a href=\"".$_SERVER["PHP_SELF"]."?".$page["name"]."=".$page["prev"].$page["params"]."\" title=\"Page ".$page["prev"]."\"> \n";
			echo "				&lArr; Previous Page \n";
			echo "			</a> \n";
		}
		
		echo "			&nbsp; &nbsp; | &nbsp; &nbsp; \n";
		echo "			Page ".$page["curr"]." Of ".$page["count"]."\n";
		echo "			&nbsp; &nbsp; | &nbsp; &nbsp; \n";

		if ($page["curr"] < $page["count"])
		{
			echo "			<a href=\"".$_SERVER["PHP_SELF"]."?".$page["name"]."=".$page["next"].$page["params"]."\" title=\"Page ".$page["next"]."\"> \n";
			echo "				Next Page &rArr; \n";
			echo "			</a>\n";
		}
		
		else
		{
			echo "			<a href=\"#\" title=\"Not Available\"> \n";
			echo "				Next Page &rArr; \n";
			echo "			</a>\n";
		}
		
		echo "		</div> \n\n";
		
		echo "	</div> \n\n";
	}
	
//	This function is used to force a page reload when required
	function spectra_site_return ()
	{
		header ("HTTP/1.1 302 Found");
		header ("Location: ".$_SERVER["PHP_SELF"]);
	}

//	This function is used to render an error page when the site
//	is able to trap a fatal error
	function spectra_page_error ($title, $message, $code="")
	{
		echo "<!DOCTYPE html> \n";
		echo "<html> \n";
		echo "<head> \n";
		
		echo "	<title>".$title."</title> \n";

		echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"".$GLOBALS["url"]["theme"]."spectra.css\"> \n";		
		
		echo "</head> \n";
		echo "<body> \n";
		echo "<div id=\"page_wrap\"> \n";
		
		echo "	<h2>".$title."</h2> \n";

		if ($code != "")
		{
			echo "	<p> \n";
			echo "		Error Code: ".$code." \n";
			echo "	</p> \n";
		}

		echo "	<p> \n";
		echo "		".$message." \n";
		echo "	</p> \n";
		
		echo "</div> \n";
		echo "</body> \n";
		echo "</html>";
		exit;
	}
	
/******************************************************************************
	Shared Detail Elements
******************************************************************************/

//	Detail wrapper for a full line detail	
	function spectra_detail_1 ($label, $value)
	{
		echo "		<div class=\"detail_1\"> \n\n";

		echo "			<div class=\"detail_1_head\"> \n";
		echo "				".$label.": \n";
		echo "			</div> \n\n";

		echo "			<div class=\"detail_1_data\"> \n";
		echo "				".$value." \n";
		echo "			</div> \n\n";

		echo "		</div> \n\n";
	}
	
//	Detail wrapper for a half line detail	
	function spectra_detail_2 ($label, $value)
	{
		echo "		<div class=\"detail_2\"> \n\n";

		echo "			<div class=\"detail_2_head\"> \n";
		echo "				".$label.": \n";
		echo "			</div> \n\n";

		echo "			<div class=\"detail_2_data\"> \n";
		echo "				".$value." \n";
		echo "			</div> \n\n";

		echo "		</div> \n\n";
	}
	
//	Detail wrapper for a 3 details per line	
	function spectra_detail_3 ($label, $value)
	{
		echo "		<div class=\"detail_3\"> \n\n";

		echo "			<div class=\"detail_3_head\"> \n";
		echo "				".$label.": \n";
		echo "			</div> \n\n";

		echo "			<div class=\"detail_3_data\"> \n";
		echo "				".$value." \n";
		echo "			</div> \n\n";

		echo "		</div> \n\n";
	}
	
//	Prepare a link for use within a detail wrapper	
	function spectra_detail_link ($target, $title, $text)
	{
		$link = "			<a href=\"".$target."\" title=\"".$title."\"> \n";
		$link .= "				".$text.": \n";
		$link .= "			</a> \n";
		
		return $link;
	}
	
/******************************************************************************
	Block Browser Layout
******************************************************************************/
	
	function spectra_front_stats ()
	{
		echo "	<div class=\"front_stat_frame\"> \n";
		echo "	<div class=\"front_stat_wrap\"> \n\n";
		
		$network_info = getinfo ();

		if (isset ($network_info["blocks"]) && $network_info["blocks"] != "")
		{
			echo "		<div class=\"front_stat_item\"> \n";
			echo "			<div class=\"front_stat_label\"> \n";
			echo "				Current Block \n";
			echo "			</div> \n";
			echo "			<div class=\"front_stat_value\"> \n";
			echo "				".$network_info["blocks"]." \n";
			echo "			</div> \n";
			echo "		</div> \n\n";
		}
		
		if (isset ($network_info["difficulty"]) && $network_info["difficulty"] != "")
		{
			echo "		<div class=\"front_stat_item\"> \n";
			echo "			<div class=\"front_stat_label\"> \n";
			echo "				 Difficulty \n";
			echo "			</div> \n";
			echo "			<div class=\"front_stat_value\"> \n";
			echo "				".$network_info["difficulty"]." \n";
			echo "			</div> \n";
			echo "		</div> \n\n";
		}
		
		else
		{
			echo "		<div class=\"front_stat_item\"> \n";
			echo "			<div class=\"front_stat_label\"> \n";
			echo "				 Difficulty \n";
			echo "			</div> \n";
			echo "			<div class=\"front_stat_value\"> \n";
			echo "				Not Available \n";
			echo "			</div> \n";
			echo "		</div> \n\n";
		}
		
		echo "		<div class=\"front_stat_item\"> \n";
		echo "			<div class=\"front_stat_label\"> \n";
		echo "				 Money Supply \n";
		echo "			</div> \n";
		echo "			<div class=\"front_stat_value\"> \n";
		echo "				".spectra_money_supply ()." \n";
		echo "			</div> \n";
		echo "		</div> \n\n";
		
		$net_speed = getnetworkhashps ();
		
		if ($net_speed != "" && !is_array ($net_speed))
		{
			echo "		<div class=\"front_stat_item\"> \n";
			echo "			<div class=\"front_stat_label\"> \n";
			echo "				 Network H/s \n";
			echo "			</div> \n";
			echo "			<div class=\"front_stat_value\"> \n";
			echo "				".$net_speed." \n";
			echo "			</div> \n";
			echo "		</div> \n\n";
		}
		
		else
		{
			echo "		<div class=\"front_stat_item\"> \n";
			echo "			<div class=\"front_stat_label\"> \n";
			echo "				 Network H/s \n";
			echo "			</div> \n";
			echo "			<div class=\"front_stat_value\"> \n";
			echo "				Not Available \n";
			echo "			</div> \n";
			echo "		</div> \n\n";
		}
		
		echo "	</div> \n";
		echo "	</div> \n\n";
		
	}
	
	function spectra_block_browser ($per_page)
	{
	//	The values required to paginate are calculated
		$page = spectra_page_describe ("block_page", spectra_block_height (), 20);
		
	//	The list of blocks for this page is retrieved
		$block_list = mysqli_getset ($GLOBALS["tables"]["block"], "TRUE ORDER BY `height` DESC LIMIT ".$page["per"]." OFFSET ".$page["offset"]);
		
	//	Display header for the block browser
		echo "	<h1> ".$GLOBALS["currency"]["name"]." Block Explorer </h1> \n\n";
		
	//	Wrapper for the block browser
		echo "	<div class=\"panel_wide\"> \n\n";

	//	Browsing Controls
		spectra_page_control ($page);

	//	Wrapper for the block list
		echo "		<div class=\"block_browse\"> \n\n";
		
	//	Header row for the block list
		echo "			<div class=\"blocklist_head\"> \n\n";

		echo "				<div class=\"blocklist_head_height\"> \n";
		echo "					Height \n";
		echo "				</div> \n";

		echo "				<div class=\"blocklist_head_flag\"> \n";
		echo "					Type \n";
		echo "				</div> \n";

		echo "				<div class=\"blocklist_head_age\"> \n";
		echo "					Age \n";
		echo "				</div> \n";

		echo "				<div class=\"blocklist_head_tx\"> \n";
		echo "					TX \n";
		echo "				</div> \n";

		echo "				<div class=\"blocklist_head_value\"> \n";
		echo "					".$GLOBALS["currency"]["code"]." Value \n";
		echo "				</div> \n";

		echo "				<div class=\"blocklist_head_diff\"> \n";
		echo "					Difficulty \n";
		echo "				</div> \n";

		echo "				<div class=\"blocklist_head_size\"> \n";
		echo "					Size (Bytes) \n";
		echo "				</div> \n";

		echo "			</div> \n\n";

	//	Render the block list
		foreach ($block_list["data"] as $block_data)
		{
		//	Enable alternating row colors
			if (isset ($blktype) && $blktype == "even")
			{
			//	Odd row type
				echo "			<div class=\"blocklist_block_odd\"> \n\n";
			
			//	Reset the row type for the next row
				$blktype = "odd";				
			}
			
			else
			{
			//	Odd row type
				echo "			<div class=\"blocklist_block_even\"> \n\n";
			
			//	Reset the row type for the next row
				$blktype = "even";				
			}

			echo "				<div class=\"blocklist_data_height\"> \n";
			echo "					<a href=\"block.php?height=".$block_data["height"]."\" title=\"View Block ".$block_data["height"]."\"> \n";
			echo "						".$block_data["height"]." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"blocklist_data_flag\"> \n";
			echo "					<a href=\"block.php?height=".$block_data["height"]."\" title=\"View Block ".$block_data["height"]."\"> \n";

			if (strcasecmp (substr ($block_data["flags"], 0, 14), "proof-of-stake") == 0)
			{
				echo "						POS \n";
			}

			else
			{
				echo "						POW \n";
			}

			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"blocklist_data_age\"> \n";
			echo "					<a href=\"block.php?hash=".$block_data["hash"]."\" title=\"View Block ".$block_data["hash"]."\"> \n";
			echo "						".floor ((time () - $block_data["time"]) / 60)." Mins\n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"blocklist_data_tx\"> \n";
			echo "					<a href=\"block.php?hash=".$block_data["hash"]."\" title=\"View Block ".$block_data["hash"]."\"> \n";
			echo "						".spectra_block_txcount ($block_data["hash"])." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"blocklist_data_value\"> \n";
			echo "					<a href=\"block.php?hash=".$block_data["hash"]."\" title=\"View Block ".$block_data["hash"]."\"> \n";
			echo "						".$block_data["val_out"]." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"blocklist_data_diff\"> \n";
			echo "					<a href=\"block.php?hash=".$block_data["hash"]."\" title=\"View Block ".$block_data["hash"]."\"> \n";
			echo "						".$block_data["difficulty"]." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"blocklist_data_size\"> \n";
			echo "					<a href=\"block.php?hash=".$block_data["hash"]."\" title=\"View Block ".$block_data["hash"]."\"> \n";
			echo "						".$block_data["size"]." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "			</div> \n\n";
		}
	
	//	Close the block list wrapper
		echo "		</div> \n\n";

	//	Browsing Controls
		spectra_page_control ($page);

	//	Close the block browser wrapper 
		echo "	</div> \n\n";

	}
	
/******************************************************************************
	Ledger Browser Layout
******************************************************************************/
	
	function spectra_ledger_browser ($page_per)
	{
	//	The values required to paginate are calculated
		$page = spectra_page_describe ("ledger_page", spectra_address_count (), 20);
		
	//	The data for this page is retrieved
		$address_list = mysqli_getset ($GLOBALS["tables"]["ledger"], "TRUE ORDER BY `balance` DESC LIMIT ".$page["per"]." OFFSET ".$page["offset"]);
	
	//	Display header for the ledger browser
		echo "<h1> ".$GLOBALS["currency"]["name"]." Ledger Browser </h1> \n";	
	
	//	Wrapper for the ledger browser
		echo "	<div class=\"panel_wide\"> \n\n";

	//	Browsing Controls
		spectra_page_control ($page);

	//	Wrapper for the address list
		echo "		<div class=\"ledger_browse\"> \n\n";
		
	//	Render a header row for the display
		echo "			<div class=\"ledger_head\"> \n\n";

		echo "				<div class=\"ledger_head_address\"> \n";
		echo "					Address \n";
		echo "				</div> \n";

		echo "				<div class=\"ledger_head_owner\"> \n";
		echo "					Owner \n";
		echo "				</div> \n";

		echo "				<div class=\"ledger_head_balance\"> \n";
		echo "					Balance (".$GLOBALS["currency"]["code"].") \n";
		echo "				</div> \n";

		echo "				<div class=\"ledger_head_txin\"> \n";
		echo "					TX In \n";
		echo "				</div> \n";

		echo "				<div class=\"ledger_head_txout\"> \n";
		echo "					TX Out \n";
		echo "				</div> \n";

		echo "			</div> \n\n";

	//	Render the address details
		foreach ($address_list["data"] as $address_data)
		{
		//	Enable alternating row colors
			if (isset ($addtype) && $addtype == "even")
			{
			//	Odd row type
				echo "			<div class=\"ledger_block_odd\"> \n\n";
			
			//	Reset the row type for the next row
				$addtype = "odd";				
			}
			
			else
			{
			//	Odd row type
				echo "			<div class=\"ledger_block_even\"> \n\n";
			
			//	Reset the row type for the next row
				$addtype = "even";				
			}

			echo "				<div class=\"ledger_data_address\"> \n";
			echo "					<a href=\"address.php?address=".$address_data["address"]."\" title=\"History For ".$address_data["address"]."\"> \n";
			echo "						".$address_data["address"]." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"ledger_data_owner\"> \n";

			if ($address_data["owner"] != "")
			{
				echo "					".$address_data["owner"]." \n";
			}

			else
			{
				echo "					<a href=\"owner.php?address=".$address_data["address"]."\" title=\"Claim ".$address_data["address"]."\"> \n";
				echo "						Click To Claim \n";
				echo "					</a> \n";
			}

			echo "				</div> \n";

			echo "				<div class=\"ledger_data_balance\"> \n";
			echo "					<a href=\"address.php?address=".$address_data["address"]."\" title=\"History For ".$address_data["address"]."\"> \n";
			echo "						".$address_data["balance"]." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"ledger_data_txin\"> \n";
			echo "					<a href=\"address.php?address=".$address_data["address"]."\" title=\"History For ".$address_data["address"]."\"> \n";
			echo "						".$address_data["tx_in"]." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "				<div class=\"ledger_data_txout\"> \n";
			echo "					<a href=\"address.php?address=".$address_data["address"]."\" title=\"History For ".$address_data["address"]."\"> \n";
			echo "						".$address_data["tx_out"]." \n";
			echo "					</a> \n";
			echo "				</div> \n";

			echo "			</div> \n\n";
		}
	
	//	Close the address list wrapper
		echo "		</div> \n\n";

	//	Browsing Controls
		spectra_page_control ($page);

	//	Close the ledger wrapper
		echo "	</div> \n\n";
	}
	
	function spectra_ledger_statistics ()
	{
		echo "		<h1> ".$GLOBALS["currency"]["name"]." Distribution </h1> \n\n";

		echo "		<div class=\"ledger_panel\"> \n\n";

		echo "			<div class=\"ledger_detail\">\n";
		echo "				<div class=\"ledger_detail_label\"> \n";
		echo "					Money Supply: \n";
		echo "				</div> \n";
		echo "				<div class=\"ledger_detail_value\"> \n";
		echo "					".spectra_money_supply ()."\n";
		echo "					<br>\n";
		echo "					&nbsp; \n";
		echo "				</div> \n";
		echo "			</div> \n\n";

		echo "			<div class=\"ledger_detail\">\n";
		echo "				<div class=\"ledger_detail_label\"> \n";
		echo "					Address Count: \n";
		echo "				</div> \n";
		echo "				<div class=\"ledger_detail_value\"> \n";
		echo "					".spectra_address_count ()."\n";
		echo "					<br>\n";
		echo "					&nbsp; \n";
		echo "				</div> \n";
		echo "			</div> \n\n";

		$top10 = spectra_money_top10 ();

		echo "			<div class=\"ledger_detail\">\n";
		echo "				<div class=\"ledger_detail_label\"> \n";
		echo "					Top 10: \n";
		echo "				</div> \n";
		echo "				<div class=\"ledger_detail_value\"> \n";
		echo "					".$top10["total"]."\n";
		echo "					<br>\n";
		echo "					".$top10["percent"]." %\n";
		echo "				</div> \n";
		echo "			</div> \n\n";

		$top100 = spectra_money_top100 ();

		echo "			<div class=\"ledger_detail\">\n";
		echo "				<div class=\"ledger_detail_label\"> \n";
		echo "					11 - 100: \n";
		echo "				</div> \n";
		echo "				<div class=\"ledger_detail_value\"> \n";
		echo "					".$top100["total"]."\n";
		echo "					<br>\n";
		echo "					".$top100["percent"]." %\n";
		echo "				</div> \n";
		echo "			</div> \n\n";

		$top1000 = spectra_money_top1000 ();

		echo "			<div class=\"ledger_detail\">\n";
		echo "				<div class=\"ledger_detail_label\"> \n";
		echo "					101 - 1000: \n";
		echo "				</div> \n";
		echo "				<div class=\"ledger_detail_value\"> \n";
		echo "					".$top1000["total"]."\n";
		echo "					<br>\n";
		echo "					".$top1000["percent"]." %\n";
		echo "				</div> \n";
		echo "			</div> \n\n";

		echo "		</div> \n\n";
	}
	
/******************************************************************************
	Network Information Layout
******************************************************************************/

	function spectra_network_statistics ()
	{
		echo "	<h1> Network Statistics </h1> \n\n";

		echo "	<div id=\"network_panel\">\n\n";

		$network_info = getinfo ();

		if (isset ($network_info["blocks"]) && $network_info["blocks"] != "")
		{
			echo "		<div class=\"network_detail\">\n";
			echo "			<div class=\"network_detail_label\"> \n";
			echo "				Block Count: \n";
			echo "			</div> \n";
			echo "			<div class=\"network_detail_value\"> \n";
			echo "				".$network_info["blocks"]."\n";
			echo "			</div> \n";
			echo "		</div> \n\n";
		}
		
		if (isset ($network_info["difficulty"]) && $network_info["difficulty"] != "")
		{
			echo "		<div class=\"network_detail\">\n";
			echo "			<div class=\"network_detail_label\"> \n";
			echo "				Difficulty: \n";
			echo "			</div> \n";
			echo "			<div class=\"network_detail_value\"> \n";
			echo "				".$network_info["difficulty"]."\n";
			echo "			</div> \n";
			echo "		</div> \n\n";
		}
		
		if (isset ($network_info["connections"]) && $network_info["connections"] != "")
		{
			echo "		<div class=\"network_detail\">\n";
			echo "			<div class=\"network_detail_label\"> \n";
			echo "				Connections: \n";
			echo "			</div> \n";
			echo "			<div class=\"network_detail_value\"> \n";
			echo "				".$network_info["connections"]."\n";
			echo "			</div> \n";
			echo "		</div> \n\n";
		}
		
		$net_speed = getnetworkhashps ();
		
		if ($net_speed != "" && !is_array ($net_speed))
		{
			echo "		<div class=\"network_detail\">\n";
			echo "			<div class=\"network_detail_label\"> \n";
			echo "				Network H/s: \n";
			echo "			</div> \n";
			echo "			<div class=\"network_detail_value\"> \n";
			echo "				".$net_speed."\n";
			echo "			</div> \n";
			echo "		</div>\n\n";
		}

	//	Sort out the time of the last block in the node
		$node_height = getblockcount ();
		$node_hash = getblockhash ($node_height);
		$node_block = getblock ($node_hash);

		if (!is_numeric ($node_block["time"]))
		{
			$node_block["time"] = strtotime ($node_block["time"]);
		}
		
	//	Calculate the time since the last block
		$last_time = floor ((time() - $node_block["time"]) / 60);

		echo "		<div class=\"network_detail\">\n";
		echo "			<div class=\"network_detail_label\"> \n";
		echo "				Last Block: \n";
		echo "			</div> \n";
		echo "			<div class=\"network_detail_value\"> \n";
		echo "				".$last_time." Minutes\n";
		echo "			</div> \n";
		echo "		</div> \n\n";

		echo "	</div>\n\n";

	}
	
	function spectra_network_nodelist ()
	{
		echo "	<h1> Active Connections </h1> \n\n";

		echo "	<div id=\"network_node_list\">\n\n";

		$node_list = getpeerinfo ();

		foreach ($node_list as $node)
		{
			echo "		<div class=\"node_description\"> \n\n";

			echo "			<div class=\"node_desc_head\"> \n";
			echo "				<span class=\"node_desc_label\">Address:</span> ".$node["addr"]." &nbsp;&nbsp; \n";
			echo "				<span class=\"node_desc_label\">Version:</span> ".$node["version"]." &nbsp;&nbsp; \n";
			echo "				<span class=\"node_desc_label\">Sub Version:</span> ".$node["subver"]." &nbsp;&nbsp; \n";
			echo "				<span class=\"node_desc_label\">Service Level:</span> ".$node["services"]." \n";		
			echo "			</div> \n\n";

			echo "			<div class=\"node_desc_body\"> \n\n";

			if (isset ($node["height"]) && $node["height"] != "")
			{
				echo "				<span class=\"node_desc_label\">Block Height:</span> ".$node["height"]." \n";
			}
			
			if (isset ($node["startingheight"]) && $node["startingheight"] != "")
			{
				echo "				<span class=\"node_desc_label\">Starting Height:</span> ".$node["startingheight"]." \n";
			}
			
			if (isset ($node["blocksrequested"]) && $node["blocksrequested"] != "")
			{
				echo "				<span class=\"node_desc_label\">Blocks Requested:</span> ".$node["blocksrequested"]." \n";
			}
			
			echo "				<br><br> \n\n";

			if (isset ($node["lastsend"]) && $node["lastsend"] != "")
			{
				echo "				<span class=\"node_desc_label\">Last Send:</span> ".date (DATE_RSS, $node["lastsend"])." &nbsp;&nbsp; \n";
			}
			
			if (isset ($node["bytessent"]) && $node["bytessent"] != "")
			{
				echo "				<span class=\"node_desc_label\">Bytes Sent:</span> ".$node["bytessent"]." \n";
			}
			
			echo "				<br><br> \n\n";

			if (isset ($node["lastrecv"]) && $node["lastrecv"] != "")
			{
				echo "				<span class=\"node_desc_label\">Last Receive:</span> ".date (DATE_RSS, $node["lastrecv"])." &nbsp;&nbsp; \n";
			}
			
			if (isset ($node["bytesrecv"]) && $node["bytesrecv"] != "")
			{
				echo "				<span class=\"node_desc_label\">Bytes Received:</span> ".$node["bytesrecv"]." \n";
			}
			
			echo "				<br><br> \n\n";

			if (isset ($node["conntime"]) && $node["conntime"] != "")
			{
				echo "				<span class=\"node_desc_label\">Connected Since:</span> ".date (DATE_RSS, $node["conntime"])." &nbsp;&nbsp; \n";
			}
			
			if (isset ($node["banscore"]) && $node["banscore"] != "")
			{
				echo "				<span class=\"node_desc_label\">Ban Score:</span> ".$node["banscore"]." \n";
			}
			
			echo "				<br> \n\n";

			echo "			</div> \n\n";

			echo "		</div> \n\n";
		}

		echo "	</div>\n\n";

	}
	
/******************************************************************************
	Market Information Layout
******************************************************************************/
	
	function spectra_ticker_panel ($market)
	{
	//	Format the funciton call
		$func_name = "spectra_ticker_".$market["exch_id"];
		
	//	Retrieve the exchange data
		$data = $func_name ($market);
		
	//	Format the data and output it to the browser
		echo "	<div class=\"spectra_ticker_panel\"> \n";
		echo "	 	<div class=\"ticker_panel_head\"> \n";
		echo "	 		".$market["exch_display"]." ".$market["mkt_display"]." \n";
		echo "	 	</div> \n\n";
		
		foreach ($data as $label => $value)
		{
			echo "	 	<div class=\"ticker_panel_data\"> \n";
			echo "	 		<span class=\"ticker_panel_label\">".$label.":</span> \n";
			echo "	 		<span class=\"ticker_panel_value\">".$value."</span> \n";
			echo "	 	</div> \n\n";
		}
		
		echo "	</div> \n\n";
	}
	
	function spectra_market_panel ($mkt_index)
	{
	//	Define the exchange specific function name	
		$func_name = "spectra_market_".$GLOBALS["markets"][$mkt_index]["exch_id"];
	
	//	Retrieve the market information
		$data = $func_name ($GLOBALS["markets"][$mkt_index]);
	
	//	Render the market Bid orders		
		echo "		<div class=\"market_panel_bid_wrap\"> \n";
		echo "		<div class=\"market_panel_bid_head\"> \n\n";
		
		echo "			<div class=\"market_panel_head\">\n";
		echo "				".$GLOBALS["markets"][$mkt_index]["exch_display"]." ".$GLOBALS["markets"][$mkt_index]["mkt_display"]." Buy Orders \n";
		echo "			</div> \n\n";

		echo "			<div class=\"market_panel_bid_order\"> \n\n";

		echo "				<div class=\"market_panel_bid_price\"> \n";
		echo "					Price \n";
		echo "				</div> \n\n";

		echo "				<div class=\"market_panel_bid_quantity\"> \n";
		echo "					Quantity \n";
		echo "				</div> \n\n";

		echo "				<div class=\"market_panel_bid_total\"> \n";
		echo "					Total \n";
		echo "				</div> \n\n";

		echo "			</div> \n\n";

		echo "		</div> \n\n";
		
		echo "		<div class=\"market_panel_bid_list\"> \n\n";
		
		if (!isset ($data["orders"]["bid"]) || !is_array ($data["orders"]["bid"]))
		{
			echo "			<div class=\"market_panel_bid_order\"> \n";
			echo"				<p>Not Available</p> \n";
			echo "			</div> \n\n";
		}
		
		else
		{
			foreach ($data["orders"]["bid"] as $order)
			{
				echo "			<div class=\"market_panel_bid_order\"> \n\n";
	
				echo "				<div class=\"market_panel_bid_price\"> \n";
				echo "					".$order["price"]." \n";
				echo "				</div> \n\n";
		
				echo "				<div class=\"market_panel_bid_quantity\"> \n";
				echo "					".$order["quantity"]." \n";
				echo "				</div> \n\n";
		
				echo "				<div class=\"market_panel_bid_total\"> \n";
				echo "					".$order["total"]." \n";
				echo "				</div> \n\n";
	
				echo "			</div> \n\n";
			}
		}
		
		echo "		</div> \n";
		echo "		</div> \n\n";

	//	Render the market ask orders		
		echo "		<div class=\"market_panel_ask_wrap\"> \n";
		echo "		<div class=\"market_panel_ask_head\"> \n\n";
		
		echo "			<div class=\"market_panel_head\">\n";
		echo "				".$GLOBALS["markets"][$mkt_index]["exch_display"]." ".$GLOBALS["markets"][$mkt_index]["mkt_display"]." Sell Orders \n";
		echo "			</div> \n\n";

		echo "			<div class=\"market_panel_ask_order\"> \n\n";

		echo "				<div class=\"market_panel_ask_price\"> \n";
		echo "					Price \n";
		echo "				</div> \n\n";

		echo "				<div class=\"market_panel_ask_quantity\"> \n";
		echo "					Quantity \n";
		echo "				</div> \n\n";

		echo "				<div class=\"market_panel_ask_total\"> \n";
		echo "					Total \n";
		echo "				</div> \n\n";

		echo "			</div> \n\n";

		echo "		</div> \n\n";
		
		echo "		<div class=\"market_panel_ask_list\"> \n\n";
		
		if (!isset ($data["orders"]["ask"]) || !is_array ($data["orders"]["ask"]))
		{
			echo "			<div class=\"market_panel_ask_order\"> \n";
			echo"				<p>Not Available</p> \n";
			echo "			</div> \n\n";
		}
		
		else
		{
			foreach ($data["orders"]["ask"] as $order)
			{
				echo "			<div class=\"market_panel_ask_order\"> \n\n";
	
				echo "				<div class=\"market_panel_ask_price\"> \n";
				echo "					".$order["price"]." \n";
				echo "				</div> \n\n";
		
				echo "				<div class=\"market_panel_ask_quantity\"> \n";
				echo "					".$order["quantity"]." \n";
				echo "				</div> \n\n";
		
				echo "				<div class=\"market_panel_ask_total\"> \n";
				echo "					".$order["total"]." \n";
				echo "				</div> \n\n";
	
				echo "			</div> \n\n";
			}
		}
		
		echo "		</div> \n";
		echo "		</div> \n\n";

	//	Render a header for the trade history 
		echo "		<div class=\"market_panel_history_head\"> \n\n";
		
		echo "			<div class=\"market_panel_head\">\n";
		echo "				".$GLOBALS["markets"][$mkt_index]["exch_display"]." ".$GLOBALS["markets"][$mkt_index]["mkt_display"]." Trade History \n";
		echo "			</div> \n\n";

		echo "			<div class=\"market_panel_trade\"> \n\n";		
			
		echo "				<div class=\"market_panel_trade_type\"> \n";		
		echo "					Type \n";		
		echo "				</div> \n\n";		

		echo "				<div class=\"market_panel_trade_quantity\"> \n";		
		echo "					Quantity \n";		
		echo "				</div> \n\n";		

		echo "				<div class=\"market_panel_trade_price\"> \n";		
		echo "					Price \n";		
		echo "				</div> \n\n";		

		echo "				<div class=\"market_panel_trade_total\"> \n";		
		echo "					Total \n";		
		echo "				</div> \n\n";		

		echo "				<div class=\"market_panel_trade_time\"> \n";		
		echo "					Trade Time \n";		
		echo "				</div> \n\n";		

		echo "			</div> \n\n";		

		echo "		</div> \n\n";
	
	//	Generate the list of tredes
		echo "		<div class=\"market_panel_history_list\"> \n\n";

		if (!isset ($data["history"]) || !is_array ($data["history"]))
		{
			echo "			<div class=\"market_panel_trade\"> \n";		
			echo "				<p>Not Available</p> \n";		
			echo "			</div> \n\n";		
		}
		
		else
		{
			foreach ($data["history"] as $trade)
			{
				echo "			<div class=\"market_panel_trade\"> \n\n";		
				
				echo "				<div class=\"market_panel_trade_type\"> \n";		
				echo "					".$trade["type"]." \n";		
				echo "				</div> \n\n";		
	
				echo "				<div class=\"market_panel_trade_quantity\"> \n";		
				echo "					".$trade["quantity"]." \n";		
				echo "				</div> \n\n";		
	
				echo "				<div class=\"market_panel_trade_price\"> \n";		
				echo "					".$trade["price"]." \n";		
				echo "				</div> \n\n";		
	
				echo "				<div class=\"market_panel_trade_total\"> \n";		
				echo "					".$trade["total"]." \n";		
				echo "				</div> \n\n";		
	
				echo "				<div class=\"market_panel_trade_time\"> \n";		
				echo "					".$trade["time"]." \n";		
				echo "				</div> \n\n";		
	
				echo "			</div> \n\n";		
			}
		}
		
		echo "		</div> \n\n";
	}

/******************************************************************************
	Block Detail Layout
******************************************************************************/
	
	function spectra_block_detail ($block)
	{
		echo "	<h1> ".$GLOBALS["currency"]["name"]." Block Details </h1> \n\n";

		echo "	<div class=\"panel_wide\"> \n\n";

		echo "		<h2> Block Description </h2> \n\n";

	//	Render the raw block data viewer
		echo "		<div class=\"raw_block_wrap\"> \n";
		echo "			<a href=\"javascript: block_raw_pop ('".$block["hash"]."')\" class=\"raw_block_click\" id=\"raw_block_click\" name=\"raw_block_click\" > \n";
		echo "				Click Here To Load Raw Block Data\n";
		echo "			</a> \n";
		echo "			<div class=\"raw_block_display\" name=\"raw_block_display\" id=\"raw_block_display\"> \n";
		echo "			</div> \n";
		echo "		</div> \n\n";

	//	Render the detailed block description
		echo "		<div class=\"detail_row\"> \n";

		spectra_detail_1 ("Block Hash", $block["hash"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_3 ("Block Height", $block["height"]);

		spectra_detail_3 ("Block Size", $block["size"]);

		spectra_detail_3 ("Block Version", $block["version"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_1 ("Merkle Root", $block["merkleroot"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_2 ("Block Time", date ("m/d/Y H:i:s", $block["time"]));

		spectra_detail_2 ("Difficulty", $block["difficulty"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_2 ("Block Bits", $block["bits"]);

		spectra_detail_2 ("Block Nonce", $block["nonce"]);

		echo "		</div> \n\n";

		if (strcasecmp (substr ($block["flags"], 0, 14), "proof-of-stake") == 0)
		{
			echo "		<div class=\"detail_row\"> \n\n";

			spectra_detail_1 ("Proof Hash", $block["proofhash"]);

			echo "		</div> \n\n";

			echo "		<div class=\"detail_row\"> \n\n";

			spectra_detail_3 ($GLOBALS["currency"]["code"]." Minted", $block["mint"]);

			spectra_detail_3 ("Block Flags", "proof-of-stake");
			
			if ($block["entropybit"] == "")
			{
				spectra_detail_3 ("Entropy Bit", "N/A");
			}
			
			else
			{
				spectra_detail_3 ("Entropy Bit", $block["entropybit"]);
			}
			
			echo "		</div> \n\n";

			if ($block["signature"] != "")
			{
				echo "		<div class=\"detail_row\"> \n\n";
	
				spectra_detail_1 ("Signature", $block["signature"]);
	
				echo "		</div> \n\n";
			}
			
			echo "		<div class=\"detail_row\"> \n\n";

			spectra_detail_2 ("Modifier", $block["modifier"]);

			spectra_detail_2 ("Modifier Checksum", $block["modifierchecksum"]);

			echo "		</div> \n\n";
		}

		echo "		<div class=\"detail_row\"> \n\n";

		$target = spectra_detail_link ("block.php?hash=".$block["previousblockhash"], "Previous Block Detail", $block["previousblockhash"]);
		spectra_detail_1 ("Previous Block Hash", $target);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		if ($block["nextblockhash"] == "Pending")
		{
			$target = "			".$block["nextblockhash"]." \n";
		}

		else
		{
			$target = spectra_detail_link ("block.php?hash=".$block["nextblockhash"], "Next Block Detail", $block["nextblockhash"]);
		}

		spectra_detail_1 ("Next Block Hash", $target);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_3 ("Input Value", $block["val_in"]);

		spectra_detail_3 ("Output Value", $block["val_out"]);

		spectra_detail_3 ("Fee Value", $block["val_fee"]);

		echo "		</div> \n\n";

		echo "	</div> \n\n";

		echo "	<div class=\"panel_wide\"> \n\n";

	//	Decode the tx list
		$tx_list = json_decode ($block["tx"], 1);

	//	Render transaction flow for each tx in the list
		echo "		<h2> Block Transactions </h2> \n\n";

		foreach ($tx_list as $transaction)
		{
			spectra_block_tx ($transaction);
		}

		echo "	</div> \n\n";
	}

	function spectra_block_tx ($txid)
	{
	//	Retrieve the transaction inputs
		$tx_vin = mysqli_getset ($GLOBALS["tables"]["vin"], "`in_tx` = '".$txid."'");
	
	//	Retrieve the transaction outputs
		$tx_vout = mysqli_getset ($GLOBALS["tables"]["vout"], "`in_tx` = '".$txid."'");
	
	//	Render the transaction data for the page
		echo "	<div class=\"block_tx_wrap\"> \n\n";

		echo "		<div class=\"block_tx_head\"> \n";
		echo "			TX ID: <a href=\"tx.php?tx=".$txid."\" title=\"Transaction Details\"> \n";
		echo "				".$txid." \n";
		echo "			</a> \n";
		echo "		</div> \n\n";
	
	//	List and sum transaction inputs
		echo "		<div class=\"block_tx_in\"> \n";
		$sum_in = 0;
		
		foreach ($tx_vin["data"] as $input)
		{
		//	Render the page layout
			echo "			<div class=\"block_tx_amt_in\"> \n";
			
			if ($input["src_address"] == "Generated")
			{
				echo "				Generated <br>\n";
			}
			
			else
			{
				$addr = json_decode ($input["src_address"], 1);	
				
				foreach ($addr as $address)
				{
					echo "				<a href=\"address.php?address=".$address."\" title=\"Address Details\"> \n";
					echo "					".$address." \n";
					echo "				</a> <br>\n";
				}
			}
			
			echo "				".$input["src_value"]." \n";
			echo "			</div> \n";

		//	Update the transaction input amount
			$sum_in = bcadd ($sum_in, $input["src_value"], 8);
		}
		
	//	Display the total input amount
		echo "			<div class=\"block_tx_sum_in\">\n";
		echo "				Input Value: ".$sum_in." \n";
		echo "			</div> \n";
		
	//	Close the input list wrapper	
		echo "		</div> \n\n";
	
	//	List and sum transaction outputs
		echo "		<div class=\"block_tx_out\"> \n";
		$sum_out = 0;
		
	//	Placeholder a stake modified output
		$modifier = 0;
		
		foreach ($tx_vout["data"] as $output)
		{
			$addr = json_decode ($output["addresses"], 1);
			
			if (strcasecmp ($addr[0], "Stake Modifier") == 0)
			{
				$modifier = 1;
			}
			
			echo "			<div class=\"block_tx_amt_out\"> \n";

			foreach ($addr as $address)
			{
				echo "				<a href=\"address.php?address=".$address."\" title=\"Address Details\"> \n";
				echo "					".$address." \n";
				echo "				</a> <br>\n";
			}

			echo "				".$output["value"]." \n";
			echo "			</div> \n";

		//	Update the transaction input amount
			$sum_out = bcadd ($sum_out, $output["value"], 8);
		}

	//	Classify any additional coins
		if ($modifier == 1)
		{
			echo "			<div class=\"block_tx_sum_mint\">\n";
			echo "				POS Minted: ".bcsub ($sum_out, $sum_in, 8)." \n";
			echo "			</div> \n";
		}
		
		else
		{
			echo "			<div class=\"block_tx_sum_fee\">\n";
			echo "				Fee Value: ".bcsub ($sum_in, $sum_out, 8)." \n";
			echo "			</div> \n";
		}
		
	//	Display the total output amount
		echo "			<div class=\"block_tx_sum_out\">\n";
		echo "				Output Value: ".$sum_out." \n";
		echo "			</div> \n";
		
	//	Close the output list wrapper	
		echo "		</div> \n\n";
	
	//	Close the transaction wrapper
		echo "	</div> \n\n";
	}

/******************************************************************************
	Transaction Detail Layout
******************************************************************************/
	
	function spectra_tx_detail ($tx)
	{
		echo "	<h1> ".$GLOBALS["currency"]["name"]." Transaction Details </h1> \n\n";

		echo "	<div class=\"panel_wide\"> \n\n";

		echo "		<h2> Transaction Description </h2> \n\n";

	//	Render the raw tx data viewer
		echo "		<div class=\"raw_tx_wrap\"> \n";
		echo "			<a href=\"javascript: tx_raw_pop ('".$_REQUEST["tx"]."')\" class=\"raw_tx_click\" id=\"raw_tx_click\" name=\"raw_tx_click\"> \n";
		echo "				Click Here To Load Raw Transaction Data\n";
		echo "			</a> \n";
		echo "			<div class=\"raw_tx_display\" name=\"raw_tx_display\" id=\"raw_tx_display\"> \n";
		echo "			</div> \n";
		echo "		</div> \n\n";

	//	Render the detailed transaction description
		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_1 ("Transaction ID", $tx["txid"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_3 ("Input Value", $tx["val_in"]);

		spectra_detail_3 ("Output Value", $tx["val_out"]);

		spectra_detail_3 ("Fee Value", $tx["val_fee"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		$target = spectra_detail_link ("block.php?hash=".$tx["in_block"], "Block Details", $tx["in_block"]);
		spectra_detail_1 ("Found In Block", $target);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_2 ("TX Version", $tx["version"]);

		spectra_detail_2 ("TX Time", $tx["time"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_2 ("Lock Time", $tx["locktime"]);

		spectra_detail_2 ("Block Time", $tx["blocktime"]);

		echo "		</div> \n\n";

		if ($tx["tx-comment"] != "")
		{
			echo "		<div class=\"detail_row\"> \n\n";

			spectra_detail_1 ("Transaction Comment", $tx["tx-comment"]);

			echo "		</div> \n\n";
		}

		echo "		<hr> \n\n";

		echo "	</div> \n\n";

	//	Render the list of transaction outputs
		echo "	<div class=\"panel_wide\"> \n\n";

		echo "		<h2> Transaction Inputs </h2> \n\n";

	//	Retrieve the transaction inputs
		$vin_data = mysqli_getset ($GLOBALS["tables"]["vin"], "`in_tx` = '".$tx["txid"]."'");

	//	Build the input list
		$list_vin = "";

		foreach ($vin_data["data"] as $vin)
		{
		//	Render based on input type		
			if ($vin["coinbase"] != "")
			{
				echo "		<div class=\"detail_row\"> \n\n";

				spectra_detail_2 ("Coinbase", $vin["coinbase"]);

				spectra_detail_2 ("Sequence", $vin["sequence"]);

				echo "		</div> \n\n";
			}

			else
			{
				echo "		<div class=\"detail_row\"> \n\n";

				$target = spectra_detail_link ("tx.php?tx=".$vin["src_tx"], "Source TX Details", $vin["src_tx"]);
				spectra_detail_1 ("Source Transaction", $target);

				echo "		</div> \n\n";

				echo "		<div class=\"detail_row\"> \n\n";

				spectra_detail_2 ("Source Output", $vin["src_vout"]);

				spectra_detail_2 ("Source Value", $vin["src_value"]);

				echo "		</div> \n\n";

				if ($vin["sequence"] != "")
				{
					echo "		<div class=\"detail_row\"> \n\n";

					spectra_detail_1 ("Sequence", $vin["sequence"]);

					echo "		</div> \n\n";
				}
			}
		}

		echo "	</div> \n\n";

	//	Render the list of transaction outputs
		echo "	<div class=\"panel_wide\"> \n\n";

		echo "		<h2> Transaction Outputs </h2> \n\n";

	//	Retrieve the transaction outputs
		$vout_data = mysqli_getset ($GLOBALS["tables"]["vout"], "`in_tx` = '".$tx["txid"]."'");

	//	Build the output list	
		$list_vout = "";

		foreach ($vout_data["data"] as $vout)
		{
			echo "		<div class=\"detail_row\"> \n\n";

			spectra_detail_2 ("Output ID (N)", $vout["n"]);

			spectra_detail_2 ("Output Value", $vout["value"]);

			echo "		</div> \n\n";

			echo "		<div class=\"detail_row\"> \n\n";

			spectra_detail_2 ("Output Type", $vout["type"]);

			spectra_detail_2 ("Required Signatures", $vout["reqsigs"]);

			echo "		</div> \n\n";

			echo "		<div class=\"detail_row\"> \n\n";

		//	It is possible for an output to target multiple addresses
			$addr = json_decode ($vout["addresses"], 1);
			$list = "";
			
			foreach ($addr as $address)
			{
				$list .= "				<a href=\"address.php?address=".$address."\" title=\"Address Detail\"> \n";
				$list .= "					".$address." \n";
				$list .= "				</a> \n";
				$list .= "				<br><br> \n\n";
			}

			spectra_detail_1 ("Output Addresses", $list);

			echo "		</div> \n\n";
		}

		echo "	</div> \n\n";
	}

/******************************************************************************
	Address Detail Layout
******************************************************************************/
	
	function spectra_address_detail ($address)
	{
		echo "	<h1> ".$GLOBALS["currency"]["name"]." Address Details </h1> \n\n";

		echo "	<div class=\"panel_wide\"> \n\n";

		echo "		<h2> Address Description </h2> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_1 ($GLOBALS["currency"]["name"]." Address", $address["address"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		$target = spectra_detail_link ("block.php?hash=".$address["firstblock"], "Address First Block", $address["firstblock"]);
		spectra_detail_1 ("Found In Block", $target);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_2 ("Incoming TX Count", $address["tx_in"]);

		spectra_detail_2 ("Value Received", $address["received"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_2 ("Outgoing TX Count", $address["tx_out"]);

		spectra_detail_2 ("Value Spent", $address["spent"]);

		echo "		</div> \n\n";

		echo "		<div class=\"detail_row\"> \n\n";

		spectra_detail_1 ("Current ".$GLOBALS["currency"]["code"]." Balance", $address["balance"]);

		echo "		</div> \n\n";

		if ($address["owner"] != "")
		{
			echo "		<div class=\"detail_row\"> \n\n";

			spectra_detail_1 ("Address Claimed By", $address["owner"]);

			echo "		</div> \n\n";
		}

		echo "	</div> \n\n";

	//	Calculate values for the paginated query
		$page_in = spectra_page_describe ("incoming", $address["tx_in"], 10);

	//	Retrieve inputs for this page
		$list_receive = mysqli_getset ($GLOBALS["tables"]["vout"], "`addresses` LIKE '%".$address["address"]."%' ORDER BY `time` DESC LIMIT ".$page_in["per"]." OFFSET ".$page_in["offset"]);

	//	Build the list of incoming transactions for this account
		echo "	<div class=\"panel_wide\"> \n\n";

		echo "	<h2> Incoming Transactions </h2> \n\n";

	//	Page controls for incoming transaction list
		spectra_page_control ($page_in);

	//	Create a header row for the list	
		echo "		<div class=\"address_ledger_head\"> \n\n";

		echo "			<div class=\"address_ledger_date_head\"> \n";
		echo "				Date \n";
		echo "			</div> \n\n";

		echo "			<div class=\"address_ledger_txid_head\"> \n";
		echo "				Transaction ID \n";
		echo "			</div> \n\n";

		echo "			<div class=\"address_ledger_value_head\"> \n";
		echo "				Value \n";
		echo "			</div> \n\n";

		echo "		</div> \n\n";

		echo "		<div class=\"address_ledger_wrap\"> \n\n";

	//	List and link each incoming transaction
		foreach ($list_receive["data"] as $received)
		{
		//	Enable alternating row colors
			if (isset ($intype) && $intype == "even")
			{
			//	Odd row type
				echo "			<div class=\"address_ledger_odd\"> \n\n";
			
			//	Reset the row type for the next row
				$intype = "odd";				
			}
			
			else
			{
			//	Odd row type
				echo "			<div class=\"address_ledger_even\"> \n\n";
			
			//	Reset the row type for the next row
				$intype = "even";				
			}

			echo "				<div class=\"address_ledger_date\"> \n";
			echo "					".date ("m/d/Y H:i:s", $received["time"])." \n";
			echo "				</div> \n\n";

			echo "				<div class=\"address_ledger_txid\"> \n";
			echo "					<a href=\"tx.php?tx=".$received["in_tx"]."\" title=\"Transaction Detail\"> \n";
			echo "						".$received["in_tx"]." \n";
			echo "					</a> \n";
			echo "				</div> \n\n";

			echo "				<div class=\"address_ledger_value\"> \n";
			echo "					".$received["value"]." \n";
			echo "				</div> \n\n";

			echo "			</div> \n\n";
		}

		echo "		</div> \n\n";

		echo "	</div> \n\n";

	//	Calculate values for the paginated query
		$page_out = spectra_page_describe ("outgoing", $address["tx_out"], 10);

	//	Retrieve outgoing transactions from the database
		$list_spent = mysqli_getset ($GLOBALS["tables"]["vin"], "`src_address` LIKE '%".$address["address"]."%' ORDER BY `time` DESC LIMIT ".$page_out["per"]." OFFSET ".$page_out["offset"]);
	
	//	Build the list of outgoing transactions for this account
		echo "	<div class=\"panel_wide\"> \n\n";

		echo "	<h2> Outgoing Transactions </h2> \n\n";

	//	Page controls for incoming transaction list
		spectra_page_control ($page_out);

	//	Create a outgoing row for the list	
		echo "		<div class=\"address_ledger_head\"> \n\n";

		echo "			<div class=\"address_ledger_date_head\"> \n";
		echo "				Date \n";
		echo "			</div> \n\n";

		echo "			<div class=\"address_ledger_txid_head\"> \n";
		echo "				Transaction ID \n";
		echo "			</div> \n\n";

		echo "			<div class=\"address_ledger_value_head\"> \n";
		echo "				Value \n";
		echo "			</div> \n\n";

		echo "		</div> \n\n";

		echo "		<div class=\"address_ledger_wrap\"> \n\n";

	//	List and link each outgoing transaction
		if (!isset ($list_spent["data"]) || $list_spent["data"] == "")
		{
			echo "			<div class=\"address_ledger_odd\"> \n";
			echo "				No Outgoing Transactions\n";
			echo "			</div> \n\n";
		}

		else
		{
			foreach ($list_spent["data"] as $spent)
			{
			//	Enable alternating row colors
				if (isset ($outtype) && $outtype == "even")
				{
				//	Odd row type
					echo "			<div class=\"address_ledger_odd\"> \n\n";

				//	Reset the row type for the next row
					$outtype = "odd";				
				}

				else
				{
				//	Odd row type
					echo "			<div class=\"address_ledger_even\"> \n\n";

				//	Reset the row type for the next row
					$outtype = "even";				
				}

				echo "				<div class=\"address_ledger_date\"> \n";
				echo "					".date ("m/d/Y H:i:s", $spent["time"])." \n";
				echo "				</div> \n\n";

				echo "				<div class=\"address_ledger_txid\"> \n";
				echo "					<a href=\"tx.php?tx=".$spent["in_tx"]."\" title=\"Transaction Detail\"> \n";
				echo "						".$spent["in_tx"]." \n";
				echo "					</a> \n";
				echo "				</div> \n\n";

				echo "				<div class=\"address_ledger_value\"> \n";
				echo "					".$spent["src_value"]." \n";
				echo "				</div> \n\n";

				echo "			</div> \n\n";
			}
		}

		echo "		</div> \n\n";

		echo "	</div> \n\n";
	}
	
/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>