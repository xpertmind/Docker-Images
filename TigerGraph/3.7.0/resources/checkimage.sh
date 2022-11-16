#!/bin/bash

# (c) 2021 TigerGraph - Bruno Šimić (bruno@tigergraph.com)
# at first start external data folder would be empty

# start docker-compose as deamon
INSTALL_DIR="/opt/tigergraph"
TG_VER_SHORT="3.3.0"
DATA_DIR="/opt/tigergraph/data"
INIT_DATA_FILE="/opt/tigergraph/tmp/initdata.tgz"

# check if data folder is empty (mounted from the host!)
if [ ! -d "$DATA_DIR" ]; then
    mkdir -p $DATA_DIR
    chown -R tigergraph:tigergraph $INSTALL_DIR
fi

if find -- "$DATA_DIR" -prune -type d -empty | grep -q .; then
  cd $DATA_DIR
  tar xfz $INIT_DATA_FILE
  chown -R tigergraph:tigergraph *
  # enable rest++ auth
#  su - tigergraph -c "$INSTALL_DIR/app/$TG_VER_SHORT/cmd/gadmin config set RESTPP.Factory.EnableAuth true"
#  su - tigergraph -c "$INSTALL_DIR/app/$TG_VER_SHORT/cmd/gadmin config apply -y"
#  su - tigergraph -c "$INSTALL_DIR/app/$TG_VER_SHORT/cmd/gadmin restart restpp nginx gui -y"
#  su - tigergraph -c "$INSTALL_DIR/app/$TG_VER_SHORT/cmd/gadmin stop all -y"
#  su - tigergraph -c "$INSTALL_DIR/app/$TG_VER_SHORT/cmd/gadmin start all"
fi
