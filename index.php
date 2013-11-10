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
include_once("functions.inc.php");


//var_dump($_SERVER);
$FOLDER="";
if (isset($_GET['folder'])) {
	$FOLDER=$_GET['folder'];
	if (isset($_GET['file'])) {
		ShowHeader();
		echo "<A HREF='" . $_SERVER['SCRIPT_NAME']  . "?folder=" . $_GET['folder'] . "'>Return...</A><BR>\n";
		echo "<SCRIPT type='text/javascript'>
		ret='showpicture.php?file=" . str_replace(" ","%20",$_GET['file']) . "&windowwidth=' + w + '&windowheight=' + h + '&folder=" . str_replace(" ","%20",$_GET['folder']) . "';
		link='showpicture.php?file=" . str_replace(" ","%20",$_GET['file']) . "&folder=" . str_replace(" ","%20",$_GET['folder']) . "';
		document.write('<A HREF=' + link + '><IMG SRC=' + ret + '></A><BR>');
</SCRIPT>\n";
		//echo "<IMG SRC='javascript:image_script()'><BR>\n";
		exit;
	}
	$PATH=$ROOT_PATH . $FOLDER;
} else {
	$PATH=$ROOT_PATH;
}
ShowHeader();
echo "<H2>Carpeta " . $PATH . "</H2>\n";
if (!is_readable($PATH)) {
	echo "El directorio '{$PATH}'no se puede leer.<BR>";
	if (isset($_SERVER['HTTP_REFERER'])) {
		echo "<A HREF='" . $_SERVER['HTTP_REFERER'] . "'>Volver</A>\n";
	}
	exit;
}
$FILES=scandir($PATH);
if (!$FILES) {
	echo "Error al listar '" . $PATH . "'";
	exit;
}
foreach ($FILES as $FILE) {
	if (is_readable($PATH . "/" . $FILE)) {
		if (is_dir($PATH . "/" . $FILE)) {
			if ($FILE!=".") {
				$DIRS[]=$FILE;
			}
		} else {
			if (IsWebImage($PATH . "/" . $FILE)) {
				$FILES2[]=$FILE;
			}
		}
	}
}
$COL_COUNT=0;
echo "<TABLE WIDTH='100%'>\n";
foreach ($DIRS as $DIR) {
	if ($DIR=="..") {
		if ($FOLDER!="" && $FOLDER!="/") {
			echo "<TD><CENTER><A HREF='" . $_SERVER['SCRIPT_NAME'] . "?folder=" . dirname($FOLDER) . "'><IMG SRC='folder" . $THUMB_SIZE . ".png'><BR>
			" . $DIR . "</A></CENTER></TD>\n";
			$COL_COUNT=$COL_COUNT+1;
		}
	} else {
		if ($FOLDER=="/") {
			echo "<TD><CENTER><A HREF='" . $_SERVER['SCRIPT_NAME'] . "?folder=" . $FOLDER . $DIR . "'><IMG SRC='folder" . $THUMB_SIZE . ".png'><BR>
			" . $DIR . "</A>\n";
			echo "<SCRIPT type='text/javascript'>
		ret='slideshow.php?windowwidth=' + w + '&windowheight=' + h + '&folder=" . str_replace(" ","%20",$FOLDER . $DIR) . "';
		document.write('<A HREF=' + ret + '><IMG SRC=\"fullscreen48.png\" WIDTH=24 HEIGHT=24></A><BR>');
</SCRIPT>\n";
			echo "</CENTER></TD>\n";
		} else {
			echo "<TD><CENTER><A HREF='" . $_SERVER['SCRIPT_NAME'] . "?folder=" . $FOLDER . "/" . $DIR . "'><IMG SRC='folder" . $THUMB_SIZE . ".png'><BR>
			" . $DIR . "</A>\n";
			echo "<SCRIPT type='text/javascript'>
		ret='slideshow.php?windowwidth=' + w + '&windowheight=' + h + '&folder=" . str_replace(" ","%20",$FOLDER . $DIR) . "';
		document.write('<A HREF=' + ret + '><IMG SRC=\"fullscreen48.png\" WIDTH=24 HEIGHT=24></A><BR>');
</SCRIPT>\n";
			echo "</CENTER></TD>\n";
		}
		$COL_COUNT=$COL_COUNT+1;
	}
	if ($COL_COUNT==$COLS) {
		echo "</TR>\n<TR>\n";
		$COL_COUNT=0;
	}
}
if (isset($FILES2) and count($FILES2) > 0) {
	foreach ($FILES2 as $FILE) {
		if (stripos($FILE,".thumb_")===False) {
			echo "<TD><CENTER><A HREF='" . $_SERVER['SCRIPT_NAME'] . "?folder=" . $FOLDER . "&file=" . $FILE . "'><IMG WIDTH='" . $THUMB_SIZE . "' HEIGHT='" . $THUMB_SIZE . "' SRC='showpicture.php?folder=" . $FOLDER . "&file=" . $FILE . "&thumb=yes'><BR>" . $FILE . "</A></CENTER></TD>\n";
			$COL_COUNT=$COL_COUNT+1;
		}
		if ($COL_COUNT==$COLS) {
			echo "</TR>\n<TR>\n";
			$COL_COUNT=0;
		}
	}
}
echo "</TABLE>\n";
?>
