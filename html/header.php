<?php

if($_REQUEST['page'] == "logout")
{
	$pastdate = mktime(0,0,0,1,1,1970);
	setcookie("login", "", $pastdate, "/", "zobartcc.com");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
		<title>ZOB ARTCC</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
		<!--[if IE 6]><link rel="stylesheet" href="css/style.ie6.css" type="text/css" media="screen" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" href="css/style.ie7.css" type="text/css" media="screen" /><![endif]-->
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/pdf.js"></script>
		<SCRIPT LANGUAGE="JavaScript">
		<!-- Idea by:  Nic Wolfe -->
		<!-- This script and many more are available free online at -->
		<!-- The JavaScript Source!! http://javascript.internet.com -->
		
		<!-- Begin
		function popUp(URL) {
			day = new Date();
			id = day.getTime();
			eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=640,height=480,left = 320,top = 272');");
		}
		// End -->
		</script>
	</head>
	<body>
		<div id="page-background-glare">
			<div id="page-background-glare-image">
				<div id="main">
					<div class="sheet">
						<div class="sheet-tl"></div>
						<div class="sheet-tr"></div>
						<div class="sheet-bl"></div>
						<div class="sheet-br"></div>
						<div class="sheet-tc"></div>
						<div class="sheet-bc"></div>
						<div class="sheet-cl"></div>
						<div class="sheet-cr"></div>
						<div class="sheet-cc"></div>
						<div class="sheet-body">
							<div class="header">
								<div class="header-jpeg"></div>
								<div class="logo">
								</div>
							</div>