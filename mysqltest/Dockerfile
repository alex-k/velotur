FROM tutum/mysql:5.6
MAINTAINER Alex Kochetov <alex@kochetov.com>

RUN date > build.log

#ADD activeinfo_newvt.sql /activeinfo_newvt.sql
#ADD activeinfo_newvt_triggers.sql /activeinfo_newvt_triggers.sql

ADD mysql_user.sql /mysql_user.sql
ADD strip_user_data.sql /strip_user_data.sql
ADD build.sh /build.sh

RUN cd / && sh ./build.sh 

EXPOSE 3306
CMD ["/run.sh"]
