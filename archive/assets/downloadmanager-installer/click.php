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

// Require the settings file
require 'ccount_settings.php';

// Get the link ID; valid chars for the link ID are: 0-9 a-z A-Z - . and _
$id = isset($_REQUEST['id']) ? preg_replace('/[^0-9a-zA-Z_\-\.]/', '', $_REQUEST['id']) : die('Missing link ID');

// Open database file for reading and writing 
if ($fp = @fopen($ccount_settings['db_file'], 'r+'))
{
	// Lock database file from other scripts
	$locked = flock($fp, LOCK_EX);

	// Lock successful?
	if ($locked)
	{
		// Read file
		$data = explode('//', fread($fp, filesize($ccount_settings['db_file'])), 2);

		// Convert contents into an array
		$ccount_database = isset($data[1]) ? unserialize($data[1]) : die('Invalid log file');

		// Is this a valid link?
		if ( ! isset($ccount_database[$id]) )
		{
			die('Link with this ID not found');
		}

		// Increse count by 1
		$ccount_database[$id]['C']++;

		// Is this a unique click or not?
		if ( ! isset($_COOKIE['ccount_unique_'.$id]) )
		{
			$ccount_database[$id]['U']++;
		}

		// Update the database file
		rewind($fp);
		fwrite($fp, "<?php die();//" . serialize($ccount_database));
	}
	else
	{
		// Lock not successful. Better to ignore than to damage the log file
		die('Error locking file, please try again later.');
	}

	// Release file lock and close file handle
	flock($fp, LOCK_UN);
	fclose($fp);
}

// Print the cookie  for counting unique clicks and P3P compact privacy policy
header('P3P: CP="NOI NID"');
setcookie('ccount_unique_'.$id, 1, time() + 3600 * $ccount_settings['unique_hours']);

// Redirect to the link URL
header('Location: ' . $ccount_database[$id]['L']);
die();
