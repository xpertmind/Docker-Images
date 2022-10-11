#!/bin/bash

# (c) 2022 TigerGraph - Bruno Šimić (bruno@tigergraph.com)
# at first start external data folder would be empty

# start docker-compose as deamon
INSTALL_DIR="/opt/tigergraph"
TG_VER_SHORT="3.6.2"
DATA_DIR="/opt/tigergraph/data"
INIT_DATA_FILE="/opt/tigergraph/tmp/initdata.tgz"
IP_ADDRESS=$(hostname -I | awk '{print $1}')

# check if data folder is empty -> first start
if [ ! -d "$DATA_DIR" ]; then
    mkdir -p $DATA_DIR
    chown -R tigergraph:tigergraph $INSTALL_DIR
fi

if find -- "$DATA_DIR" -prune -type d -empty | grep -q .; then
  cd $DATA_DIR
  tar xfz $INIT_DATA_FILE
  chown -R tigergraph:tigergraph *
fi

/usr/sbin/sshd
su - tigergraph bash -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin start all"

hostname -I | awk '{print $1}' > ${INSTALL_DIR}/tmp/ipconfig.json
#[{"Hostname":"127.0.0.1","ID":"m1","Region":""}]
su - tigergraph bash -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set System.HostList '[{\"Hostname\":\"${IP_ADDRESS}\",\"ID\":\"m1\",\"Region\":\"Docker\"}]'"
su - tigergraph bash -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config apply -y"
su - tigergraph bash -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin start all"
