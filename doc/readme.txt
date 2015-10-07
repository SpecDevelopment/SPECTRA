/******************************************************************************
	SPECTRA Block Explorer Installation Guide
******************************************************************************/

Contents:

	1.	Requirements
	2.	Files and Directory Structure
	3.	Configuration
	4.	Relative Paths (Inclusions)
	5.	Installation
	6.	Maintenance
	
	This document describes the steps required to install the SPECTRA
	block explorer on a computer.


/******************************************************************************
	Requirements
******************************************************************************/

	In order to operate the block explorer requires the following:
	
	PHP 5+ with the following extensions:
		cURL
		MySQLI
		Hash
	
	MySQL 5+
	
	Apache 2
	
	Crypto-Currency Wallet/Node with at least:	
		TXIndex Enabled		
		RPC Enabled
		Bitcoin Compatible JSON-RPC Interface

	The script may operate with other versions of these products however
	this is the environment in which it was developed and is intended to
	operate within.
	
	The scripts were developed on Ubuntu Server 14.10 if you are using
	a different linux version there may be minor differences from any
	procedures outlined in this document.

/******************************************************************************
	Files and Directory Structure
******************************************************************************/

	The block explorer consists of 
	
	<Script Root>/ This directory contains the scripts that generate
	the page content for the block explorer website and also the 
	license and other information.
	
		Files:
			install.txt - This file
			license.txt - A copy of the MIT Software License
			spectra.js - Javascript file used by the site
			address.php - 
			api_fetch.php - 
			api_guide.php - 
			block.php - 
			index.php - 
			ledger.php - 
			markets.php - 
			network.php - 
			owner.php - 
			search.php - 
			tx.php - 
	
	<Script Root>/lib/ - contains php libraries used to enable both
	the SPECTRA Block Explorer web site, and the back end.
	
		Files:
			spectra_config.php - This file is used to configure
			the block explorer for operation in your production
			environment.
			
			spectra_lib_layout.php - This file contains code that
			is used to render the front end (web interface) for 
			the block explorer.
			
			spectra_lib_utility.php - This file contains functions
			that support both the front-end rendering and the 
			back-end maintenance of the block explorer.
			
			spectra_wrap_exchange.php - This file contains
			functions that support exchange communications and 
			data normalization for the markets module.
			
			spectra_wrap_mysqli.php - This file contains functions 
			used to work with the php mysqli interface for MySQL.
			
			spectra_wrap_node.php - This file contains functions 
			used for communicating with the block explorer's
			crypto-currency node.
	
	<Script Root>/lib/cache/ - This directory enables cookie-handling
	for the SPECTRA markets module. At distribution it is empty.
		
	<Script Root>/theme/ - This directory will contain one or more
	subdirectories with pre-defined themes for the web site.
	
	<Script Root>/theme/themeless/ - This directory contains files 
	related to the default theme for the SPECTRA Block Explorer.
	
		Files:
			spectra.css - The default stylesheet
			icon_load.gif - Animated "Loading" Icon
			site_foot_html.txt - Default Footer HTML
			site_head_html.txt - Default Header HTML

	<Script Root>/utility/ - This directory contains the utility scripts
	required to set up and maintain the SPECTRA Block Explorer.

		Files:
			maint.crontab.php - This is the primary maintenance
			script which can be configured to run via crontab 
			and keeps the block explorer up to date with the node.
			
			maint_deleteblock.php - This script provides a method
			to manually delete all data associated with a specific 
			block from the block explorer database.
			
			maint_insertblock.php - This script provides a method
			to manually parse a specific block into the database.
			
			maint_rebalance.php - This script is used to rebalance
			the ledger after adding or removing block data from 
			the database.
			
			maint_resetflag.php - Used to force removal of maintenance
			flags during manual maintenance tasks, or after 
			an interruption of scheduled maintenance.
			
			setup_build.php - This script creates the tables
			required by the block explorer..
			
			setup_load.php - This script is used to perform the
			initial block load when installing the block explorer.

	All of these files need to be present for the block explorer to operate
	normally.

