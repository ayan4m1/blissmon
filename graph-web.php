<?php
/*  Bliss status grapher
    Copyright 2012 ayan4m1 (andrew@bulletlogic.com)*/

$file = time() . '.png';

rrd_graph($file, array(
#	'--start', '1352781165',
#	'--end', '1352856645',
	'--title', 'Bliss Server Activity',
	'--vertical-label', '# events',
	'--lower-limit', '0',
	'--slope-mode',
	'--grid-dash', '1:3',
	'-w', '700', '-h', '300',
	'--legend-position', 'east',
	'-X', '-3',
	'DEF:death=bliss.rrd:deaths:AVERAGE',
	'DEF:conn=bliss.rrd:conns:AVERAGE',
	'DEF:disconn=bliss.rrd:disconns:AVERAGE',
	'LINE1:death#A40802:Deaths\n',
	'LINE1:conn#A0D4A4:Conns\n',
	'LINE1:disconn#025D8C:Disconns\n'
/*	'DEF:obs=bliss-graph.rrd:pdeath:AVERAGE',
	'DEF:pred=bliss-graph.rrd:pdeath:HWPREDICT',
	'DEF:dev=bliss-graph.rrd:pdeath:DEVPREDICT',
	'DEF:fail=bliss-graph.rrd:pdeath:FAILURES',
	'TICK:fail#ffffa0:1.0:Failures\:Player Deaths',
	'CDEF:scaledobs=obs,8,*',
	'CDEF:upper=pred,dev,2,*,+',
	'CDEF:lower=pred,dev,2,*,-',
	'CDEF:scaledupper=upper,8,*',
	'CDEF:scaledlower=lower,8,*',
	'LINE2:scaledobs#0000ff:Player Deaths',
	'LINE1:scaledupper#ff0000:Upper Bound Player Deaths',
	'LINE1:scaledlower#ff0000:Lower Bound Player Deaths'
*/
));

header('Content-Type: image/png');
header('Content-Length: ' . filesize($file));
readfile($file);

?>
