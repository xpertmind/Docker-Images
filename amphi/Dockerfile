FROM ubuntu:22.04 as build

LABEL maintainer="Bruno Šimić <bruno@simicmail.com>" \ 
  ubuntu-version="22.04" \
  build-date="2025-01-24" \
  description="Amphi Docker Image without login"

COPY ./resources/jupyter_*.py /tmp/

RUN \
  runtime_pkgs="nano python3-pip iputils-ping" && \
  DEBIAN_FRONTEND=noninteractive apt update && apt install --no-install-recommends --no-install-suggests -y ${runtime_pkgs}  && \
  pip install amphi-etl SQLAlchemy nodejs jsonpath-ng lxml beautifulsoup4 html5lib && \
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
