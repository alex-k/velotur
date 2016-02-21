#!/bin/sh
set -e
set -x


echo "=> Dump production DB..."
#mysqldump --skip-triggers -h velotur.ru -u activeinfo_newvt -pvt123 activeinfo_newvt > activeinfo_newvt.sql

echo "=> Dump production DB triggers..."
#mysqldump --no-create-info --no-data --no-create-db --skip-opt -h velotur.ru -u activeinfo_newvt -pvt123 activeinfo_newvt > activeinfo_newvt_triggers.sql

echo "=> Remove DEFINER from triggers..."
sed -i 's/DEFINER=[^*]*\*/\*/g' activeinfo_newvt_triggers.sql


echo "=> Start mysql..."
service mysql start

echo "=> Create users..."
mysql < mysql_user.sql

echo "=> Load data..."
mysql activeinfo_newvt < activeinfo_newvt.sql

echo "=> Strip data..."
mysql activeinfo_newvt < strip_user_data.sql

echo "=> Load triggers..."
mysql activeinfo_newvt < activeinfo_newvt_triggers.sql

echo "=> Delete dump file ..."
rm -v activeinfo_newvt.sql activeinfo_newvt_triggers.sql

echo "=> Stop mysql..."
service mysql stop

