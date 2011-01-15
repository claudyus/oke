<?php

/* Just change this directory and point it to oke 
   directory. KEEP the final / */

$dir = "/home/claudyus/oke/";

/* end of configuration */
/* DON'T EDIT BELOW THIS LINE */


// based on http://elouai.com/force-download.php
function output_file($filename)
{
   // required for IE, otherwise Content-disposition is ignored
   if(ini_get('zlib.output_compression'))
     ini_set('zlib.output_compression', 'Off');

   // addition by Jorg Weske
   $file_extension = strtolower(substr(strrchr($filename,"."),1));

   switch( $file_extension )
   {
     case "gz": $ctype="application/x-gzip"; break;
     case "patch": $ctype="text/x-diff"; break;
     default: $ctype="application/force-download";
   }
   
   header("Pragma: public"); // required
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   header("Cache-Control: private",false); // required for certain browsers 
   header("Content-Type: $ctype");
   
   // change, added quotes to allow spaces in filenames, by Rajkumar Singh
   header("Content-Disposition: attachment; filename=\"" . basename($filename)."\";" );
   header("Content-Transfer-Encoding: binary");
   header("Content-Length: ".filesize($filename));
   readfile("$filename");
   exit();   
}
   
if (isset($_GET["get"]))
   output_file($dir.$_GET["get"] );
else {

   echo "<h1>OpenWrt ExtractED Kernel</h1>";
   
   // Open a known directory, and proceed to read its contents
   if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
         while (($file = readdir($dh)) !== false) {
            if (strncmp($file, "linux", 4)==0 ||
               strncmp($file, "patch", 5)==0 ) {
               echo "<a href=?get=$file>$file</a><br>";
            }
         }
         closedir($dh);
      }
   }
}
