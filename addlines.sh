#!/bin/bash
# adds linefeed in oai_dc file if needed
#
sed -e s/"><dc:"/">\n<dc:"/g -i *.xml
sed -e s/"></oai_dc:"/">\n</oai_dc:"/g -i *.xml
echo "linefeeds added to xml files!"
sed -e s/"\" xmlns:"/"\"\n xmlns:"/g -i *.xml
sed -e s/"\" xsi:"/"\"\n xsi:"/g -i *.xml

