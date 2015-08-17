#!/bin/bash

#mysql -usummer -pcamp -e "create database ezsolr character set utf8"
mysql ezsolr -usummer -pcamp < installation/ezsolr.sql
composer install -n
