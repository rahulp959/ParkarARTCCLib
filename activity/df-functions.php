<?php
/**
 * This file contains a set of functions to support the ZOA processes for:
 *
 *    - controller stats (saved in MySQL table controller_log
 *    - who is online (saved in tables atc_online and pilotos_online)
 *
 */

/*
 * General information about the data feed file:
 *
 * Client records are available in the data feed file for pilots and ATC.
 * Each client record contains 41 fields separated by colon ":" character
 * (shown here with field offset number relative to zero)
 *
 *     [0] callsign                  [21] planned_flighttype
 *     [1] cid                       [22] planned_deptime
 *     [2] realname                  [23] planned_actdeptime
 *     [3] clienttype                [24] planned_hrsenroute
 *     [4] frequency                 [25] planned_minenroute
 *     [5] latitude                  [26] planned_hrsfuel
 *     [6] longitude                 [27] planned_minfuel
 *     [7] altitude                  [28] planned_altairport
 *     [8] groundspeed               [29] planned_remarks
 *     [9] planned_aircraft          [30] planned_route
 *    [10] planned_tascruise         [31] planned_depairport_lat
 *    [11] planned_depairport        [32] planned_depairport_lon
 *    [12] planned_altitude          [33] planned_destairport_lat
 *    [13] planned_destairport       [34] planned_destairport_lon
 *    [14] server                    [35] atis_message
 *    [15] protrevision              [36] time_last_atis_received
 *    [16] rating                    [37] time_logon (format yyyymmddhhmmss)
 *    [17] transponder               [38] heading
 *    [18] facilitytype              [39] QNH_iHg
 *    [19] visualrange               [40] QNH_Mb
 *    [20] planned_revision
 */

/**
 * function connect_to_db()
 *
 * Creates a connection to MySQL and selects the ZOA database
 *
 * Arguments:
 *    none
 *
 * Returns:
 *    The MySQL link identifier, or FALSE if failure
 *
 */
function connect_to_db() {
	$mysql_link = mysql_connect('localhost','zobartcc_zob','zDcD0@UiZSQT');
	if ($mysql_link === FALSE) {
		df_log_error('ERROR: Connection to MySQL database failed: ' . mysql_error());
	} else {
		if (!mysql_select_db('zobartcc_zob')) {
			df_log_error('ERROR: Could not select (use) the database: ' . mysql_error());
			mysql_close();
			$mysql_link = FALSE;
		}
	}
	return $mysql_link;
}

/**
 * function get_data_feed($local_file_name)
 *
 * Retrieves the vatsim data feed from a data server. Usually multiple data
 * feed servers are available, so this function will try them in random order
 * as recommended by vatsim administrators to "spread the load".
 *
 * Arguments:
 *    $local_file_name  -  the file path to the location where the data file
 *                         should be stored locally
 *
 * Returns:
 *    The data update time (yyyymmddhhmmss) if the file is successfully retrieved,
 *    otherwise FALSE
 *
 */
function get_data_feed($local_file_name) {
	$data_update_time = FALSE;
	$servers = array();
	$server_status_feed = 'http://status.vatsim.net/status.txt';
	$local_server_file = '/home/zobartcc/public_html/activity/servers.txt';
	if (date('i') < 2 || !is_file($local_server_file)) {
		copy($server_status_feed, $local_server_file);
	}
	$server_file = file($local_server_file);
	foreach($server_file as $server_record) {
		if (substr($server_record, 0, 5) == 'url0=') {
			$servers[] = rtrim(substr($server_record, 5));
		}
	}
	if (empty($servers)) {
		df_log_error("ERROR: there are no data feeds available!");
	} else {
		if (!shuffle($servers)) df_log_error('WARNING: the random shuffle for the server list failed');
		foreach($servers as $source_feed) {
			if (copy($source_feed, $local_file_name)) {
				$data_file = file($local_file_name);
				/*
				 * We want to confirm that the data in the file is recently updated.
				 */
				foreach($data_file as $data_record) {
					if (substr($data_record, 0, 9) == 'UPDATE = ') {
						$streamupdate = rtrim(substr($data_record, 9));
						$update_time = gmmktime(
								substr($streamupdate,8,2),
								substr($streamupdate,10,2),
								substr($streamupdate,12,2),
								substr($streamupdate,4,2),
								substr($streamupdate,6,2),
								substr($streamupdate,0,4));
						break;
					}
				}
				/*
				if (!$streamupdate) {
					df_log_error('ERROR: Did not find a value for the data feed file update timestamp (UPDATE = )');
				}
				 */
				$age = time() - $update_time;
				if ($age < 600) {
					$data_update_time = $streamupdate;
					break;
				/* } else {
					df_log_error("WARNING: age for $source_feed is $age seconds (from $streamupdate)");
				 */
				}
			/*
			} else {
				df_log_error("WARNING: failed to download vatsim data feed from $source_feed");
			 */
			}
		}
	}
	if (!$data_update_time) df_log_error("ERROR: did not successfully download from any of the data feeds!");
	return $data_update_time;
}

/**
 * function get_facilities()
 *
 * Builds an array containing the 3-character code for ZOA facilities used
 * in callsigns
 *
 * Arguments:
 *    none
 *
 * Returns:
 *    Array of 3-character codes for all ZOA facilities used in callsigns
 *
 */
