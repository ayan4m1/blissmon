<?php
/*
	This file is part of Blissmon.

	Blissmon is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	Blissmon is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Blissmon.  If not, see <http://www.gnu.org/licenses/>.
*/

mysql_connect('localhost', 'dayz', 'dayz');
mysql_select_db('dayz');

define("LOG_CODE_LOGIN", 1);
define("LOG_CODE_DISCONNECT", 5);

define("STEP_VALUE", 15);

$rrd_update = rrd_lastupdate('bliss.rrd');
$rrd_update = $rrd_update['last_update'];

$logfile = file_get_contents('HiveExt.log');
$logfile = explode("\n", $logfile);
$deaths = array();
foreach($logfile as $line) {
	#if (strpos($line, 'Method: 202') !== FALSE) {
	if (strpos($line, 'set `is_dead` = 1') !== FALSE) {
		#$matched = preg_match("/(\d{4})\-(\d{2})\-(\d{2})\s(\d{2}):(\d{2}):(\d{2})\s.*Params:\s([0-9A-Z]{1,}):0:/", $line, $matches);
		$matched = preg_match("/(\d{4}\-\d{2}\-\d{2}\s\d{2}:\d{2}:\d{2}).*/", $line, $matches);
		if ($matched) {
			//Parse timestamp from log format (YYYY-MM-DD HH:MM:SS)
			$ts = strtotime($matches[1]);

			//Skip if this is old data
			if ($ts <= $rrd_update) {
				echo "Skipping data point $ts\n";
				continue;
			}

			//Fit timestamp into buckets
			$ts -= ($ts % STEP_VALUE);
			if (isset($deaths[$ts])) {
				$deaths[$ts]++;
			} else {
				$deaths[$ts] = 1;
			}
		}
	}
}

ksort($deaths);
$stamps = array_keys($deaths);
$start_ts = array_shift($stamps);
$end_ts = array_pop($stamps);

if ($start_ts == $end_ts) {
	echo "FATAL: Start and end timestamps are the same\n";
	die;
}

for($i = $start_ts; $i <= $end_ts; $i += STEP_VALUE) {
	$conns = $disconns = 0;
	$i_end = $i + STEP_VALUE;
	$result = mysql_query("select count(*), log_code_id from log_entry where log_code_id in (1, 5) and unix_timestamp(created) between $i and $i_end group by log_code_id");
	if ($result !== FALSE) {
		for($j = 0; $j < mysql_num_rows($result); $j++) {
			$row = mysql_fetch_array($result);
			if ($row[1] == 1) {
				$conns = $row[0];
			} elseif ($row[1] == 5) {
				$disconns = $row[0];
			}
		}
	}

	rrd_update('bliss.rrd', array("$i:" . (isset($deaths[$i]) ? $deaths[$i] : 0) . ":" . $conns . ":" . $disconns));
	if ($conns > 0 || $disconns > 0 || isset($deaths[$i])) {
		echo "$i:" . (isset($deaths[$i]) ? $deaths[$i] : 0) . ":" . $conns . ":" . $disconns . "\n";
	}
}

?>
