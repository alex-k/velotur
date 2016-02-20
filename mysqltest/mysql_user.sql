create database if not exists activeinfo_newvt;
grant all privileges on activeinfo_newvt.* to activeinfo_newvt@'%' identified by 'vt123' with grant option;
grant all privileges on activeinfo_newvt.* to root@'%' identified by 'nukva' with grant option;
grant super on *.* to activeinfo_newvt@'%' identified by 'vt123' with grant option;
