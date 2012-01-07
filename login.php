<?php
session_start();
include("inc/PZOB.php");
if (!$_REQUEST['CID'] || !$_REQUEST['pass'])
{
echo $_REQUEST['CID'];
echo $_REQUEST['pass'];
}
else
{
	$pZOB = new PZOB;
	$rv = $pZOB->check_auth($_REQUEST['CID'], trim(stripslashes($_REQUEST['pass'])),0);
	if ($rv == 1)
	{
		if ($_REQUEST['cookie'] == 1)
		{
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		
			setcookie("login", mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 518856462569894564, $_REQUEST['CID'] . "-" . trim(stripslashes($_REQUEST['pass'])),MCRYPT_MODE_ECB, $iv), time() + 604800,"/","zobartcc.com");
		}
		// Logged in, Congrats!.. now let's give them a success! page and redirect them
?>
<html>
<head>
<meta http-equiv="refresh" content="3;url=/?page=home">
</head>
<body style="background-color: #333333;">
<div style="width: 100%; text-align: center; color: #ffffff;"><p>You have been successfully logged in.  Welcome to Cleveland ARTCC.  Enjoy your stay.</p><p>You should be redirected momentarily to the home page, if not, <a href="/?page=home">click here</a>.</p></div>
</body>
</html>
<?php
	}
	else if ($rv == -1)
	{
?>
<html>
<head>
<meta http-equiv="refresh" content="1;url=/?page=home&msg=Invalid%20Password%20Specified">
</head>
<body style="background-color: #333333;">
<div style="width: 100%; text-align: center; color: #ffffff;"><p>There was an error processing your request, as your CID and password did not match.</p><p>You should be redirected momentarily to the home page, if not, <a href="/?page=home">click here</a>.</p></div>
</body>
</html>
<?php
	}
	else
	{
?>
<html>
<head>
<meta http-equiv="refresh" content="3;url=/?page=home&msg=Unknown%20Database%20Error">
</head>
<body style="background-color: #333333;">
<div style="width: 100%; text-align: center; color: #ffffff;"><p>There was an error processing your request, an invalid response from the database has been received.</p><p>You should be redirected momentarily to the home page, if not, <a href="/?page=home">click here</a>.</p></div>
</body>
</html>
<?php
	}
}
?>