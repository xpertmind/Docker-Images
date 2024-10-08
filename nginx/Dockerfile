FROM ubuntu:22.04 as build
ENV NX_VER 1.27.0
ENV LINUX_VER "Ubuntu 22.04 Jammy Jellyfish"
ENV NX_BUILD_NAME "Ludbreg_${NX_VER}"
ENV PSOL "jammy"
ENV NX_BUILD_DATE 2024-07-22
ARG DEBIAN_FRONTEND=noninteractive
ARG WITH_DEBUG=false
ARG WITH_RTMP=false
LABEL maintainer="Bruno Šimić <bruno@xpertmind.de>" \
    architecture="amd64/x86_64" \
    nginx-version="${NX_VER}" \
    ubuntu-version="${LINUX_VER}" \
    build-date="${NX_BUILD_DATE}" \
    description="Nginx Docker image based on ${LINUX_VER} incl. (dynamic) Pagespeed module"

COPY ./resources/* /tmp/

RUN DEBIAN_FRONTEND=noninteractive \
  runtime_pkgs="libpcre3 tzdata ca-certificates libgeoip-dev nano" && \
  build_pkgs="build-essential uuid-dev unzip net-tools wget make gcc g++ pkg-config libpcre++-dev zlib1g-dev uuid-dev libpcre3-dev git libssl-dev zlib1g-dev libxslt1-dev libgd-dev" && \
  addgroup --system --gid 101 nginx && \
  adduser --system --disabled-login --ingroup nginx --no-create-home --gecos "nginx user" --shell /bin/false --uid 101 nginx &&\
  groupmod -g 82 www-data &&\
  usermod -u 82 www-data &&\
  DEBIAN_FRONTEND=noninteractive apt update && DEBIAN_FRONTEND=noninteractive apt install --no-install-recommends --no-install-suggests -y ${runtime_pkgs} ${build_pkgs} && \
  ## pagespeed
  mkdir -p /etc/nginx/modules/build-pagespeed && \
  cd /etc/nginx/modules/build-pagespeed && \
  wget http://www.tiredofit.nl/psol-${PSOL}.tar.xz && \
  git clone --depth=1 https://github.com/apache/incubator-pagespeed-ngx.git && \
  tar xvf psol-${PSOL}.tar.xz && \
  mv psol incubator-pagespeed-ngx && \
  cd /tmp && \
  wget https://nginx.org/download/nginx-${NX_VER}.tar.gz && \
  tar xzf nginx-${NX_VER}.tar.gz && \
  cd /tmp/nginx-${NX_VER} && \
  ./configure \
    --prefix=/etc/nginx \
    --build=${NX_BUILD_NAME} \
    --sbin-path=/usr/sbin/nginx \
    --conf-path=/etc/nginx/nginx.conf \
    --error-log-path=/var/log/nginx/error.log \
    --http-log-path=/var/log/nginx/access.log \
    --pid-path=/var/run/nginx.pid \
    --lock-path=/var/run/nginx.lock \
    --user=www-data \
    --group=www-data \
    --http-client-body-temp-path=/var/cache/nginx/client_temp \
    --http-proxy-temp-path=/var/cache/nginx/proxy_temp \
    --http-fastcgi-temp-path=/var/cache/nginx/fastcgi_temp \
    --http-uwsgi-temp-path=/var/cache/nginx/uwsgi_temp \
    --http-scgi-temp-path=/var/cache/nginx/scgi_temp \
    --with-compat \
    --with-http_ssl_module \
    --with-http_v2_module \
    --with-http_realip_module \
    --with-http_addition_module \
    --with-http_sub_module \
    --with-http_mp4_module \
    --with-http_gunzip_module \
    --with-http_gzip_static_module \
    --with-http_random_index_module \
    --with-http_secure_link_module \
    --with-http_stub_status_module \
    --with-http_auth_request_module \
    --with-http_geoip_module \
    --with-threads \
    --with-stream \
    --with-stream_ssl_module \
    --with-stream_realip_module \
    --with-http_realip_module \
    --with-http_slice_module \
    --with-http_v2_module \
    --with-pcre-jit \
    --with-cc-opt='-O2 -fstack-protector-strong -Wformat -Werror=format-security -fPIC -D_FORTIFY_SOURCE=2' \
    --with-ld-opt='-Wl,-Bsymbolic-functions -Wl,-z,relro -Wl,-z,now -fPIC' \
    --add-dynamic-module=/etc/nginx/modules/build-pagespeed/incubator-pagespeed-ngx && \
  make && \
  make install && \
#  sed -i -e 's/#access_log  logs\/access.log  main;/access_log \/dev\/stdout;/' -e 's/#error_log  logs\/error.log  notice;/error_log stderr notice;/' /etc/nginx/nginx.conf && \
  DEBIAN_FRONTEND=noninteractive apt clean && DEBIAN_FRONTEND=noninteractive apt purge ${build_pkgs} -y --auto-remove && \
  mkdir -p /var/www/html && \
  mv /tmp/index.html /var/www/html/index.html &&\
  mv /tmp/nginx.conf /etc/nginx/nginx.conf &&\
  mkdir -p /var/cache/nginx && \
  chown -R www-data:www-data /var/www/html &&\
  chown -R www-data:www-data /var/cache/nginx &&\
  chown -R www-data:www-data /var/log/nginx &&\
  rm -rf /tmp/* && rm -rfd /etc/nginx/modules/build-pagespeed && \
  rm -rf /var/lib/apt/lists/*

# Define mountable directories.
VOLUME ["/etc/nginx", "/var/log", "/var/www/html"]

# Define working directory.
WORKDIR /etc/nginx

EXPOSE 80

ENTRYPOINT ["nginx", "-g", "daemon off;"]