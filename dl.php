<?php
require("inc/config.php");
require("inc/PZOB.php");
$ZOB = new PZOB;

if (!$_REQUEST['id'])
{
	exit;
}

if ($ZOB->isint($_REQUEST['id'])) { $id = $_REQUEST['id']; }

$ZOB->db_build($db);
$ZOB->db_query($db, $res, "SELECT `name`,`file` FROM `uploads` WHERE `id`='$id' LIMIT 1");
$row = mysql_fetch_assoc($res);

header("Content-type: " . $ZOB->mime_content_type2($row['file']));
$path_parts = pathinfo($row['file']);
header("Content-Disposition: attachment; filename=\"".$row['name'].".".$path_parts['extension']."\"");

ob_end_clean();
ob_start();

$handle = fopen($row['file'], 'rb');
if ($handle === false ) { return false; }

while (!feof($handle))
{
	$buf = fread($handle, 1 * 1024 * 1024);
	echo $buf;
	ob_flush();
	flush();
}

fclose($handle);
?>
