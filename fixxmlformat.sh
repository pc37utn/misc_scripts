#!/bin/sh

find */ -name '*.xml' -exec tidy -w 80 -i -q -xml -m -utf8 -asxml {} \;
