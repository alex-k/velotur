FROM tutum/mysql:5.6
MAINTAINER Alex Kochetov <alex@kochetov.com>

RUN apt-get update && apt-get -y install git vim

RUN date > build.log

#ADD build.sh /build.sh
#ADD mysql /tmp/velotur/mysql
#RUN chmod 755 /*.sh

#RUN /build.sh $VT_BRANCH

EXPOSE 3306
CMD ["/run.sh"]