function get_facilities() {
	$prefixes = array('MBS','LAN','JXN','ADG','TOL','TDZ','DFI','MNN','MFD','BJJ','SLW','CAK','AKR','PHD','HLG','MGW','CKB','YNG','BVI','PIT','AGC','LBE','IDI','JST','AOO','BFD','ELZ','OLE','JHW','DKK','BUF','ROC','DSV','PEO','ERI','LNN','CGF','BKL','CLE','DET','DTW','MTC','PHN','PTK','ARB','YIP');
	return $prefixes;
}

/**
 * function is_zoa_controller()
 *
 * Determines whether a record from the vatsim data feed is a ZOA controller online
 *
 * Arguments:
 *    $data_record  =  array containing the vatsim data feed record (client record type)
 *
 * Returns:
 *    TRUE if this is an ATC record for a valid ZOA controller position, otherwise FALSE
 *
 */
function is_controller($data_record) {
    $zoa_match = FALSE;
	if($data_record[3] == 'ATC' && $data_record[16] != '1' && $data_record[18] != '0' && stristr($data_record[0],'OBS') === FALSE) {
	    $prefixes = get_facilities();
		for($a = 0; $a < count($prefixes); $a++) {
			if(($prefixes[$a] . '_') == substr($data_record[0], 0, 4)) {
				$zoa_match = TRUE;
				break;
			}
		}
	}
	return $zoa_match;
}

/**
 * function is_zoa_pilot()
 *
 * Determines whether a record from the vatsim data feed is a pilot flying to/from ZOA
 *
 * Arguments:
 *    $data_record  =  array containing the vatsim data feed record (client record type)
 *
 * Returns:
 *    TRUE if this is a pilot record for a flight to/from ZOA, otherwise FALSE
 *
 */
function is_pilot($data_record) {
    $zoa_match = FALSE;
	if ($data_record[3] == 'PILOT' and $data_record[11] != '' and $data_record[13] != '') {
		$result = mysql_query("SELECT icao FROM icaos WHERE icao = '$data_record[11]' OR icao = '$data_record[13]' ");
		if (mysql_num_rows($result) > 0) $zoa_match = TRUE;
	}
	return $zoa_match;
}

/**
 * function save_controller_stats()
 *
 * Saves to the database a matched atc client record from the vatsim data feed.
 * This updates both the individual controllers' stats in the controller_log table,
 * as well as the atc_online table.
 *
 * Arguments:
 *    $atc_array     -  array holding all of the fields from the vatsim data feed (ATC record)
 *    $streamupdate  -  the update datetime value from the vatsim data feed file header
 *
 * Returns:
 *    none
 *
 */
function save_controller_stats($atc_array, $streamupdate) {
	$position  = $atc_array[0];
	$cid       = $atc_array[1];
	$realname  = $atc_array[2];
	$frequency = $atc_array[4];
	$rating    = $atc_array[16];
	$starttimestamp = gmmktime(substr($atc_array[37], 8, 2), substr($atc_array[37], 10, 2), substr($atc_array[37], 12, 2), substr($atc_array[37], 4, 2), substr($atc_array[37], 6, 2), substr($atc_array[37], 0, 4));
	$date = date("n/j/y");
	$duration = time() - $starttimestamp;
	$lastsession = mysql_query("SELECT id, start, streamupdate FROM controller_log WHERE cid = '$cid' ORDER BY start DESC");
	$row = mysql_fetch_array($lastsession);

	if($streamupdate != $row["streamupdate"]) {
		if(($row['start'] != $starttimestamp) || !mysql_num_rows($lastsession)) {
			mysql_query("INSERT INTO controller_log (cid, date, start, duration, position, streamupdate) VALUES ('$cid', '$date', '$starttimestamp', '$duration', '$position', '$streamupdate')");
		} else {
			$id = $row["id"];
			mysql_query("UPDATE controller_log SET duration = '$duration', streamupdate = '$streamupdate' WHERE id = '$id'");
		}
	}
	mysql_query("INSERT INTO atc_online (atc, freq, name, cid, rango, atis,starttime) VALUES ('$position','$frequency','".mysql_real_escape_string($realname)."','$cid','$rating','no atis available','$starttimestamp') ") or die("Failed (1) " . mysql_error());
}

/**
 * function save_pilot_online()
 *
 * Saves to the database a matched pilot client record from the vatsim data feed.
 * This inserts a row into the pilotos_online table.
 *
 * Arguments:
 *    $pilot_array  -  array holding all of the fields from the vatsim data feed (pilot record)
 *
 * Returns:
 *    none
 *
 */
function save_pilot_online($pilot_array) {
	$position  = $pilot_array[0];
	$realname  = $pilot_array[2];
	$depart    = $pilot_array[11];
	$dest      = $pilot_array[13];
	$lat	   = $pilot_array[5];
	$lon	   = $pilot_array[6];
	mysql_query("INSERT INTO pilots_online (callsign, icao1, icao2, name, lat, lon) VALUES ('$position','$depart','$dest','$realname', '$lat', '$lon') ");
}

/**
 * function df_log_error()
 *
 * Logs an error message.
 *
 * Arguments:
 *    $message
 *
 * Returns:
 *    none
 *
 */
function df_log_error($message) {
	$log_path = '/home/zhuartcc/apps/activity/stats_error_log';
	$fmt_msg = '[' . date('Y.m.d.H:i:s') . '] ' . $message . "\n";
	error_log($fmt_msg,3,$log_path);
	// echo $fmt_msg;
}

?>
