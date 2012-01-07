<?php
require_once("Mail.php");
require_once("PDBA.php");

class PZOB extends PDBA
{
	public $__email_def_from = "no-reply@zobartcc.com";
	public $__version = 'LibParkar 0.01';
	public $__artcc = 'ZOB';
	
	public function
	__construct()
	{
		global $conf;
		parent::__construct();
		
		$this->__email_def_from = $__email_def_from;
		set_include_path(get_include_path() . PATH_SEPARATOR . "/home1/citrusn1/php");
	}
	
	public function
	build_page($pagename) {
 		$result = mysql_query("SELECT * FROM `pages` WHERE `pagename`='" . $pagename . "' LIMIT 1");
		$row = mysql_fetch_array($result);
		
		if ($row['content'] == "") {
			$content[0] = '404';
			$content[1] = '<p>Errrmmm... YOU BROKE IT!</p>
<p>Just kidding, but I can\'t find the page you\'re looking for, care to try again? Or give the webmaster a call, his email address can be found on the staff page</p>';
			
			$_config['title_start'] = "<h1>";
			$_config['title_end'] = "</h1>";
			$_config['content_start'] = "";
			$_config['content_end'] = "";
			
			$handle = fopen("tmp/404" . session_id() . ".php", "w");
			fputs($handle, $_config['title_start'] . $content[0] . $_config['title_end'] . "\n");
			fputs($handle, $_config['content_start'] . $content[1] . $_config['content_end'] . "\n");
			fclose($handle);
			return "tmp/404" . session_id() . ".php";
		}
		else if ($result)
		{
			$_config['title_start'] = "<h1>";
			$_config['title_end'] = "</h1>";
			$_config['content_start'] = "";
			$_config['content_end'] = "";
			
			$handle = fopen("tmp/" . $pagename . session_id() . ".php", "w");
			fputs($handle, $_config['title_start'] . $row['title'] . $_config['title_end'] . "\n");
			fputs($handle, $_config['content_start'] . $row['content'] . $_config['content_end'] . "\n");
			fclose($handle);
			return "tmp/" . $pagename . session_id() . ".php";
		}
		else
		{
			$content[0] = '404';
			$content[1] = '<p>Errrmmm... YOU BROKE IT!</p>
<p>Just kidding, but I can\'t find the page you\'re looking for, care to try again? Or give the webmaster a call, his email address can be found on the staff page</p>';
			
			$_config['title_start'] = "<h1>";
			$_config['title_end'] = "</h1>";
			$_config['content_start'] = "";
			$_config['content_end'] = "";
			
			$handle = fopen("tmp/404" . session_id() . ".php", "w");
			fputs($handle, $_config['title_start'] . $content[0] . $_config['title_end'] . "\n");
			fputs($handle, $_config['content_start'] . $content[1] . $_config['content_end'] . "\n");
			fclose($handle);
			return "tmp/404" . session_id() . ".php";
		}
	}
	
	public function
	htp($cid, $pass)
	{
		return hash('sha512', $cid . $pass);
	}
	
	public function
	isint($int)
	{
		if (is_numeric($int)) {
			if ((int)$int == $int) { return 1; }
		}
		return 0;
	}

