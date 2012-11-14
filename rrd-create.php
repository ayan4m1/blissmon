<?php
rrd_create('bliss.rrd', array(
	'--step', '15',
	'--start', '1351773830',
	'DS:deaths:ABSOLUTE:45:0:1000',
	'DS:conns:ABSOLUTE:45:0:100',
	'DS:disconns:ABSOLUTE:45:0:100',
	'RRA:AVERAGE:0.8:4:120',
	'RRA:AVERAGE:0.8:20:288',
	'RRA:AVERAGE:0.8:240:24',
));
?>
