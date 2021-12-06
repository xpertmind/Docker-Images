FROM ubuntu:20.10 as build

ENV NX_VER 1.21.4
ENV LINUX_DISTRO Ubuntu
ENV LINUX_VER 20.10
ENV INSTALL_DIR /opt/nginx
ENV NX_BUILD_NAME Ludbreg
ENV NX_BUILD_DATE 2021-11-15

ARG DEBIAN_FRONTEND=noninteractive
ARG WITH_DEBUG=false
ARG WITH_RTMP=false

LABEL maintainer="Bruno Šimić <bruno@xpertmind.de>" \
    architecture="amd64/x86_64" \
    nginx-version="${NX_VER}" \
    ubuntu-version="${LINUX_VER}" \
    build-date="${NX_BUILD_DATE}" \
    description="Nginx Docker image based on Ubuntu 20.10"

COPY ./resources/* /tmp/

RUN DEBIAN_FRONTEND=noninteractive \
  runtime_pkgs="tzdata net-tools wget ca-certificates make gcc g++ pkg-config libpcre++-dev libssl-dev zlib1g-dev libxslt1-dev libgd-dev libgeoip-dev uuid-dev nano" && \
  addgroup --system --gid 101 nginx && \
  adduser --system --disabled-login --ingroup nginx --no-create-home --gecos "nginx user" --shell /bin/false --uid 101 nginx &&\
  groupmod -g 82 www-data &&\
  usermod -u 82 www-data &&\
  #addgroup --system --gid 82 www-data && \
  #adduser --system --disabled-login --ingroup www-data --no-create-home --gecos "www-data user" --shell /bin/false --uid 82 www-data &&\
  DEBIAN_FRONTEND=noninteractive apt update && DEBIAN_FRONTEND=noninteractive apt install --no-install-recommends --no-install-suggests -y ${runtime_pkgs} && \
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
    --http-client-body-temp-path=/var/cache/nginx/client_temp \
    --http-proxy-temp-path=/var/cache/nginx/proxy_temp \
    --http-fastcgi-temp-path=/var/cache/nginx/fastcgi_temp \
    --http-uwsgi-temp-path=/var/cache/nginx/uwsgi_temp \
    --http-scgi-temp-path=/var/cache/nginx/scgi_temp \
    --with-http_ssl_module \
    --with-http_realip_module \
    --with-http_addition_module \
    --with-http_sub_module \
#    --with-http_dav_module \
#    --with-http_flv_module \
    --with-http_mp4_module \
    --with-http_gunzip_module \
    --with-http_gzip_static_module \
    --with-http_random_index_module \
    --with-http_secure_link_module \
    --with-http_stub_status_module \
    --with-http_auth_request_module \
    --with-http_geoip_module \
    --http-log-path=/var/log/nginx/access.log \
  #  --with-mail \
  #  --with-mail_ssl_module \
  #  --with-file-aio \
    --with-threads \
  #  --with-stream \
  #  --with-stream_ssl_module \
    --with-stream_realip_module \
    --with-http_slice_module \
    --with-http_v2_module \
    --with-pcre-jit \
    --with-cc-opt='-O2 -fstack-protector-strong -Wformat -Werror=format-security -fPIC -D_FORTIFY_SOURCE=2' \
    --with-ld-opt='-Wl,-Bsymbolic-functions -Wl,-z,relro -Wl,-z,now -fPIC' && \
  make && \
  make install && \
#  sed -i -e 's/#access_log  logs\/access.log  main;/access_log \/dev\/stdout;/' -e 's/#error_log  logs\/error.log  notice;/error_log stderr notice;/' /etc/nginx/nginx.conf && \
  apt clean && apt-get purge -y --auto-remove && \
  mkdir -p /var/www/html && \
  mv /tmp/index.html /var/www/html/index.html &&\
  mv /tmp/nginx.conf /etc/nginx/nginx.conf &&\
  mkdir -p /var/cache/nginx && \
  chown -R www-data:www-data /var/www/html &&\
  chown -R www-data:www-data /var/cache/nginx &&\
  chown -R www-data:www-data /var/log/nginx &&\
#  ln -sf /dev/stdout /var/log/nginx/access.log &&\
#  ln -sf /dev/stderr /var/log/nginx/error.log && \
  rm -rf /tmp/* && \
  rm -rf /var/lib/apt/lists/*



# Define mountable directories.
VOLUME ["/etc/nginx", "/var/log", "/var/www/html"]

# Define working directory.
WORKDIR /etc/nginx

EXPOSE 80

ENTRYPOINT ["nginx", "-g", "daemon off;"]
