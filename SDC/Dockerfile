FROM streamsets/datacollector:5.6.0
USER root
RUN DEBIAN_FRONTEND=noninteractive apt-get -qq update && \
  apt-get install -y samba-client samba-common cifs-utils 