/******************************************************************************
	Configuration
******************************************************************************/

	Configuration for the block explorer is performed by editing the
	following files:
	
		<Script Root>/spectra.js
			Line 7: Absolute URI for installed 'block.php'
			Line 25: Absolute URI for installed 'tx.php'
			Line 42: Absolute URI for your selected 'loading' icon				

		<Script Root>/<path to theme directory>/site_foot_html.txt
			This file is where you should place any custom HTML
			code for automatic inclusion into the sitewide page
			footers.

		<Script Root>/<path to theme directory>/site_head_html.txt
			This file is where you should place any custom HTML
			code for automatic inclusion into the sitewide page
			footers.

		<Script Root>/lib/spectra_config.php
			This file is where the majority of the block explorer
			functionality is configured. The file is broken into 
			several sections, each is described here.

			Currency Description:
			=====================
			These values are used to generate text while rendering
			the block explorer web site.  They should contain the 
			full nameof your selected currency (e.g. Spec Coin) and
			the commonly used trading symbol for the coin (e.g. SPEC).
	
			Local Configuration:
			====================
			These values are used to describe the installed location
			of the script files in your production environment. They 
			are divided into two secitons, those with the index 
			["url"] are used to generate links between the various
			pages of the site, and those with the index ["path"]
			are used to generate internal paths used for the 
			various script inclusions required by the block explorer.
			
			If you are using the default directory structures on
			your server, the only values you should need to edit 
			are:
				$GLOBALS["url"]["home"] - The root URI used to
				access the block explorer from a web browser
				
				$GLOBALS["path"]["root"] - The absolute path
				to the script root within your operating 
				system.
				
			If you are not going to use the default theme you 
			can edit the values $GLOBALS["url"]["theme"] and
			$GLOBALS["path"]["theme"] to point to the directory
			containing the files required by your desired theme.
			
			Advanced users can use these values to move the /lib
			and /utility directories outside of the web root if
			that is desired for your installation.
			
			Node Connection:
			================
			These values should be updated with the actual values
			required for connection to the crypto-currency node
			you will be using for the block explorer.
	
			Database Customization:
			=======================
			There are four parameters required for connection to 
			the MySQL server that will be used by the block explorer.
			
			Additionally, there are values here that will be used 
			to reference the six database tables required for the 
			block explorer to operate. If you will be operating more
			than one instance of the block explorer using the same
			database you should replace these table names with 
			unique names for each version of the block explorer you 
			will install.
			
			Market Configuration:
			=====================
			Exchanges supported by this module at the time of the 
			initial release include:
			
				["exch_id"] bittrex		["exch_display"] Bittrex
				["exch_id"] bleutrade	["exch_display"] Bleutrade
				["exch_id"] ccex		["exch_display"] C-Cex
				["exch_id"] cryptopia	["exch_display"] Cryptopia
				["exch_id"] cryptsy		["exch_display"] Cryptsy
				["exch_id"] poloniex	["exch_display"] Poloniex
				["exch_id"] yobit		["exch_display"] YoBit
				
			The ["exch_id"] value is case sensitive and is used to 
			invoke the functions required to communicate with each 
			supported exchange.
			
			The ["exch_display"] value is not case sensitive and is 
			used only to render a display name for the web site and 
			corresponding API.
				
			Each market you select for display on the site requires 
			you to configure a block of five values as described here:
			
				$GLOBALS["markets"][0]["index"] -
				this is a self-referential value, and should be
				updated to contain the numeric index used in the 
				configuration of this market.  This value is used
				to generate links allowing market and ticker
				selection on the markets page of the site.

				$GLOBALS["markets"][0]["exch_id"] -
				This should contain the case-sensitive ID for 
				the exchange that hosts this market as indicated
				in the list above.
				
				$GLOBALS["markets"][0]["exch_display"] -
				This should contain the display name you want 
				used when rendering data from this market on 
				the block explorer web site.
				
				$GLOBALS["markets"][0]["mkt_id"] -
				This should contain the market ID for this market 
				as required by the specific exchange.  Syntax and 
				case-sensitivity cary here, please see the exchange
				API documentation at each exchange for specifics.
				
				$GLOBALS["markets"][0]["mkt_display"] -
				This is the display name that will be used when
				rendering information about this market on the 
				block explorer web site. There are no syntax or
				case-sensitivity requirements for this value.
			

			Here are two sample blocks referencing actual markets:
			
			$GLOBALS["markets"][0]["index"] = 0;
			$GLOBALS["markets"][0]["exch_id"] = "cryptsy";
			$GLOBALS["markets"][0]["exch_display"] = "Cryptsy";
			$GLOBALS["markets"][0]["mkt_id"] = "492";
			$GLOBALS["markets"][0]["mkt_display"] = "SPEC / BTC";

			$GLOBALS["markets"][3]["index"] = 3;
			$GLOBALS["markets"][3]["exch_id"] = "bleutrade";
			$GLOBALS["markets"][3]["exch_display"] = "Bleutrade";
			$GLOBALS["markets"][3]["mkt_id"] = "SPEC_BTC";
			$GLOBALS["markets"][3]["mkt_display"] = "SPEC / BTC";
	
			As youc an see there is a different numeric index used
			in each block/market. These values should be unique to
			each exchange/market you want to display information
			from.
			
			There is no requirement that these indexes be sequential
			as long as each block has a unique index.  There is also
			no limit to the number of markets you can shoose to
			display, although over use of this module may cause issues
			with the exchange depending on traffic to your site.
			
			There is no local caching of this market data as of the
			initial SPECTRA release so if you are seeing a large 
			amount of usage you may encounter rate-limiting or 
			other issues.  Also, the more markets you have enabled
			the more likely you are to experience noticeable page 
			loading lag when visiting the markets page on the site.
				
			Required Modules and Wrappers:
			==============================
			This section of the file generates the inclusions for
			the library and wrapper files required by the block
			explorer.
			
			In most cases there will be no need to edit these paths
			as they reference the library path in the earlier
			section of the file.


	
