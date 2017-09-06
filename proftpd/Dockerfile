FROM ubuntu

RUN apt-get update -y
RUN apt-get install -y proftpd

ADD proftpd.conf /etc/proftpd/proftpd.conf
RUN mkdir -m 0777 /ftp

EXPOSE 21
EXPOSE 20

ENTRYPOINT proftpd --nodaemon
