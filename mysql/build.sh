#!/bin/sh
set -e
set -x

BRANCH=$1

if [ -z "$BRANCH"];
then
  BRANCH='abcdefg'
fi

VOLUME_WWW="/tmp/velotur/"

echo "=> Cloning app [branch $BRANCH]..."
git clone https://github.com/alex-k/velotur.git --depth 1 --branch $BRANCH --single-branch $VOLUME_WWW

echo "=> Starting mysql..."
service mysql start

echo "=> Loading data to mysql..."
cd $VOLUME_WWW/mysql && mysql < mysql_user.sql
cd $VOLUME_WWW/mysql && mysql < mysql_data.sql

echo "=> Ending mysql..."
service mysql stop

echo "=> Removing data ..."
rm -fr $VOLUME_WWW



