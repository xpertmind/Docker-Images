#/bin/bash
podman build --compress -t xpertmind/nginx:latest .
podman push xpertmind/nginx:latest
