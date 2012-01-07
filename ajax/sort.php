<?php

require_once("../inc/config.php");
require_once("../inc/PZOB.php");

$ZOB = new PZOB;

$ZOB->db_build($db);

parse_str($_POST['data']);

$fp = fopen("log.txt","a");
fwrite($fp, "Here we go!!\n");

for ($i = 0 ; $i < count($sortlist) ; $i++)
{
	fwrite($fp, "$i ${sortlist[$i]}\n");
	$ZOB->db_execute($db, "UPDATE `eventspositions` SET `order`=$i WHERE `posid`='".$sortlist[$i]."'");
}
?>
