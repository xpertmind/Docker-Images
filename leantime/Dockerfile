FROM ubuntu:22.04 as build

ENV LT_VER "2.3.4" 
ENV LINUX_VER "Ubuntu 22.04 Jammy Jellyfish"
ENV LT_BUILD_NAME "LEANTIME v. ${LT_VER}"
ENV LT_BUILD_DATE "2023-01-11"
ENV LT_TGZ_URL "https://github.com/Leantime/leantime/releases/download/v${LT_VER}/Leantime-v${LT_VER}.tar.gz"

LABEL maintainer="Bruno Šimić <bruno@xpertmind.de>" \
    architecture="amd64/x86_64" \
    leantime-version="${LT_VER}" \
    ubuntu-version="${LINUX_VER}" \
    build-date="${LT_BUILD_DATE}" \
    description="Leantime Docker image based on ${LINUX_VER} without PHP FMP. You will need external web server and PHP framework"

COPY ./resources/* /tmp/

RUN apt update && apt install --no-install-recommends --no-install-suggests -y wget tar && \
  cd /tmp && \
  wget --no-check-certificate https://github.com/Leantime/leantime/releases/download/v${LT_VER}/Leantime-v${LT_VER}.tar.gz && \ 
  tar xzvf Leantime-v${LT_VER}.tar.gz && \
  mkdir -p /var/www && \
  mv leantime/public/ /var/www && \
  cd /var/www && \
  mv public html && \
  mkdir html/config && \
  cp /tmp/configuration.php html/config/

# Define mountable directories.
VOLUME ["/var/www/html"]