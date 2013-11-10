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

ShowHeader();

if (!isset($_GET['folder'])) {
	echo "No folder selected.";
	exit;
}
$FOLDER=$_GET['folder'];
$WIDTH=$_GET['windowwidth'];
$HEIGHT=$_GET['windowheight'];

$FILES=ScanFilesRecursive($ROOT_PATH . $FOLDER);
$FILE=$FILES[0];
	echo "<SCRIPT type='text/javascript'>
	iframew=dw-20;
	iframeh=dh-20;
	link='showpicture.php?windowwidth=' + iframew + '&windowheight=' + iframeh + '&file=" . str_replace(" ","%20",basename($FILE)) . "&folder=" . str_replace(" ","%20",str_replace($ROOT_PATH,"",dirname($FILE))) . "';
	document.write('<IFRAME WIDTH=' + iframew + ' HEIGHT=' + iframeh + ' SRC=' + link + '><P>Your browser does not support iframes.</P></IFRAME>');
</SCRIPT>\n";

?>
