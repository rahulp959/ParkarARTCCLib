<?php

function delxlines($txtfile, $numlines)
{
if (file_exists("temp.txt"))
{
unlink("temp.txt");
}
$arrayz = file("$txtfile");
$tempWrite = fopen("temp.txt", "w");
for ($i = $numlines; $i < count($arrayz); $i++)
{
fputs($tempWrite, "$arrayz[$i]");
}
fclose($tempWrite);
copy("temp.txt", "$txtfile");
unlink("temp.txt");
}

require('/home2/zobartcc/public_html/inc/config.php');
require('/home2/zobartcc/public_html/inc/PZOB.php');

$pRouteCsv = 'http://www.fly.faa.gov/rmt/data_file/prefroutes_db.csv';
$pRouteCsvLocal = '/home2/zobartcc/public_html/proutes/proutes_db.csv';

copy($pRouteCsv, $pRouteCsvLocal);

delxlines($pRouteCsvLocal, 1);

$ZOB = new PZOB();

$ZOB->db_build($db);

mysql_query("TRUNCATE TABLE `preferred_routes`");

mysql_query("load data local infile '$pRouteCsvLocal' into table preferred_routes fields terminated by ',' enclosed by '\"' lines terminated by '\n' (orig, route, dest, hours1, hours2, hours3, type, area, altitude, aircraft, direction, seq, DCNTR, ACNTR)");