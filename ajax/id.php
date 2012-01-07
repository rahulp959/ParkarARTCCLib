<?php

if (!isset($_REQUEST['cid']))
{
	header("Content-type: text/plain");
	echo "-1";
	exit;
}

require ("../inc/config.php");
require ("../inc/PZOB.php");

$ZOB = new PZOB();

$_REQUEST['cid'] = $ZOB->db_safe($_REQUEST['cid']);

$cid = $_REQUEST['cid'];

$ZOB->db_build($db);

$ZOB->db_query($db, $res, "SELECT * FROM `users` WHERE `cid`='${_REQUEST['cid']}' LIMIT 1");
if (mysql_num_rows($res) == 1)
{
	header("Content-Type: text/plain");
	echo "100";
	exit;
	
}
// So they aren't in our DB... gotta query VATSIM
// Their API: https://cert.vatsim.net/vatsimnet/idstatus.php?cid=_______

$url = "http://www.vatusa.net/feeds/cidlookup.php?a=6&key=709ceab47022aac392ecf5ff27d5e6e2&cid=${_REQUEST['cid']}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_USERAGENT, 'ZOB (Cleveland) ARTCC VATSIM ID Checker.');

$content = curl_exec($ch);

curl_close($ch);

// Now handle the XML in $content

$root = new SimpleXMLElement($content, null, false);

if ($root->controller[0]->cid == "") { 
	
	vatsimparse($cid); 
} else {

if ($root->user[0]->rating == "Suspended" || $root->user[0]->rating == "Inactive") { header("Content-type: text/plain"); echo "101"; exit; }

@mysql_free_result($res);
//$ZOB->db_query($db, $res, "SELECT `ratingid` FROM `ratings` WHERE `vatshort`='".$root->controller[0]->rating ."'");
//$row = mysql_fetch_assoc($res);
$rating = $root->controller[0]->rating;
$rating = $rating - 1;
header("Content-type: application/xml");

echo "<zob><user><indb>0</indb><cid>";
echo $root->controller[0]->cid;
echo "</cid><firstname>" . $root->controller[0]->fname . "</firstname>";
echo "<lastname>".$root->controller[0]->lname . "</lastname><email>".$root->controller[0]->email . "</email>";
echo "<rating>".$rating . "</rating><artcc>".$root->controller[0]->artcc . "</artcc></user></zob>";
}

function vatsimparse($cid) {
	
	$url = "https://cert.vatsim.net/vatsimnet/idstatus.php?cid=$cid";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_USERAGENT, 'ZOB (Cleveland) ARTCC VATSIM ID Checker.');

$content = curl_exec($ch);

curl_close($ch);

// Now handle the XML in $content

$root = new SimpleXMLElement($content, null, false);

if ($root->user[0]->rating == "Suspended" || $root->user[0]->rating == "Inactive") { header("Content-type: text/plain"); echo "101"; exit; }

@mysql_free_result($res);
//$ZOB->db_query($db, $res, "SELECT `ratingid` FROM `ratings` WHERE `vatshort`='".$root->controller[0]->rating ."'");
//$row = mysql_fetch_assoc($res);
$rating = $root->user[0]->rating;
header("Content-type: application/xml");

echo "<zob><user><indb>0</indb><cid>";
echo $root->user[0]['cid'];
echo "</cid><firstname>" . $root->user[0]->name_first . "</firstname>";
echo "<lastname>".$root->user[0]->name_last . "</lastname><email>".$root->user[0]->email . "</email>";
echo "<rating>".$rating . "</rating><artcc>ZOB</artcc></user></zob>";
}
?>
