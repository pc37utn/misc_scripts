#!/bin/bash
#
# removes metadata tags around old dlxs xml files
sed -e s/"<metadata>"//g -i *.xml
sed -e s/"<\/metadata>"//g -i *.xml
echo "metadata tags removed from xml files!"

