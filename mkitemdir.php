#!/usr/bin/php
<?php

/*
 mkitemdir.php
 20130814  Paul Cummins
 
 look at tif files in current directory
 make a directory with item part of name
 move item into the directory
 */

//------functions------------------- 
function isDir($dir) {
  $cwd = getcwd();
  $returnValue = false;
  if (@chdir($dir)) {
    chdir($cwd);
    $returnValue = true;
  }
  return $returnValue;
}

function Add2Log($logname,$logstr) 
{ 
$filename = $logname;
  // Let's make sure the file exists and is writable first.
  if (is_writable($filename))  {
	 // we are opening $filename in append mode.
   if (!$handle = fopen($filename, 'a'))  {
     //echo "Cannot open file ($filename)";
     exit;
     }
	 // get date
	 $d=date("Y-m-d ");
	 $line=$d.$logstr;	 
   // Write $logstr to our file.
   if (fwrite($handle, $line) === FALSE)  {
     //echo "Cannot write to file ($filename)";
     exit;
     }
   //echo "Success, wrote ($somecontent) to file ($filename)";
   fclose($handle);
   }
  // end logging routine
   exit();
	}

function listFiles( $from = '.')
{
    if(! is_dir($from))
        return false;
   
    $files = array();
    $dirs = array( $from);
    while( NULL !== ($dir = array_pop( $dirs)))
    {
        if( $dh = opendir($dir))
        {
            while( false !== ($file = readdir($dh)))
            {
                if( $file == '.' || $file == '..')
                    continue;
                $path = $dir . '/' . $file;
                if( is_dir($path))
                    $dirs[] = $path;
                else
                    $files[] = $path;
            }
            closedir($dh);
        }
    }
    return $files;
}

function listdir($start_dir='.') {

  $files = array();
  if (isDir($start_dir)) {
    $fh = opendir($start_dir);
    while (($file = readdir($fh)) !== false) {
      # loop through the files, skipping . and .., and recursing if necessary
      if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
      $filepath = $start_dir . '/' . $file;
      if ( isDir($filepath) )
        $files = array_merge($files, listdir($filepath));
      else
        array_push($files, $filepath);
    }
    closedir($fh);
  } else {
    # false if the function was called with an invalid non-directory argument
    $files = false;
  }
  return $files;
}
//------------- begin main-----------------
//-----

 $files = listFiles('.');
 //$files = listdir('.');
 //print_r($files);

foreach ($files as $fil) {
  //check for file in database
  $end = substr($fil, -4);
  // also count underscores in filename
  $numsep=substr_count($fil, "_");
  // quick check of filename
  if ($end=='.tif') {
    // make array with the period as delimiter
    $allstr=explode("_",$fil);
   // build dir name according to numsep
   if (($numsep==2)||($numsep==3)) {
    // three part name- dir must be parts 1,2
    $dirname=$allstr[0]."_".$allstr[1];
    // four part name- dir must be parts 1,2,3
    if ($numsep==3) $dirname.="_".$allstr[2];
   }//end if 2 or 3
   elseif ($numsep==1) {
    // two part name-like images- dir must be parts 1,2
    //$dirname=$allstr[0]."_".$allstr[1];
   }
   // chk for dir already there
   if (!isDir($dirname)) {
     mkdir($dirname);
     print "made $dirname \n";
   }  
   $new=$dirname."/".$fil;
   print "new=$new\n";
   rename($fil,$new);
   } //end if tif
 }//end foreach

  unset($files);

?>