/******************************************************************************
	Relative Paths / Inclusions
******************************************************************************/

	When you download the block explorer files, each of the pages and
	maintenance scripts will contain a relative path to the file
	spectra_config.php which assumes the default directory structure 
	be in use on the server.
	
	As long as you retain this default directory structure, these 
	relative paths will be fine for continued use in the site pages.
	The site pages are the php files located in the root directory 
	of the package.
	
	When you are working from a terminal or command prompt, the relative
	paths in the maintenance scripts (found in the /utility directory) 
	will function, however in order for the script maint_crontab to operate
	correctly when invoked via crontab or another scheduler it is required
	that you update the inclusion path with the absolute path to the file
	spectra_config.php.
	
	It is, honestly, best to use your text editor to replace all of the 
	inclusions (require_once statement at the beginning of each file) 
	with the absolute path to the spectra_config file. 
	
	This is especially true if you will be running more than one instance
	of the block explorer on the same server.

/******************************************************************************
	Installation
******************************************************************************/

	Once you have uploaded the scripts to your server and performed 
	the required steps for configuration, you should browse to the file
	"network.php" in your web browser.
	
	The page will not load if it cannot establish a connection to the 
	database specified in the configuration file.if the page does not
	display any content, you should take normal troubleshooting measures 
	to determine what is wrong with your database connection.
	
	if the page connects to the database and renders, you should see 
	the network.php page populated with information about the block
	chain and network taken directly from the specified crypto-currency 
	node.
	
	If the networkinformation page displays correctly it is time to 
	set up the database to receive the block chain data.
	
	At a command prompt or terminal session as appropriate, browse
	to the utility directory for this installation. execute the script 
	"setup_build.php", which will create the tables required by the 
	block explorer in the specified database.  This will also generate 
	a log file which you can use to determine whther or not the tables
	were created successfully.  The lag file has a default name of 
	"spectra.log" if you did not change it in the configuration file.
	
	If you enabled debug output in the configuration file, this information
	will be displayed for you on the terminal screen.
	
	When you have determined that the database tables have been created
	you can execute the script "setup_load.php"
	
	The setup_load.php script will begint he process of parsing blocks
	from your node and inserting the data into the MySQL database. This 
	script should only ever be used once, for the initial loading of the
	database. If the loading of the block chain is interrupted, you should
	manually rollback the last block referenced in the log file, in case 
	of any partial block insertions, and continue loading the blocks
	by executing the script named "maint_crontab.php".
	
	If it is necessary for you to manually roll back the database this can
	be done by executing the script maint_deleteblock.php with a single
	command prompt parameter, the block height of the block that you need
	to have removed. For example:
	
		php maint_deleteblock.php 13105
		
	That sample command will remove all data associated with the block
	at block height 13105 from the block explorer database.
	
	After ensuring that you have removed any partially loaded blocks, you 
	can execute the maint_crontab script to continue parsing the block chain.
	
	If there were any interruptions during the laoding process, it is best
	execure the maint_rebalance script after you have finished loading the
	block chain into the database.  This will re-balance any ledger values
	that may have been duplicated during the loading process.
	
/******************************************************************************
	Maintenance
******************************************************************************/

	Once you have loaded the blocks into the database you should 
	configure a cron job to regularly execute the maint_crontab.php
	script which will load any new blocks into the database.
	
	Maint_crontab.php also includes code that will automatically 
	roll back the database if it sees that the data has lost sync
	with the node (for example in case of an orphaned block) and 
	initialize a rebalancing of the ledger if it deletes any blocks 
	from the database.
	
/******************************************************************************
	Additional Troubleshooting
******************************************************************************/





/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
?>	