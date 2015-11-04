#!/usr/bin/php
<?php

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

function listFiles( $from = '.') {
  if(! is_dir($from)) return false;
  $files = array();
  $dirs = array( $from);
  while( NULL !== ($dir = array_pop( $dirs))) {
    if( $dh = opendir($dir)) {
      while( false !== ($file = readdir($dh))) {
        if( $file == '.' || $file == '..') continue;
        $path = $dir . '/' . $file;
        if( is_dir($path)) $dirs[] = $path;
        else $files[] = $path;
      }// end while
      closedir($dh);
    }//end if
  }// end if
  return $files;
}

$dfiles = listFiles(".");
foreach ($dfiles as $dfil) {
  $dirname=$xnew=$new=$meta='';
  if (($dfil=='.')||($dfil=='..')) continue;
  $end = substr($dfil, -4);
  if ($end=='.xml') {
    $xbase=basename($dfil,'.xml');
    print "xbase = $xbase \n";
    // check for kind of metadata, DC or MODS
    $xml = file_get_contents("$dfil");
    $sxe = new SimpleXMLElement($xml);
    $namespaces = $sxe->getDocNamespaces(TRUE);
    if (isset($namespaces['mods'])) print "MODS \n";
    if (isset($namespaces['dc'])) print "DC \n";
  }// end if xml
}//end foreach
unset($dfiles);
?>

