#!/bin/bash

# (c) 2022 TigerGraph - Bruno Šimić (bruno@tigergraph.com)
# set the proper IP address
APP_DIR="/opt/tigergraph/app/3.6.2/cmd/"

${APP_DIR}/gadmin config set System.HostList '[{"Hostname":"'$(ip a | grep "inet " | awk 'FNR == 2 {print $2}' | awk -F "/" '{print $1}')'","ID":"m1","Region":""}]'
${APP_DIR}/gadmin config apply -y
${APP_DIR}/gadmin start all
