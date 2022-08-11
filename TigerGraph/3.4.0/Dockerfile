FROM ubuntu:18.04 as build

ENV TG_VER_SHORT 3.4.0
ENV TG_VERSION ${TG_VER_SHORT}-offline
ENV DOWNL_URL https://dl.tigergraph.com/enterprise-edition/prebuild/tigergraph-${TG_VERSION}-11232020.tar.gz
ENV INSTALL_DIR /opt/tigergraph

# Crack the CPU and MEM check
COPY ./resources/* /tmp/

RUN DEBIAN_FRONTEND=noninteractive apt-get -qq update && \
  apt-get install -y --no-install-recommends sudo iputils-ping qemu-system-x86 curl sed nano iproute2 net-tools cron ntp locales tar uuid-runtime openssh-client openssh-server libsasl2-dev > /dev/null && \
#  apt-get clean -y && \
  rm -Rf /var/lib/apt/lists/* /var/cache/apt/archives /var/log/apt && \
# adding user and sudo
  useradd -ms /bin/bash tigergraph && \
  mkdir /var/run/sshd && \
  echo 'root:root' | chpasswd && \
  echo 'tigergraph:tigergraph' | chpasswd && \
  sed -i 's/PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config && \
  sed -i 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' /etc/pam.d/sshd && \
  echo "tigergraph    ALL=(ALL)       NOPASSWD: ALL" >> /etc/sudoers && \
# preparing installation file
/usr/sbin/sshd && \
  cd /home/tigergraph/ && \
  curl -k -s -L ${DOWNL_URL} -o /home/tigergraph/tigergraph-ent.tar.gz && \
  tar xfz tigergraph-ent.tar.gz && \
  rm -f tigergraph-ent.tar.gz && \
# change the installation folders
sed -i 's/home\/tigergraph\/tigergraph\//opt\/tigergraph\//'  /home/tigergraph/tigergraph-${TG_VERSION}/install_conf.json && \
# Tweak original OS check & co
sed -i 's/if grep \-q \"Legacy services exist\"/if grep \-q \"Something not existing\"/'  /home/tigergraph/tigergraph-${TG_VERSION}/utils/check_utils && \
sed -i 's/local min_kB=7400000/local min_kB=4194304/'  /home/tigergraph/tigergraph-${TG_VERSION}/utils/os_utils && \
sed -i 's/local min_Num=2/local min_Num=1/'  /home/tigergraph/tigergraph-${TG_VERSION}/utils/os_utils && \
sed -i 's/minimum=20/  minimum=5/'  /home/tigergraph/tigergraph-${TG_VERSION}/utils/os_utils && \
# preparing installation folders
  mkdir -p ${INSTALL_DIR} && \
  chown -R tigergraph:tigergraph ${INSTALL_DIR} && \
# INSTALL
  cd /home/tigergraph/tigergraph-${TG_VERSION}/  && \
  ./install.sh -n && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set RESTPP.Factory.EnableAuth true" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config apply -y" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin stop all -y"  && \
# optimizations
# NGINX:
#   worker_processes 1;
#   worker_connections  10240;
sed 's/worker_processes 4/worker_processes 1/' -i ${INSTALL_DIR}/data/configs/nginx/conf/nginx.conf && \
sed 's/worker_connections  10240/worker_connections  1024/' -i ${INSTALL_DIR}/data/configs/nginx/conf/nginx.conf && \
# cleanups
# check later files within image
# find /opt/tigergraph -type f  -exec du -h {} + | sort -r -h | more
# lsof FILE -> if it's used by system or app
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/lib/tg_null_dev  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/lib  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/pics_libs  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/kafka_plugins && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/src/er && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/.syspre/usr/lib/jvm/jdk-11.0.10+9/lib/src.zip && \
  echo "export VISIBLE=now" >> /etc/profile && \
  echo "export USER=tigergraph" >> /home/tigergraph/.bash_tigergraph && \
  #rm -f /home/tigergraph/.gsql_fcgi/RESTPP.socket.1 && \
  #mkdir -p /home/tigergraph/.gsql_fcgi && \
  #touch /home/tigergraph/.gsql_fcgi/RESTPP.socket.1 && \
  #chmod 644 /home/tigergraph/.gsql_fcgi/RESTPP.socket.1 && \
  chown -R tigergraph:tigergraph /home/tigergraph && \
  chown -R tigergraph:tigergraph ${INSTALL_DIR} && \
# prepare data folder for the first usage
  mv /tmp/checkimage.sh ${INSTALL_DIR} && \
  chmod 755 ${INSTALL_DIR}/checkimage.sh && \
  tar czf ${INSTALL_DIR}/tmp/initdata.tgz -C ${INSTALL_DIR}/data . && \
  rm -Rf /tmp/*

EXPOSE 9000
EXPOSE 14240

ENTRYPOINT /usr/sbin/sshd && ${INSTALL_DIR}/checkimage.sh && su - tigergraph bash -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin start all && tail -f /dev/null"
#ENTRYPOINT /usr/sbin/sshd && su - tigergraph bash -c "/bin/bash"
