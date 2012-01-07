<?php
session_start();

require_once("inc/config.php");
require_once("inc/PZOB.php");
require_once("/home2/zobartcc/www/ajax/securimage/securimage.php");
$securimage = new Securimage();

$garbage_timeout = 3600; // 3600 seconds = 60 minutes = 1 hour
ini_set('session.gc_maxlifetime', $garbage_timeout);

$ZOB = new PZOB();
$ZOB->db_build($db);
if ($_REQUEST['page']) { $pn = $_REQUEST['page']; } else { $pn = "home"; }
$page = $ZOB->build_page($pn);

//Include HTML from site design with spaces for page name and page content.
include("html/header.php");
include("html/navbar.php");
include("html/pagename.html");
//echo $page[0];
include("html/pagecontent.html");
include($page);
include("html/sidebar.php");
include("html/footer.html");

?>