FROM ubuntu:22.04 as build

LABEL maintainer="Bruno Šimić <bruno@simicmail.com>" \ 
  ubuntu-version="22.04" \
  build-date="2025-01-24" \
  description="Amphi Docker Image without login"
ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=Europe/Zagreb

COPY ./resources/jupyter_*.py /tmp/

RUN \
  runtime_pkgs="nano python3-full pipx libpq-dev python3-dev python3-pip build-essential python3-psycopg2" && \
  DEBIAN_FRONTEND=noninteractive  apt update && apt install --no-install-recommends --no-install-suggests -y ${runtime_pkgs}  && \
  python3 -m pip install ensurepath amphi-etl SQLAlchemy nodejs jsonpath-ng lxml beautifulsoup4 html5lib argcomplete && \
  #--break-system-packages && \
  useradd -ms /bin/bash amphi && \
  mkdir -p /home/amphi/.jupyter && \
  mv ./tmp/jupyter_*.py /home/amphi/.jupyter/

USER amphi
# Define working directory.
WORKDIR /home/amphi


EXPOSE 8888
#Define mountable directories.
VOLUME ["/home/amphi", "/var/log"]
ENTRYPOINT ["amphi", "start", "-w", "/home/amphi", "-i", "0.0.0.0", "-p", "8888"]
