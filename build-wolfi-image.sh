#!/usr/bin/env bash
#
# build-wolfi-image.sh — Build mapbender as a distroless-style Wolfi OCI image
#
# Uses melange and apko via their official Docker images.
#
# Usage:
#   ./build-wolfi-image.sh [target]
#
# Targets:
#   mapbender            Build the base mapbender image (default)
#   mapbender-puppeteer  Build the mapbender image with Puppeteer (Node.js + Chromium)
#   all                  Build both images
#
set -euo pipefail

TARGET="${1:-all}"
WORKDIR="$(cd "$(dirname "$0")" && pwd)"
PACKAGES_DIR="$WORKDIR/packages"

build_mapbender_apk() {
  echo "==> Cleaning previous build artifacts..."
  rm -rf "$PACKAGES_DIR"
  mkdir -p "$PACKAGES_DIR"

  echo "==> Generating Melange signing key..."
  docker run --rm \
    -v "$WORKDIR:/work" \
    -w /work \
    cgr.dev/chainguard/melange keygen

  echo "==> Building mapbender APK with Melange..."
  docker run --rm --privileged \
    -v "$WORKDIR:/work" \
    -w /work \
    cgr.dev/chainguard/melange build melange.yaml \
      --arch x86_64 \
      --signing-key melange.rsa \
      --out-dir /work/packages \
      --source-dir /work
}

build_puppeteer_apk() {
  echo "==> Building mapbender-puppeteer APK with Melange..."
  docker run --rm --privileged \
    -v "$WORKDIR:/work" \
    -w /work \
    cgr.dev/chainguard/melange build melange-puppeteer.yaml \
      --arch x86_64 \
      --signing-key melange.rsa \
      --out-dir /work/packages \
      --source-dir /work
}

assemble_mapbender() {
  echo "==> Assembling mapbender OCI image with Apko..."
  docker run --rm \
    -v "$WORKDIR:/work" \
    -w /work \
    cgr.dev/chainguard/apko build apko.yaml \
      "mapbender-wolfi:latest" \
      /work/mapbender-wolfi.tar \
      --keyring-append /work/melange.rsa.pub \
      --repository-append /work/packages \
      --arch x86_64

  echo "==> Loading mapbender image into Docker..."
  docker load < "$WORKDIR/mapbender-wolfi.tar"

  echo ""
  echo "    Image: mapbender-wolfi:latest"
  echo "    Test:  docker run --rm --entrypoint /usr/bin/php mapbender-wolfi:latest-amd64 ./application/bin/console mapbender:config:check"
  echo "    Run:   docker run -p 8080:8080 mapbender-wolfi:latest-amd64"
  echo ""
}

assemble_puppeteer() {
  echo "==> Assembling mapbender-puppeteer OCI image with Apko..."
  docker run --rm \
    -v "$WORKDIR:/work" \
    -w /work \
    cgr.dev/chainguard/apko build apko-puppeteer.yaml \
      "mapbender-puppeteer-wolfi:latest" \
      /work/mapbender-puppeteer-wolfi.tar \
      --keyring-append /work/melange.rsa.pub \
      --repository-append /work/packages \
      --arch x86_64

  echo "==> Loading mapbender-puppeteer image into Docker..."
  docker load < "$WORKDIR/mapbender-puppeteer-wolfi.tar"

  echo ""
  echo "    Image: mapbender-puppeteer-wolfi:latest"
  echo "    Test:  docker run --rm --entrypoint /usr/bin/php mapbender-puppeteer-wolfi:latest-amd64 ./application/bin/console mapbender:config:check"
  echo "    Run:   docker run -p 8080:8080 -e MB_PUPPETEER_NO_SANDBOX=true mapbender-puppeteer-wolfi:latest-amd64"
  echo ""
}

case "$TARGET" in
  mapbender)
    build_mapbender_apk
    assemble_mapbender
    ;;
  mapbender-puppeteer)
    build_mapbender_apk
    build_puppeteer_apk
    assemble_puppeteer
    ;;
  all)
    build_mapbender_apk
    build_puppeteer_apk
    assemble_mapbender
    assemble_puppeteer
    ;;
  *)
    echo "Unknown target: $TARGET"
    echo "Usage: $0 [mapbender|mapbender-puppeteer|all]"
    exit 1
    ;;
esac

echo "==> Build complete!"
