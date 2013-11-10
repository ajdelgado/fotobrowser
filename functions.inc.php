<?php

 //Copyright (C) 2012 Antonio J. Delgado Linares

 //This program is free software: you can redistribute it and/or modify
 //it under the terms of the GNU General Public License as published by
 //the Free Software Foundation, either version 3 of the License, or
 //(at your option) any later version.

 //This program is distributed in the hope that it will be useful,
 //but WITHOUT ANY WARRANTY; without even the implied warranty of
 //MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //GNU General Public License for more details.

 //You should have received a copy of the GNU General Public License
 //along with this program.  If not, see <http://www.gnu.org/licenses/>.

include_once("config.inc.php");
function ShowHeader($HEADERS="") {
	global $COLS,$FOLDER;
	echo "<HTML>
	<HEAD>
		<TITLE>Fotos en '" . $FOLDER . "'</TITLE>
		<link rel='stylesheet' type='text/css' href='style.css' />
		<STYLE>
			TD {
				width:" . intval(100/$COLS) . "%;
		</STYLE>
		<SCRIPT type='text/javascript'>
		function f_filterResults(n_win, n_docel, n_body) {
			var n_result = n_win ? n_win : 0;
			if (n_docel && (!n_result || (n_result > n_docel)))
				n_result = n_docel;
			return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
		}
		function f_clientHeight() {
			return f_filterResults (
				window.innerHeight ? window.innerHeight : 0,
				document.documentElement ? document.documentElement.clientHeight : 0,
				document.body ? document.body.clientHeight : 0
			);
		}
function f_clientWidth() {
	return f_filterResults (
		window.innerWidth ? window.innerWidth : 0,
		document.documentElement ? document.documentElement.clientWidth : 0,
		document.body ? document.body.clientWidth : 0
	);
}
function f_scrollLeft() {
	return f_filterResults (
		window.pageXOffset ? window.pageXOffset : 0,
		document.documentElement ? document.documentElement.scrollLeft : 0,
		document.body ? document.body.scrollLeft : 0
	);
}
function f_scrollTop() {
	return f_filterResults (
		window.pageYOffset ? window.pageYOffset : 0,
		document.documentElement ? document.documentElement.scrollTop : 0,
		document.body ? document.body.scrollTop : 0
	);
}

			var w = screen.width;
			var h = screen.height;
			var dh = f_clientHeight();
			var dw = f_clientWidth();
		</SCRIPT>
		<script type=\"text/javascript\">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-11672883-1']);
		  _gaq.push(['_setDomainName', 'susurrando.com']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
".$HEADERS."
	</HEAD>
	<BODY>";
}

function ScanFilesRecursive($DIR) {
	$TFILES=scandir($DIR);
	foreach ($TFILES as $FILE) {
		if ( ($FILE!=".") && ($FILE!="..") ) {
			if (is_dir($DIR . "/" . $FILE)) {
				$DIRS[]=$DIR . "/" . $FILE;
			} else {
				if (strpos($FILE,".thumb_")===False) {
					$FILES[]=$DIR . "/" . $FILE;
				}
			}
		}
	}
	while ( count($DIRS)>0 ) {
		$CUR_DIR=array_pop($DIRS);
		$TFILES=scandir($CUR_DIR);
		foreach ($TFILES as $FILE) {
			if (($FILE!=".") && ($FILE!="..")) {
				if (is_dir($CUR_DIR . "/" . $FILE)) {
					$DIRS[]=$CUR_DIR . "/" . $FILE;
				} else {
					if (strpos($FILE,".thumb_")===False) {
						$FILES[]=$CUR_DIR . "/" . $FILE;
					}
				}
			}
		}
	}
	return $FILES;
}
function WhatFileType($FILE) {
	$LASTLINE=exec("/usr/bin/file '" . $FILE . "'",$ARETURNED,$RESULT);
	if ($RESULT != 0) {
		return false;
	}
	foreach($ARETURNED as $RETURNED) {
		$LASTLINE=$RETURNED;
	}
	$ALASTLINE=split(":",$LASTLINE);
	return $ALASTLIE[1];
}
function IsWebImage($FILE) {
	$LASTLINE=exec("/usr/bin/file '" . $FILE . "'",$ARETURNED,$RESULT);
	if ($RESULT != 0) {
		return false;
	}
	foreach ($ARETURNED as $RETURNED) {
		if (strpos($RETURNED,"JPEG image data")>-1) {
			return true;
		} elseif (strpos($RETURNED,"PNG image")>-1) {
			return true;
		} elseif (strpos($RETURNED,"GIF image data")>-1) {
			return true;
		} elseif (strpos($RETURNED,"MPEG" )>-1) {
			return true;
		}
	}
	return false;
}
?>
