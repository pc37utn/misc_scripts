#!/usr/bin/php
<?php
/*
renames file to name based on non-http identifier
  option: removes http identifier for ingest metadata

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
$rdir="";
//get dir name from command line
if (isset($argv[1])) $rdir=$argv[1];
// exit if no file on command line
if ((!isset($rdir))||(empty($rdir))||(!is_dir($rdir))) {
  print "**Error!**\n";
  print "fixmods.php  directory\n\n";
  print "should be run from directory above a directory that has oaixml files.\n\n";
  print "*** no valid directory name given *** exiting... \n";
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
    $idstr=$fparts[0];
    $oldtif=$rdir.'/'.$xbase.'.tif';
    $t='';
    //print "**xmlfile=$fil\n";
    $xmlstr = file_get_contents($fil);
    $doc = new SimpleXMLElement($xmlstr);
    /* For each <identifier> node, we echo a separate value. */
    foreach ($doc->identifier as $id) {
      echo "identifier= $id \n";
      $t=$id;
      if (strstr($t,'adams_')) {
        $newfile=$rdir.'/'.$t.'.xml';
      }
    }// end foreach id

  }// end if xml
  print "oldxml=$fil  -->  newfile=$newfile\n";
  rename($fil,$newfile);
}// end foreach files
unset($files);
print "\n*** finished***\n";
//----
?>
