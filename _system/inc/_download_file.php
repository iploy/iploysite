<?php

###############################################################
# File Download 1.3
###############################################################
# Visit http://www.zubrag.com/scripts/ for updates
###############################################################
# Sample call:
#    download.php?f=phptutorial.zip
#
# Sample call (browser will try to save with new file name):
#    download.php?f=phptutorial.zip&fc=php123tutorial.zip
###############################################################

// Allow direct file download (hotlinking)?
// Empty - allow hotlinking
// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
define('ALLOWED_REFERRER', $_SERVER['HTTP_HOST']);

// Download folder, i.e. folder where you keep all files for download.
// MUST end with slash (i.e. "/" )
define('BASE_DIR','./');

// log downloads?  true/false
define('LOG_DOWNLOADS',false);

// log file name
define('LOG_FILE','downloads.log');

// Allowed extensions list in format 'extension' => 'mime type'
// If myme type is set to empty string then script will try to detect mime type 
// itself, which would only work if you have Mimetype or Fileinfo extensions
// installed on server.
/*
$allowed_ext = array (
  // archives
  'zip' => 'application/zip',
  // documents
  'pdf' => 'application/pdf',
  'doc' => 'application/msword',
  'xls' => 'application/vnd.ms-excel',
  'ppt' => 'application/vnd.ms-powerpoint',
  'txt' => '',
  // executables
  'exe' => 'application/octet-stream',
  // images
  'gif' => 'image/gif',
  'png' => 'image/png',
  'jpg' => 'image/jpeg',
  'jpeg' => 'image/jpeg',
  // audio
  'mp3' => 'audio/mpeg',
  'wav' => 'audio/x-wav',
  // video
  'mpeg' => 'video/mpeg',
  'mpg' => 'video/mpeg',
  'mpe' => 'video/mpeg',
  'mov' => 'video/quicktime',
  'avi' => 'video/x-msvideo'
);
*/

$disallowed_ext = explode("|",DISALLOWED_FILETYPES) ;

####################################################################
###  DO NOT CHANGE BELOW
####################################################################

// If hotlinking not allowed then make hackers think there are some server problems
if (ALLOWED_REFERRER !== ''
&& (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
) {
  die("EXTERNAL LINKING OF OUR FILES IS DISABLED<br />Browsing our <a href=\"index.php\" >Homepage</a> may lead you to the file correctly.");
}

// Make sure program execution doesn't time out
// Set maximum script execution time in seconds (0 means no limit)
set_time_limit(0);

if (!isset($download_file) || empty($download_file)) {
  die("Please specify file name for download.");
}

// Get real file name.
// Remove any path info to avoid hacking by adding relative path, etc.
$fname = stripslashes(basename($download_file)) ;

if (!is_file($download_file)) {
  die("File does not exist. Make sure you specified correct file name."); 
}


// file size in bytes
$fsize = filesize($download_file); 

// file extension
$fext = strtolower(substr(strrchr($fname,"."),1));

// check if allowed extension
/*
if (!array_key_exists($fext, $allowed_ext)) {
  die("Not allowed file type."); 
}
*/

if(in_array($fext,$disallowed_ext)||!in_array($fext,$allowed_array)){
  die("Not allowed file type."); 
}

// get mime type
if ($allowed_ext[$fext] == '') {
  $mtype = '';
  // mime type is not set, get from server settings
  if (function_exists('mime_content_type')) {
    $mtype = mime_content_type($download_file);
  }
  else if (function_exists('finfo_file')) {
    $finfo = finfo_open(FILEINFO_MIME); // return mime type
    $mtype = finfo_file($finfo, $download_file);
    finfo_close($finfo);  
  }
  if ($mtype == '') {
    $mtype = "application/force-download";
  }
}
else {
  // get mime type defined by admin
  $mtype = $allowed_ext[$fext];
}

// Browser will try to save file with this filename, regardless original filename.
// You can override it if needed.

if (!isset($_GET['fc']) || empty($_GET['fc'])) {
  $asfname = $fname;
}
else {
  // remove some bad chars
  $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
  if ($asfname === '') $asfname = 'NoName';
}

// set headers
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $mtype");
header("Content-Disposition: attachment; filename=\"$asfname\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);

// download
// @readfile($download_file);
$file = @fopen($download_file,"rb");
if ($file) {
  while(!feof($file)) {
    print(fread($file, 1024*8));
    flush();
    if (connection_status()!=0) {
      @fclose($file);
      die();
    }
  }
  @fclose($file);
}

// log downloads
if (!LOG_DOWNLOADS) die();

$f = @fopen(LOG_FILE, 'a+');
if ($f) {
  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
  @fclose($f);
}

?>