function block_raw_pop (strHASH)
{
	document.getElementById('raw_block_click').href='javascript:block_raw_dump (\'' + strHASH + '\')';
	document.getElementById('raw_block_click').innerHTML='Click Here To Hide Raw Block Data';

	document.getElementById('raw_block_display').style.display='block';
	panel_fetch ('raw_block_display', 'block.php?raw=1&hash=' + strHASH);
}

function block_raw_dump (strHASH)
{
	document.getElementById('raw_block_click').href='javascript:block_raw_pop (\'' + strHASH + '\')';
	document.getElementById('raw_block_click').innerHTML='Click Here To Load Raw Block Data';

	document.getElementById('raw_block_display').style.display='none';
	document.getElementById('raw_block_display').innerHTML='';
}

function tx_raw_pop (strTX)
{
	document.getElementById('raw_tx_click').href='javascript:tx_raw_dump (\'' + strTX + '\')';
	document.getElementById('raw_tx_click').innerHTML='Click Here To Hide Raw Transaction Data';

	document.getElementById('raw_tx_display').style.display='block';
	panel_fetch ('raw_tx_display', 'spectra/tx.php?raw=1&tx=' + strTX);
}

function tx_raw_dump (strTX)
{
	document.getElementById('raw_tx_click').href='javascript:tx_raw_pop (\'' + strTX + '\')';
	document.getElementById('raw_tx_click').innerHTML='Click Here To Load Raw Transaction Data';

	document.getElementById('raw_tx_display').style.display='none';
	document.getElementById('raw_tx_display').innerHTML='';
}

function panel_fetch (strTARG, strURL) 
{
	var popREQ;
	
//	Let's have a loading gif
	document.getElementById(strTARG).innerHTML = '<img src="theme/themeless/icon_load.gif" alt="Loading...">';

//	All Browsers Do This Now
	if (window.XMLHttpRequest) 
	{
		popREQ = new XMLHttpRequest();
	}
		
//	IE Goes Back Further
	else if (window.ActiveXObject) 
	{
		popREQ = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
//	Prepare the AJAX request
	popREQ.open('GET', strURL, true);
	
//	Set the response function to update the target on response	
	popREQ.onreadystatechange = function() 
	{
		if (popREQ.readyState == 4) 
		{
			document.getElementById(strTARG).innerHTML = popREQ.responseText;
		}
	}
    
//	Process the request
	popREQ.send('data=' + escape(strURL));
}

/******************************************************************************
	Developed By Jake Paysnoe - Copyright © 2015 SPEC Development Team
	SPEC Block Explorer is released under the MIT Software License.
	For additional details please read license.txt in this package.
******************************************************************************/
