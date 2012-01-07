<?php
/* 
PDBA.php -- Parkar's Database Abstraction Class
Copyright (C) 2011 Rahul A. Parkar

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class PDBA
{
	private $__database_user = "zobartcc_zob";
	private $__database_pass = "zDcD0@UiZSQT";
	private $__database_db = "zobartcc_zob";
	private $__database_server = "localhost";

	public function
	__construct()
	{
		global $conf;

		$this->__database_u = "zobartcc_zob";
		$this->__database_p = "zDcD0@UiZSQT";
		$this->__database_d = "zobartcc_zob";
		$this->__database_s = "localhost";
	}

        public function
        db_build(&$db)
        {
                $db = mysql_connect($this->__database_s, $this->__database_u, $this->__database_p);
                mysql_select_db($this->__database_d);

                if ($db) { return 1; }
                return 0;
        }

        public function
        db_done(&$db)
        {
                mysql_close($db);

                if (!isset($db)) { return 1; }
                return 0;
        }

        public function
        db_execute(&$db, $query)
        {
		//echo "'$query'";
                mysql_query($query, $db) or die("Failed: " . mysql_error());

                return mysql_affected_rows($db);
        }

        public function
        db_fetchone(&$db, &$row, $cols, $table, $filter)
        {
                $query = "SELECT $cols FROM $table";
                if (isset($filter)) { $query .= " WHERE $filter"; }
                $query .= " LIMIT 1";

                if (!isset($db)) { $this->db_build($db); }

                if ($db == 0) { return 0; }

                if ($this->db_query($db, $res, $query)) {
                        $row = mysql_fetch_assoc($res);
                } else { return 0; }

                return 1;
        }
		
		public function
		fetch_row($query)
		{
			db_query($db, $result, $query);
			sql_fetchone($result, $row);
			return $row;
		}
		
		public function
		sql_fetchone(&$res, &$row)
		{
			$row = mysql_fetch_assoc($result);
			@mysql_free_result($result);
		}

        public function
        db_query(&$db, &$res, $query)
        {
                $res = mysql_query($query, $db) or die("Failed: " . mysql_error());

                if ($res && mysql_num_rows($res) > 0) { return 1; }
                return 0;
        }

        public function
        db_safe($str)
        {
                $search=array("\\","\0","\n","\r","\x1a","'",'"');
                $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
                return str_replace($search,$replace,$str);
        }
}