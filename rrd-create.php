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
