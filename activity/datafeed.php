<?php
include('/home2/zobartcc/public_html/activity/df-functions.php');
$local_file = '/home2/zobartcc/public_html/activity/datafeed.txt';
if (!connect_to_db()) { echo "(1)\n"; exit(1); }
$streamupdate = get_data_feed($local_file);
if (!$streamupdate) { echo "(2)\n"; exit(2); }
$file = file($local_file);
$in_client_section = FALSE;
mysql_query('DELETE FROM atc_online');
mysql_query('DELETE FROM pilots_online');
foreach($file as $record) {
	if($in_client_section && substr($record, 0, 1) == ';') {
		$in_client_section = FALSE;
	}
	if($in_client_section) {
		$data_record = explode(':', $record);
		if (is_controller($data_record)) {
			save_controller_stats($data_record, $streamupdate);
		} elseif (is_pilot($data_record)) {
			save_pilot_online($data_record);
		}
	}
	if(substr($record, 0, 8) == '!CLIENTS') {
		$in_client_section = TRUE;
	}
}
?>
