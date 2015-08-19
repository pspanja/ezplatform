#!/bin/sh

java -DSTOP.PORT=5015 -DSTOP.KEY=ezsc -Djetty.home=/opt/solr/example -jar /opt/solr/example/start.jar --stop
