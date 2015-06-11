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
define('THIS_PAGE', 'SETTINGS');

// Require the settings file
require '../ccount_settings.php';

// Load functions
require '../inc/common.inc.php';

// Start session
pj_session_start();

// Are we logged in?
pj_isLoggedIn(true);

// The settings file is in parent folder
$ccount_settings['db_file_admin'] = '../' . $ccount_settings['db_file'];

$error_buffer = array();

// Save settings?
if ( pj_POST('action') == 'save' && pj_token_check() )
{
	// Check demo mode
	pj_demo('settings.php');

	// Admin password
	$ccount_settings['admin_pass'] = pj_input( pj_POST('admin_pass') ) or $error_buffer['admin_pass'] = 'Choose a password you will use to access admin pages.';

	// click.php URL
	$ccount_settings['click_url'] = pj_validateURL( pj_POST('click_url') ) or $error_buffer['click_url'] = 'Enter a valid URL address of the click.php file on your server.';

	// Database file
	$ccount_settings['db_file'] = pj_input( pj_POST('db_file', 'ccount_database.php') );

	// Check database file
	if ( preg_match('/[^0-9a-zA-Z_\-\.]/', $ccount_settings['db_file']) )
	{
		$error_buffer['db_file'] = 'Invalid file name. Use only these chars: a-z A-Z 0-9 _ - .';
	}

	// Unique hours
	$ccount_settings['unique_hours'] = intval( pj_POST('unique_hours', 24) );
	if ($ccount_settings['unique_hours'] < 0)
	{
		$ccount_settings['unique_hours'] = 0;
	}

	// Notation
	$ccount_settings['notation'] = pj_input( pj_POST('notation', 'US') );
	if ( ! in_array($ccount_settings['notation'], array('US', 'UK', 'FR', 'X1', 'X2') ) )
	{
		$ccount_settings['notation'] = 'US';
	}

    // If no errors, check for duplicates/generate a new ID
	if ( count($error_buffer) == 0 )
    {
		// Update settings file
		if ( @file_put_contents('../ccount_settings.php', "<?php
error_reporting(0);
if (!defined('IN_SCRIPT')) {die('Invalid attempt!');}

// Password hash for admin area
\$ccount_settings['admin_pass']='{$ccount_settings['admin_pass']}';

// URL of the click.php file
\$ccount_settings['click_url']='{$ccount_settings['click_url']}';

// Number of hours a visitor is considered as \"unique\"
\$ccount_settings['unique_hours']={$ccount_settings['unique_hours']};

// Sets the preferred number notation (US, UK, FR, X1, X2)
\$ccount_settings['notation']='{$ccount_settings['notation']}';

// Name of the log file
\$ccount_settings['db_file']='{$ccount_settings['db_file']}';

// Version information
\$ccount_settings['version']='{$ccount_settings['version']}';", LOCK_EX) === false)
		{
			$_SESSION['PJ_MESSAGES']['ERROR'] = 'Error writing to settings file, please try again later.';
		}
		else
		{
			$_SESSION['PJ_MESSAGES']['SUCCESS'] = 'Settings have been saved.';
		}
    }
}

if ( count($error_buffer) )
{
	$_SESSION['PJ_MESSAGES']['ERROR'] = 'Missing or invalid data, see below for details.';
}

// Require admin header
require 'admin_header.inc.php';

?>

<?php pj_processMessages(); ?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Edit settings</h3>
			</div>
			<div class="panel-body">
                <form action="settings.php" method="post" name="settingsform" class="form-horizontal">

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Script version:</label>
                        <div class="col-lg-9" style="padding-top:7px;margin-bottom:10px">
                            <?php echo $ccount_settings['version']; ?>
                        </div>
                    </div>

                    <div class="form-group<?php echo isset($error_buffer['admin_pass']) ? ' has-error' : ''; ?>">
                        <label for="url" class="col-lg-3 control-label bold">Admin password:</label>
                        <div class="col-lg-9">
                            <input type="text" id="admin_pass" name="admin_pass" value="<?php echo stripslashes($ccount_settings['admin_pass']); ?>" size="50" maxlength="255" class="form-control" placeholder="" autocomplete="off">
                            <p class="help-block"><?php echo isset($error_buffer['admin_pass']) ? $error_buffer['admin_pass'] : 'Password used to login to CCount admin pages.'; ?></p>
                        </div>
                    </div>
                    <div class="form-group<?php echo isset($error_buffer['click_url']) ? ' has-error' : ''; ?>">
                        <label for="url" class="col-lg-3 control-label bold">URL of click.php file:</label>
                        <div class="col-lg-9">
                            <input type="url" id="click_url" name="click_url" value="<?php echo stripslashes($ccount_settings['click_url']); ?>" size="50" maxlength="255" class="form-control" placeholder="http://www.example.com/ccount/click.php">
                            <p class="help-block"><?php echo isset($error_buffer['click_url']) ? $error_buffer['click_url'] : 'Location of the <b>click.php</b> file on your server.'; ?></p>
                        </div>
                    </div>
                    <div class="form-group<?php echo isset($error_buffer['db_file']) ? ' has-error' : ''; ?>">
                        <label for="text" class="col-lg-3 control-label bold">CCount database file:</label>
                        <div class="col-lg-9">
                            <input type="text" id="db_file" name="db_file" value="<?php echo $ccount_settings['db_file']; ?>" size="50" maxlength="255" class="form-control" placeholder="ccount_database.php">
                            <p class="help-block"><?php echo isset($error_buffer['db_file']) ? $error_buffer['db_file'] : 'Name of the CCount database file (defaults to <b>ccount_database.php</b>).'; ?></p>
                        </div>
                    </div>
                    <div class="form-group<?php echo isset($error_buffer['unique_hours']) ? ' has-error' : ''; ?>">
                        <label for="name" class="col-lg-3 control-label bold">Unique hours limit:</label>
                        <div class="col-lg-9">
                            <input type="text" id="unique_hours" name="unique_hours" value="<?php echo $ccount_settings['unique_hours']; ?>" maxlength="10" size="5" class="form-control" style="width:80px;">
                            <p class="help-block"><?php echo isset($error_buffer['unique_hours']) ? $error_buffer['unique_hours'] : 'Number of hours between clicks a visitor is again considered unique (defaults to <b>24</b>).'; ?></p>
                        </div>
                    </div>
                    <div class="form-group<?php echo isset($error_buffer['notation']) ? ' has-error' : ''; ?>">
                        <label for="name" class="col-lg-3 control-label bold">Number notation:</label>
                        <div class="col-lg-9">
							<div class="radio">
								<label>
									<input type="radio" name="notation" id="notation1" value="US" <?php echo $ccount_settings['notation'] == 'US' ? 'checked="checked"' : ''; ?> > 10<b>,</b>000<b>.</b>0
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="notation" id="notation1" value="UK" <?php echo $ccount_settings['notation'] == 'UK' ? 'checked="checked"' : ''; ?> > 10<b>.</b>000<b>,</b>0
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="notation" id="notation1" value="FR" <?php echo $ccount_settings['notation'] == 'FR' ? 'checked="checked"' : ''; ?> > 10<b> </b>000<b>,</b>0
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="notation" id="notation1" value="X1" <?php echo $ccount_settings['notation'] == 'X1' ? 'checked="checked"' : ''; ?> > 10000<b>.</b>0
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="notation" id="notation1" value="X2" <?php echo $ccount_settings['notation'] == 'X2' ? 'checked="checked"' : ''; ?> > 10000<b>,</b>0
								</label>
							</div>
                            <p class="help-block">&nbsp;</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                            <input type="hidden" name="action" value="save">
							<input type="hidden" name="token" value="<?php echo pj_token_get(); ?>">
                            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Save changes</button>
                        </div>
                    </div>
                </form>
			</div>
		</div>
	</div>
</div>

<?php

// Get footer
include('admin_footer.inc.php');