	public function
	check_auth($cid,$pass,$access,$enc = true)
	{
		if ($enc == true) { $pass = $this->htp($cid,$pass); }
		if (!$this->isint($cid)) { return -1; }
		if ($this->db_fetchone($db,$row,"`u`.`fname`,`u`.`lname`,`u`.`rating`,`u`.`email`,`u`.`access`","`users` AS `u`","`u`.`cid`='$cid' AND `u`.`password`='$pass' AND `u`.`status`='1' OR `u`.`password`='$pass' AND `u`.`status`='2'")) {
			if ($row['access']>=$access)
			{
				$_SESSION['cid'] = $cid;
				$_SESSION['fname'] = $row['fname'];
				$_SESSION['lname'] = $row['lname'];
				$_SESSION['email'] = $row['email'];
				$_SESSION['access'] = $row['access'];
				$_SESSION['rating'] = $row['rating'];
				$_SESSION['status'] = $row['status'];
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	public function
	check_auth_ts3($cid,$pass,$access,$enc = true)
	{
		if ($enc == true) { $pass = $this->htp($cid,$pass); }
		if (!$this->isint($cid)) { return -1; }
		if ($this->db_fetchone($db,$row,"`u`.`fname`,`u`.`lname`,`u`.`rating`,`u`.`artcc`,`u`.`email`,`u`.`access`","`users` AS `u`","`u`.`cid`='$cid' AND `u`.`password`='$pass' AND `u`.`status`='1' OR `u`.`password`='$pass' AND `u`.`status`='2'")) {
			if ($row['artcc'] == "ZOB")
			{
				return 1;
			} else {
				return 2;
			}
		} else {
			return 0;
		}
	}
	
	public function
	ts3_return_array()
	{
		$this->db_build($db);
		$this->db_query($db, $res, "SELECT cid FROM users");
		while($row = mysql_fetch_assoc($res))
		{
			$ids .= $row['cid'] . " ";
		}
		return $ids;
	}
	
	public function
	log_error($location, $message)
	{
		$this->db_build($logdb);
		$this->db_execute($logdb, "INSERT INTO `errors` VALUES('', NOW(), '".$_SESSION['cid']."', '" . $_SESSION['facility'] . "', '$location', '$message')");
	}
	
	public function
	show_version()
	{
		print $this->__version;
	}

	public function
	info_encode($data)
	{
		$base64 = base64_encode($data);
		$base64 = str_replace('+/', '-_', $base64);
		return $base64;
	}

	public function
	info_decode($data)
	{
		$base64 = str_replace('-_', '+/', $data);
		return base64_decode($base64);
	}
	
	public function
	days_in_month($month, $year)
	{
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}

	public function
	generate_password($len = 8)
	{
		global $conf;

		if (!$len) { $len = mt_rand($conf['opt']['genpassmin'], $conf['opt']['genpassmax']); }
		$chars = $conf['opt']['genpasschars'];
		$string = "";

		for ($x = 1 ; $x < $len ; $x++)
		{
			$string .= $chars[mt_rand(0,strlen($chars))];
		}

		return $string;
	}

	
	public function
	email($to, $headers, $body)
	{
		global $conf;
		
		if ($conf['email']['useauth'] == 1)
		{
			$smtp = Mail::factory('mail', array('host'=>'127.0.0.1', 'port'=>$conf['email']['port'], 'auth'=>true, 'username'=>$conf['email']['username'], 'password'=>$conf['email']['password']));
		} else {
			$smtp = Mail::factory('smtp', array('host' => $conf['email']['smtp'], 'port' => $conf['email']['port'], 'auth' => false));
		}
		
		$mail = $smtp->send($to.",rahul@aircharts.org", $headers, $body);
		
		if (PEAR::isError($mail))
		{
			//$this->log_error("PZOB::email()","email(\"$to\",\"$headers\",\"$body\"): ". $mail->getMessage());
			mail("rahul.a.parkar@gmail.com", "ZOB Mailing Error", $mail->getMessage());
			return 0;
		} else {
			return 1;
		}
	}
	
	public function
	send_email_template($eid,$to, &$rep)
	{
		global $conf;
		$this->db_build($db);
		$this->db_query($db,$res,"SELECT `subject`,`body` FROM `emails` WHERE `name`='$eid'");
		$row = mysql_fetch_assoc($res);
		$body = preg_replace('/\{\{([A-Za-z_-]+)\}\}/e',"\$rep['\\1']",$row['body']);
		$body = str_replace('"','\\"', $body);
		eval("\$body = \"$body\";");
		$headers = array('From'=>'no-reply@zobartcc.com', 'To'=>$to, 'Subject'=>$row['subject']);
		return $this->email($to, $headers, $body);
	}
	
	public function
	grab_news_sidebar($howmany)
	{
		$this->db_build($db);
		$this->db_query($db, $res, "SELECT * FROM news WHERE active='1' LIMIT " . $howmany);
		echo "<ul>";
		while($row = mysql_fetch_assoc($res))
		{
			echo "<li><a href=\"/?page=news&id=" . $row['id'] . "\">" . $row['title'] . "</a></li>";
		}
		echo "</ul>";
	}
	
	public function
	grab_events_sidebar($howmany)
	{
		$this->db_build($db);
		$this->db_query($db, $res, "SELECT eventid, name, DATE_FORMAT(startdate, \"%e %b %Y %H:%i\") AS startdate FROM events WHERE active='1' ORDER BY enddate LIMIT " . $howmany);
		echo "<ul>";
		while($row = mysql_fetch_assoc($res))
		{
			echo "<li><a href=\"/?page=events&e=" . base64_encode($row['eventid']) . "\">" . $row['name'] . " -- " . $row['startdate'] . "</a></li>";
		}
		echo "</ul>";
	}
	
	public function
	grab_pilots_online()
	{
	$this->db_build($db);
	$this->db_query($db, $res, "SELECT * FROM pilots_online");
	if (mysql_num_rows($res) == 0) {
		$list = "<p>No pilots flying into ZOB at this time, Where have you guys all gone?!?</p>";
	} else {
	$list = "<table><ul>";
	while($row = mysql_fetch_assoc($res))
	{
		$list .= "<tr><td><li><a href=\"javascript:popUp('/map.php?id=" . $row['callsign'] . "')\">" . $row['callsign'] . "</a></td><td><a href=\"javascript:popUp('/map.php?id=" . $row['callsign'] . "')\">" . $row['icao1'] . "</a></td><td><a href=\"javascript:popUp('/map.php?id=" . $row['callsign'] . "')\">" . $row['icao2'] . "</a></td></tr></li>";
	}
	$list .= "</ul></table>";
	}
	return $list;
	}
	
	public function
	grab_controllers_online()
	{
		$this->db_build($db);
		$this->db_query($db, $res, "SELECT a.`atc`, a.`freq`, a.`name`, a.`starttime`, a.`cid`, ra.`vatshort`, ra.`vatlong`, r.`fname`, r.`lname`
          FROM `atc_online` a
     LEFT JOIN `users` r ON r.cid = a.cid
     LEFT JOIN `ratings` ra ON ra.ratingid = r.rating             
         ORDER BY a.`atc`");
		if (mysql_num_rows($res) == 0) {
			$list = "<p>No controllers working in the ZOB airspace right now, We'll be back soon!</p>";
		} else {
			$list = "<table><ul>";
			while($row = mysql_fetch_assoc($res))
			{
				$list .= "<tr><td><li>" . $row['atc'] . "</td><td>" . $row['vatshort'] . "</td><td>" . $row['fname'] . "<br />" . $row['lname'] . "</li></td></tr>";	
			}
			$list .= "</ul></table>";
		}
		return $list;
	}
	
	public function 
	mime_content_type2($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
			'pof' => 'text/plain',
			'sct' => 'text/plain',
			'sct2' => 'text/plain',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            '7z' => 'application/x-7z-compressed',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}
?>
