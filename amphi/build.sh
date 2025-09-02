#!/bin/bash
#docker buildx create --platform linux/arm64,linux/amd64 --name qemu --> needed before strting build for mutliplatform on rancher
#docker manifest create $1
docker buildx build --progress=plain  --push --platform linux/arm64,linux/amd64 -t $1 .
#docker buildx build --progress=plain  --push --platform linux/arm64 -t $1 .
#docker build --platform linux/amd64,linux/arm64  --manifest $1  .
#podman manifest push $1