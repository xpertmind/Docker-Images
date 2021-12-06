#/bin/bash
docker build --compress -t xpertmind/nginx:latest .
docker push xpertmind/nginx:latest
