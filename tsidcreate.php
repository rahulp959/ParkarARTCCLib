<?php

include("inc/config.php");
include("inc/PZOB.php");

$ZOB = new PZOB();

$ids = $ZOB->ts3_return_array();

echo $ids;

?>