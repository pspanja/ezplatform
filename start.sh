#!/bin/sh

java  -DSTOP.PORT=5015 -DSTOP.KEY=ezsc -Djetty.home=/opt/solr/example -Djetty.port=5014 -Dsolr.solr.home=/var/www/summercamp/workshops/ezsolr/solr.multicore/ -jar /opt/solr/example/start.jar > /dev/null 2>&1 &
