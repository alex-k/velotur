FROM ubuntu:trusty  

RUN apt-get update
ADD exim.debconf /root/
RUN debconf-set-selections < /root/exim.debconf && DEBIAN_FRONTEND=noninteractive apt-get install -y exim4
#RUN cat /root/passwd.client > /etc/exim4/passwd.client
ADD passwd.client  /etc/exim4/passwd.client

EXPOSE 25
CMD ["/usr/sbin/exim4", "-bdf", "-v", "-q30m"]
