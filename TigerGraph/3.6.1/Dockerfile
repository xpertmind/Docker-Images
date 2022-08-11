FROM ubuntu:18.04 as build

ENV TG_VER_SHORT 3.6.1
ENV TG_VERSION ${TG_VER_SHORT}-offline
ENV DOWNL_URL https://dl.tigergraph.com/enterprise-edition/tigergraph-${TG_VERSION}.tar.gz
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
# Optimization Logs & Co
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set RESTPP.Factory.EnableAuth true" && \
# LOG optimizations
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Admin.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Admin.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Admin.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Admin.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Controller.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Controller.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Controller.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Controller.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Dict.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Dict.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Dict.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set ETCD.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set ETCD.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set ETCD.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set ETCD.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Executor.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Executor.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Executor.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Executor.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set FileLoader.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set FileLoader.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set FileLoader.BasicConfig.LogConfig.LogLevel OFF" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set FileLoader.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GPE.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GPE.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GPE.BasicConfig.LogConfig.LogLevel OFF" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GPE.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSE.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSE.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSE.BasicConfig.LogConfig.LogLevel OFF" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSE.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSQL.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSQL.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSQL.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSQL.CatalogBackupFileMaxDurationDay 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSQL.CatalogBackupFileMaxNumber 10" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GSQL.QueryResponseMaxSizeByte 67108864" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GUI.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GUI.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GUI.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set GUI.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Informant.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Informant.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Informant.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Informant.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Kafka.RetentionHours 12" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Kafka.RetentionSizeGB 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Kafka.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Kafka.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Kafka.BasicConfig.LogConfig.LogRotationFileNumber 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaConnect.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaConnect.BasicConfig.LogConfig.LogLevel ERROR" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaConnect.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaLoader.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaLoader.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaLoader.BasicConfig.LogConfig.LogLevel OFF" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaLoader.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaStreamLL.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaStreamLL.BasicConfig.LogConfig.LogLevel OFF" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set KafkaStreamLL.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Nginx.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Nginx.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Nginx.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Nginx.ClientMaxBodySize 1024" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set Nginx.WorkerProcessNumber 1" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set RESTPP.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set RESTPP.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set RESTPP.BasicConfig.LogConfig.LogLevel BRIEF" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set RESTPP.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set RESTPP.Factory.DefaultQueryTimeoutSec 90" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set RESTPP.Factory.StatsIntervalSec 120" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3.BasicConfig.LogConfig.LogLevel FATAL" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3.RetentionPeriodDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3Server.BasicConfig.LogConfig.LogFileMaxDurationDay 7" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3Server.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3Server.BasicConfig.LogConfig.LogLevel FATAL" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set TS3Server.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set ZK.BasicConfig.LogConfig.LogFileMaxSizeMB 50" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set ZK.BasicConfig.LogConfig.LogLevel OFF" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config set ZK.BasicConfig.LogConfig.LogRotationFileNumber 5" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin config apply -y" && \
  su - tigergraph -c "${INSTALL_DIR}/app/${TG_VER_SHORT}/cmd/gadmin stop all -y"  && \
# optimizations
# NGINX:
#   worker_processes 1;
# cleanups
  rm -Rf /home/tigergraph/tigergraph-${TG_VERSION} && \
  rm -f ${INSTALL_DIR}/gium_prod.tar.gz && \
  rm -f ${INSTALL_DIR}/pkg_pool/tigergraph_*.tar.gz && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/syspre_pkg && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/document/examples  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/document/DEMO  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/document/benchmark  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/document/help  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/visualization/* && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/document/examples  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/document/DEMO  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/document/benchmark  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/document/help  && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/*.txt  && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsql/lib/.tmp_tg_dbs_gsqld.jar && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/.syspre/usr/lib/jvm/jdk-11.0.10+9/lib/src.zip  && \
  #rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/src/er/platform_independent/kafka_2.12-2.5.1.tar.gz  && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/src/er/platform_independent/etcd-3.3.22.tar.gz && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/src/er/platform_independent/zookeeper-3.6.3.tar.gz && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/gui/gst.tar.gz  && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui/server/node_modules/tar/test/dir-normalization.tar && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui/server/node_modules/tar-pack/test/fixtures/packed.tar && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui/server/node_modules/tar-pack/test/fixtures/packed.tar.gz && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui/server/node_modules/sqlite3/node_modules/tar/test/dir-normalization.tar && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui/server/node_modules/sqlite3/node_modules/tar-pack/test/fixtures/packed.tar && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/gui/server/node_modules/sqlite3/node_modules/tar-pack/test/fixtures/packed.tar.gz && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/gui/server/node_modules/sqlite3/node_modules/extsprintf/Makefile.targ && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui/server/node_modules/sqlite3/deps/sqlite-autoconf-3150000.tar.gz && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui/server/node_modules/extsprintf/Makefile.targ && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui/node/lib/node_modules/npm/node_modules/request/node_modules/http-signature/node_modules/jsprim/node_modules/extsprintf/Makefile.targ && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/.syspre/usr/share/man && \
  rm -Rf /usr/share/man/* && \
  rm -Rf /usr/share/i18n/charmaps/* && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/.syspre/usr/share/doc && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/.syspre/usr/lib/jvm/java-8-openjdk-amd64-1.8.0.171/jre/man && \
  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/.syspre/usr/lib/jvm/java-8-openjdk-amd64-1.8.0.171/man && \
  rm -f /usr/share/info/*.gz && \
  rm -Rf /usr/share/doc && \
  rm -Rf /var/log/*  && \
  rm -Rf ${INSTALL_DIR}/log/*  && \
# GUI!
#  rm -Rf ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/gui && \
# OPTIMIZE files & folders
  cd ${INSTALL_DIR}/app/${TG_VER_SHORT}/bin/ && \
  rm -f libprofiler.so.0 libprofiler.so.0.4.18 libtcmalloc.so.4 libtcmalloc.so.4.5.3 && \
  rm -f libtigergraph.so tg_shadower && \
  ln -s libprofiler.so libprofiler.so.0 && \
  ln -s libprofiler.so libprofiler.so.0.4.18 && \
  ln -s libtcmalloc.so libtcmalloc.so.4 && \
  ln -s libtcmalloc.so libtcmalloc.so.4.5.3 && \
  ln -s ../dev/gdk/gsdk/lib/tg_${TG_VER_SHORT}_dev/libtigergraph.so . && \
  ln -s ../../../app/cmd/.tg_shadower tg_shadower && \
  cd ${INSTALL_DIR}/app/${TG_VER_SHORT}/.syspre/usr/lib/x86_64-linux-gnu/dri/ && \
  rm -f r200_dri.so nouveau_vieux_dri.so i965_dri.so i915_dri.so && \
  ln -s radeon_dri.so r200_dri.so && \
  ln -s radeon_dri.so nouveau_vieux_dri.so && \
  ln -s radeon_dri.so i965_dri.so && \
  ln -s radeon_dri.so i915_dri.so && \
  rm ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/lib/tg_${TG_VER_SHORT}_dev/librocksdb.a && \
  rm ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/pic_libs/librocksdb.a && \
  rm ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/lib/tg_${TG_VER_SHORT}_dev/libprotobuf.a && \
  rm ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/pic_libs/libprotobuf.a && \
  cd ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/lib/ && \
  ln -s librocksdb.a ../../../../lib/tg_${TG_VER_SHORT}_dev/librocksdb.a && \
  ln -s librocksdb.a ../pic_libs/librocksdb.a && \
  ln -s libprotobuf.a ../../../../lib/tg_${TG_VER_SHORT}_dev/libprotobuf.a && \
  ln -s libprotobuf.a ../pic_libs/libprotobuf.a && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/lib/tg_${TG_VER_SHORT}_dev/libcryptopp.a && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/pic_libs/libcryptopp.a && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/lib/libcryptopp.a && \
  cd ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/cryptopp/ && \
  ln -s libcryptopp.a ../prebuilt/lib/libcryptopp.a && \
  ln -s libcryptopp.a ../prebuilt/pic_libs/libcryptopp.a && \
  ln -s libcryptopp.a ../../../lib/tg_${TG_VER_SHORT}_dev/libcryptopp.a && \
  rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/kafka_plugins/kafka_plugins/tg-kafka-connect-filesystem-1.0.jar && \
  cd ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/kafka_plugins/kafka_plugins/ && \
  ln -s ${INSTALL_DIR}/app/${TG_VER_SHORT}/kafka/plugins/tg-kafka-connect-filesystem-1.0.jar . && \
  #rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/pic_libs/libgrpc.a && \
  #rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/lib/libgrpc.a && \
  #rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/pic_libs/librdkafka.a && \
  #rm -f ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/lib/librdkafka.a && \
  #cd ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/lib/tg_${TG_VER_SHORT}_dev/ && \
  #ln -s libgrpc.a ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/pic_libs/libgrpc.a && \
  #ln -s libgrpc.a ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/lib/libgrpc.a && \
  #ln -s librdkafka.a ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/pic_libs/librdkafka.a && \
  #ln -s librdkafka.a ${INSTALL_DIR}/app/${TG_VER_SHORT}/dev/gdk/gsdk/include/thirdparty/prebuilt/lib/librdkafka.a && \
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
