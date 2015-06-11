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
define('THIS_PAGE', 'INDEX');
define('INSTALL',1);
define('NEW_VERSION', '2.0.1');

// Require the settings file
require '../ccount_settings.php';

// Load functions
require '../inc/common.inc.php';

// Start session
pj_session_start();

// We will use this later...
$ccount_settings['INSTALL_OK'] = false;
$error_buffer = array();

// ==> Update settings file
if ( ! is_writable('../ccount_settings.php') )
{
	$error_buffer['SETTINGS'] = 1;
}
else
{
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
\$ccount_settings['version']='" . NEW_VERSION . "';", LOCK_EX) === false)
	{
		$_SESSION['PJ_MESSAGES']['ERROR'] = 'Error writing to settings file, please try again later.';
	}
	else
	{
		pj_session_stop();
		$ccount_settings['INSTALL_OK'] = true;
	}
}

// Get header
include('../admin/admin_header.inc.php');

// Load main installation page
?>
<div class="container">

	<div class="row">
		<div class="col-lg-12">
			<?php
			if ($ccount_settings['INSTALL_OK'])
			{
				?>
				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Update successful</h3>
					</div>
					<div class="panel-body">

						<h4>Next Steps:</h4>

						&nbsp;

						<ol>
							<li><span style="color:red">Important: </span> For security reasons, delete <b>install</b> folder from the server.<br />&nbsp;</li>
							<li><a href="../admin/index.php">Login to admin panel</a> to add new links to track, read instructions and manage settings.</li>
						</ol>

						<p>&nbsp;</p>

                	</div>
				</div>
				<?php
			} // END INSTALL OK
			else
			{
				?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Updating Click Counter</h3>
					</div>
					<div class="panel-body">

						<?php pj_processMessages(); ?>

						<p>&nbsp;</p>

						<form method="POST" action="update.php" class="form-horizontal">

		                    <div class="form-group">
		                        <label class="col-lg-3 control-label">ccount_settings.php:</label>
								<?php
								if ( pj_test_error('SETTINGS', 1) )
								{
									?>
									<div class="col-lg-9" style="padding-top:7px;margin-bottom:10px;color:red">
										<b>Not writable</b><br />PHP needs permission to write to this file. On Linux servers CHMOD the file to 666 (rw-rw-rw-).
									</div>
									<?php
								}
								else
								{
									?>
									<div class="col-lg-9" style="padding-top:7px;margin-bottom:10px;color:green;font-weight:bold">
										OK
									</div>
									<?php
								}
								?>
		                    </div>
		                    <div class="form-group">
		                        <div class="col-lg-offset-3 col-lg-9">
		                            <input type="hidden" name="action" value="save">
		                            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;Try again</button>
		                        </div>
		                    </div>
						</form>

						<p>&nbsp;</p>

                	</div>
				</div>
				<?php
			} // END install not OK
			?>
		</div>
	</div>
<?php

// Get footer
include('../admin/admin_footer.inc.php');


////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS
////////////////////////////////////////////////////////////////////////////////


function pj_test_error($type, $value = 0)
{
	global $error_buffer;

	if ( isset($error_buffer[$type]) && $error_buffer[$type] == $value )
	{
		return true;
	}

	return false;
} // END pj_test_error()
