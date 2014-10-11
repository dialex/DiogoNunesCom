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

// Require the settings file
require '../ccount_settings.php';

// Load functions
require '../inc/common.inc.php';

// Start session
pj_session_start();

// Is this a LOGOUT request?
if ( pj_GET('logout', false) !== false && pj_token_check() )
{
	// Expire session variable
	$_SESSION['LOGGED_IN'] = false;

	// Delete cookie
	setcookie('ccount_hash', '');

	// Stop session
	pj_session_stop();

	// Define a success message
    $_SESSION['PJ_MESSAGES']['SUCCESS'] = 'You have logged out successfuly.';
}

// Are we logged in? Go to admin if yes
elseif ( pj_isLoggedIn() )
{
	header('Location: admin.php');
	die();
}

// Is this a LOGIN attempt?
elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Check password etc
	if ( stripslashes( pj_input( pj_POST('pass', false) ) ) == $ccount_settings['admin_pass'])
	{
		// Set session variable
		$_SESSION['LOGGED_IN'] = true;

		// Remember user?
		if ( pj_POST('remember') == 'yes' )
		{
			setcookie('ccount_hash', pj_Pass2Hash($ccount_settings['admin_pass']), strtotime('+1 year'));
		}

		// Redirect to admin
		header('Location: admin.php');
		die();
	}
    else
    {
		$_SESSION['PJ_MESSAGES']['ERROR'] = 'Invalid password.';
    }
}

// Session expired?
elseif ( isset($_GET['notice']) )
{
	$_SESSION['PJ_MESSAGES']['INFO'] = 'Session expired, please login again.';
}

// Nothing of above, print the sign in form...

// Get header
include('admin_header.inc.php');

// Sign in form
?>

	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav">
				<li><a href="#" class="text-center">
				<i class="glyphicon glyphicon-user"></i>&nbsp;Sign in</a></li>
			</ul>
			</div><!-- /.navbar-collapse -->
		</div>
	</nav>

	<div class="container">

		<div class="row">
			<div class="col-lg-12">
				<div class="panel">
					<div class="panel-body">

						<?php pj_processMessages(); ?>

						<p>&nbsp;</p>
						<form method="POST" action="index.php" class="form-signin">
							<h2 class="form-signin-heading">Your password:</h2>
							<input type="password" name="pass" class="input-block-level" value="<?php echo defined('PJ_DEMO') ? $ccount_settings['admin_pass'] : ''; ?>" >
							<label class="checkbox">
								<input type="checkbox" name="remember" value="yes"> Remember me
							</label>
							<button class="btn btn-large btn-primary" type="submit">Sign in</button>
						</form>
						<p>&nbsp;</p>
					</div>
				</div>
			</div>
		</div>

<?php

// Get footer
include('admin_footer.inc.php');
