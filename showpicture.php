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
include('simpleimage.php');


function ShowPicture($FILE) {
	if(!file_exists($FILE))	{
		// File doesn't exist, output error
		ShowHeader();
		echo "file '" . $FILE . "' not found";
		exit;
	} else {
		if (!IsWebImage($FILE)) {
			ShowHeader();
			echo "Is not a picture";
			exit;
		}
		if (strpos(FileType($FILE),"MPEG")>-1) {
			ShowHeader("<link rel='stylesheet' type='text/css' href='./vplayer/video.css' media='screen' />
					<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js'></script>
		<script type='text/javascript' src='./vplayer/video.js'></script>
");
			echo "<video id=\"video\" preload=\"preload\" width=\"621\" height=\"264\">
			<source src=\"{$FILE}\" type='video/mpeg;' />
			<track kind=\"captions\" src=\"./vplayer/oceans.vtt\" />
		</video>
		
			<script type=\"text/javascript\">
			$(document).ready(function(){
				var v = $('#video'),
					t = v.find('track'),
					video = new videoPlayer(v);
				video.track = new videoTrack(v,t,$container:video.$wrap);
			});
		</script>
";
		} else {
			// Set headers
			header("Cache-Control: public");
			//header("Content-Description: File Transfer");
			//header("Content-Disposition: attachment; filename=$file");
			header("Content-Type: image/jpg");
			header("Content-Transfer-Encoding: binary");
		
			// Read the file from disk
			readfile($FILE);
		}
	}
}
function ShowThumb($FILE,$SIZE,$FRAME) {
	$FOLDER=dirname($FILE);
	$SFILE=basename($FILE);
	$THUMBFILE=$FOLDER . "/.thumb_" . $SIZE . "_" . $SFILE;
	if(!file_exists($FILE))	{
		// File doesn't exist, output error
		ShowHeader();
		echo 'file '. $FILE . ' not found';
		if (file_exists($THUMBFILE)) {
			echo " but thumbnail " . $THUMBFILE . " exists...";
		}
		var_dump($_GET);
		exit;
	} else {
		if (!IsWebImage($FILE)) {
			ShowHeader();
			echo "Is not a picture";
			exit;
		}
		$image = new SimpleImage();
		$image->load($FILE);
		if (!$image->image) {
			ShowHeader();
			echo "Error reading picture";
			exit;
		}
		if ($image->image_type == IMAGETYPE_JPEG) {
			$content_type="Content-Type: image/jpg";
		} elseif ($image->image_type == IMAGETYPE_GIF) {
			$content_type="Content-Type: image/gif";
		} elseif ($image->image_type == IMAGETYPE_PNG) {
			$content_type="Content-Type: image/png";
		} else {
			ShowHeader();
			echo "Unknow file type";
		}
		if (is_readable($FILE)) {
			// Read the file from disk
			if (!file_exists($THUMBFILE)) {
				if (!$image->resizeToWidth($SIZE)) {
					ShowHeader();
					echo "Error resizing '{$image->error}'";
					exit;
				}
				$image->save($THUMBFILE);
				if (!file_exists($THUMBFILE)) {
					ShowHeader();
					echo "Error resizing picture";
					exit;
				}
				/*if ($FRAME) {
					$COMMAND=escapeshellcmd("/usr/bin/convert " . $FILE . " -resize " . $SIZE . "x" . $SIZE . " -size " . $SIZE . "x" . $SIZE . " xc:black +swap -gravity center -composite " . $THUMBFILE );
				} else {
					$COMMAND=escapeshellcmd("/usr/bin/convert " . $FILE . " -resize " . $SIZE . "x" . $SIZE . " \"" . $THUMBFILE . "\"");
				}
				//$RETURN=passthru($COMMAND,$RET);
				$RETURN=exec($COMMAND,$OUT,$RET);
				if ($RET!=0) {
					ShowHeader();
					echo "Error " . $RET . " while generating thumbnail for '{$FILE}'.<BR/>Command : '" . $COMMAND . "'<BR/>Output:\n";
					foreach ($OUT as $LINE) {
						echo $LINE . "<BR/>\n";
					}
					exit;
				}*/
		}
		
		$content=file_get_contents($THUMBFILE);
		if (!$content) {
			echo "Error reading thumbnail '{$THUMBFILE}'";
			exit;
		}
		// Set headers
		header("Cache-Control: public");
		header($content_type);
		header("Content-Transfer-Encoding: binary");
		echo $content;		
	} else {
		ShowHeader();
		echo "Unable to read file '{$FILE}'. ";
		$ME=exec("whoami");
		echo "I'm " . $ME;
		exit;
	}
	}
}

if (isset($_GET['file'])) {
	if (isset($_GET['folder'])) {
		$FOLDER=$_GET['folder'];
		
		//echo "<H1>Showing file '" . $ROOT_PATH . $_GET['folder'] . "/" . $_GET['file'] . "'</H1>\n";
		if (isset($_GET['thumb'])) {
			ShowThumb($ROOT_PATH . $_GET['folder'] . "/" . $_GET['file'],$THUMB_SIZE,True);
		} else {
			if (isset($_GET['windowheight']) && isset($_GET['windowwidth'])) {
				if ($_GET['windowheight']<$_GET['windowwidth']) {
					$MAX_SIZE=$_GET['windowheight']*85/100;
				} else {
					$MAX_SIZE=$_GET['windowwidth']*85/100;
				}
				ShowThumb($ROOT_PATH . "/" . $_GET['folder'] . "/" . $_GET['file'],$MAX_SIZE,False);
			} else {
				ShowPicture($ROOT_PATH . "/" . $_GET['folder'] . "/" . $_GET['file']);
			}
		}
	}
} else {
	echo "You must specify a file to show";
}
?>
