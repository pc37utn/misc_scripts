#!/usr/bin/php
<?php
/*
for the utrecord project -- to fix order of filenames 

pages were pulled out of  numbered utracord issues to be inserted into utkcomm.

The pages are numbered 003 to 009 and must be renumbered so they start with 001.

  the number of "_" breaks is hard coded for these files
  
  loop through all files looking for 001, then check for 002, etc.
  
  on the first find rename it 001,  the second find rename to 002, etc.
*/
$prefile="";
$chkfile=$newfile="";
//----functions
function npad($intnum,$n) {
return str_pad((int) $intnum,$n,"0",STR_PAD_LEFT);
}

//start at directory you are running script from
$cwd1=getcwd();
// open directory
if ($handle = opendir('.'))  {
  while (false !== ($filename = readdir($handle)))  {
    print "now working with $filename \n";
    // get the last 4 characters of filename
    $end = substr($filename, -4);
    // also count underlines in filename
    $numunderlines=substr_count($filename, "_");
    // quick check of filename
    if (($end=='.jp2')&&($numunderlines==4)) {
      // make array with the period as delimiter
      $allstr=explode(".",$filename);
      //first element is filename without extension
      $pre=$allstr[0];
      $ex=$end;
      // make other parts on the underline
      $parts=explode("_",$pre);
      $coll=$parts[0];
      $vol=$parts[1];
      $yr=$parts[2];
      $pg=$parts[3];
      $ip=$parts[4];
      $prefile=$coll."_".$vol."_".$yr."_";
      print "prefile = $prefile \n";
      //break;
    }
  }// end while
}//end if
print "finished detecting name -- $prefile\n\n";
//start looking for existing files
//$numfiles=exec('ls -1 | wc -l');
$numfiles= ($pg*1)+1;
//$numfiles=100;
print "numfiles = $numfiles \n";
// sequence number
$new=1;
$newnum=npad($new,3);
$newfile=$prefile.$newnum."_".$ip.$ex;
for ($i = 1; $i <= $numfiles; $i++){
  $chknum=npad($i,3);
  $chkfile=$prefile.$chknum."_".$ip.$ex;
  print"looking for file: $chkfile\n";
  if (file_exists($chkfile)) {
    print "\n renaming $chkfile to $newfile \n";
    rename($chkfile,$newfile);
    $new++;
    $newnum=npad($new,3);
    $newfile=$prefile.$newnum."_".$ip.$ex;
  }
  
}//end for

print "\n**finished re-sequencing**\n";
?>
