#!/usr/bin/php
<?php
/*
 renames xml files in directory
 to name based on pid-like identifier
 
*/

//---functions


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
function list_xml($str) {
 $root = simplexml_load_string($str);
 list_node($root);
}
function list_node($node) {
  foreach ($node as $element) {
   echo $element. "\n";
   if ($element->children()) {
     echo "<br/>";
     list_node($element);
   }
  }
}
function savefield($fvalue) {
  global $fieldvalues;
  //check for value in array
  if (!in_array($fvalue,$fieldvalues, true)) {
    $fieldvalues[]=$fvalue;
  }
 return;
}
//   ---------  end functions------------------
$rdir=$namespace="";
//get dir name from command line
if (isset($argv[1])) $rdir=$argv[1];
if (isset($argv[2])) $namespace=$argv[2];
// exit if no file on command line
if ((!isset($rdir))||(empty($rdir))||(!is_dir($rdir))) {
  $rdir='';
  print "*** no valid directory name given ***  \n";
}
if ((!isset($namespace))||(empty($namespace))) {
  $namespace=='';
  print "*** no valid namespace name given ***  \n";
}
if (!$rdir || !$namespace) {
  print "**Error!**\n";
  print "rename2pid.php  directory namespace\n\n";
  print "should be run from directory above a directory that has oaixml files.\n\n";
  print "*** now exiting..... ***  \n";
          exit();
}

print "*** dir= $rdir\n\n";
///---

$files = listFiles("$rdir");
// $files = listdir('.');
//print_r($files);
foreach ($files as $fil) {
  $newfile='';
  if (substr($fil,-4,4)=='.xml') {
    // get basename
    $xbase=basename($fil,'.xml');
    $fparts=explode($fil,'.');
    //$idstr=$fparts[0];
    //$oldtif=$rdir.'/'.$xbase.'.tif';
    $t='';
    //print "**xmlfile=$fil\n";
    $xmlstr = file_get_contents($fil);
    $doc = new SimpleXMLElement($xmlstr);
    /* For each <identifier> node, we echo a separate value. */
    foreach ($doc->identifier as $id) {
      echo "identifier= $id \n";
      $t=$id;
      if (strstr($t,"$namespace".'_')) {
        $newfile=$rdir.'/'.$t.'.xml';
      }
    }// end foreach id
    if (!$newfile) print "Error!  no namespace identifier!\n";
  }// end if xml
  if ($newfile) {
    print "oldxml=$fil  -->  newfile=$newfile\n";
    rename($fil,$newfile);
  }
}// end foreach files
unset($files);
print "\n*** finished***\n";
//----
?>
