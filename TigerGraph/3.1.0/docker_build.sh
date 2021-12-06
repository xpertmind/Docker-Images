#/bin/bash
#docker build --compress --memory 52428800m --memory-swap 0 -t xpertmind/tigergraph:enterprise_latest .
docker build --compress -t xpertmind/tigergraph:free_latest .

# docker run -d xpertmind/tigergraph:free_3.1.0 /bin/true
# 39e4104b237291375022308e862bb53dc5210e9f9efaf3632f06f9dc8251e8f8
# docker export 39e | docker import - xpertmind/tigergraph:free_3.1.0
# docker images
# docker image history IMGID
docker push xpertmind/tigergraph:free_latest
