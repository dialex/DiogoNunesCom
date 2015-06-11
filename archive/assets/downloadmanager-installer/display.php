<?php
/*******************************************************************************
*  Title: PHP click counter (CCount)
*  Version: 2.0.1 from 4th August 2014
*  Author: Klemen Stirn
*  Website: http://www.phpjunkyard.com
********************************************************************************
*  COPYRIGHT NOTICE
*  Copyright 2004-2014 Klemen Stirn. All Rights Reserved.

*  This script may be used and modified free of charge by anyone
*  AS LONG AS COPYRIGHT NOTICES AND ALL THE COMMENTS REMAIN INTACT.
*  By using this code you agree to indemnify Klemen Stirn from any
*  liability that might arise from it's use.

*  Selling the code for this program, in part or full, without prior
*  written consent is expressly forbidden.

*  Using this code, in part or full, to create derivate work,
*  new scripts or products is expressly forbidden. Obtain permission
*  before redistributing this software over the Internet or in
*  any other medium. In all cases copyright and header must remain intact.
*  This Copyright is in full effect in any country that has International
*  Trade Agreements with the United States of America or
*  with the European Union.

*  Removing any of the copyright notices without purchasing a license
*  is expressly forbidden. To remove copyright notice you must purchase
*  a license for this script. For more information on how to obtain
*  a license please visit the page below:
*  http://www.phpjunkyard.com/buy.php
*******************************************************************************/

define('IN_SCRIPT',1);

// Tell browsers not to cache the file output
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Start Javascript variable
echo 'var ccount={';

// Require the settings file
require 'ccount_settings.php';

// Get links database
$data = explode('//', file_get_contents($ccount_settings['db_file']), 2);

// Convert contents into an array
$ccount_database = isset($data[1]) ? unserialize($data[1]) : array();
unset($data);

// List all links in a Javascript array
foreach ($ccount_database as $id => $link)
{
	echo "'{$id}':{c:{$link['C']},u:{$link['U']}},";
}

// Print the rest of Javascript
?>'':{}};
function ccount_display(id)
{
	document.write(ccount[id]['c'].formatThousands('<?php echo $ccount_settings['notation']; ?>'));
}
function ccount_unique(id)
{
	document.write(ccount[id]['u'].formatThousands('<?php echo $ccount_settings['notation']; ?>'));
}

Number.prototype.formatThousands = function(notation)
{
	var n = this, separator = "";
	switch (notation)
	{
		case "US":
			separator = ",";
			break;
		case "UK":
			separator = ".";
			break;
		case "FR":
			separator = " ";
			break;
		default:
			return n;
	}
	n = parseInt(n) + "";
	j = (j = n.length) > 3 ? j % 3 : 0;
	return (j ? n.substr(0, j) + separator : "") + n.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + separator);
}
