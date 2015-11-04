#!/bin/sh
mkdir trimmed
for i in *pdf ; do
  echo "file= $i ";
  pdftk ./$i cat 2-end output ./trimmed/$i
done
