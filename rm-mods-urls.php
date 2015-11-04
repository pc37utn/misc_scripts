#!/usr/bin/php
<?php
/*
  renames file to copy of xml plus '.new' on end
 removes http identifier for ingest metadata

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
  print "rm-mods-urls.php  directory\n\n";
  print "should be run from directory above a directory that has mods oaixml files.\n\n";
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
    //print "**xmlfile=$fil\n";
    $xmlstr = file_get_contents($fil);
    $doc = new SimpleXMLElement($xmlstr);
    // easier way to remove nodes
    unset($doc->location->url); // remove url inside location
  }// end if xml
  $newfile="$fil".".new";
  print "oldxml=$fil  -->  $newfile \n";
  //rename($fil,$newfile);
  echo $doc->asXML("$newfile");
}// end foreach files
unset($files);
print "\n*** finished***\n";
//----
?>
