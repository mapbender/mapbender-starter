#!/usr/bin/env bash
#
# build-wolfi-image.sh — Build mapbender as a distroless-style Wolfi OCI image
#
# Uses melange and apko via their official Docker images.
#
# Usage:
#   docker/apko/build-wolfi-image.sh [target]
#
# Targets:
#   mapbender-slim       Build the slim mapbender image (SQLite only, no PostgreSQL/LDAP)
#   mapbender            Build the full mapbender image (PostgreSQL + LDAP + bz2)
#   mapbender-puppeteer  Build the mapbender image with Puppeteer (Node.js + Chromium)
#   all                  Build all images
#
set -euo pipefail

TARGET="${1:-all}"
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"
PACKAGES_DIR="$SCRIPT_DIR/packages"
APKO_DIR="docker/apko"

build_mapbender_apk() {
  echo "==> Cleaning previous build artifacts..."
  rm -rf "$PACKAGES_DIR"
  mkdir -p "$PACKAGES_DIR"

  echo "==> Generating Melange signing key..."
  docker run --rm \
    -v "$SCRIPT_DIR:/work/$APKO_DIR" \
    -w /work/$APKO_DIR \
    cgr.dev/chainguard/melange keygen

  echo "==> Building mapbender APK with Melange..."
  docker run --rm --privileged \
    -v "$PROJECT_ROOT:/work" \
    -w /work \
    cgr.dev/chainguard/melange build $APKO_DIR/melange.yaml \
      --arch x86_64 \
      --signing-key $APKO_DIR/melange.rsa \
      --out-dir /work/$APKO_DIR/packages \
      --source-dir /work
}

build_puppeteer_apk() {
  echo "==> Building mapbender-puppeteer APK with Melange..."
  docker run --rm --privileged \
    -v "$PROJECT_ROOT:/work" \
    -w /work \
    cgr.dev/chainguard/melange build $APKO_DIR/melange-puppeteer.yaml \
      --arch x86_64 \
      --signing-key $APKO_DIR/melange.rsa \
      --out-dir /work/$APKO_DIR/packages \
      --source-dir /work
}

assemble_slim() {
  echo "==> Assembling mapbender-slim OCI image with Apko..."
  docker run --rm \
    -v "$PROJECT_ROOT:/work" \
    -w /work \
    cgr.dev/chainguard/apko build $APKO_DIR/apko-slim.yaml \
      "mapbender-slim-wolfi:latest" \
      /work/$APKO_DIR/mapbender-slim-wolfi.tar \
      --keyring-append /work/$APKO_DIR/melange.rsa.pub \
      --repository-append /work/$APKO_DIR/packages \
      --arch x86_64

  echo "==> Loading mapbender-slim image into Docker..."
  docker load < "$SCRIPT_DIR/mapbender-slim-wolfi.tar"

  echo ""
  echo "    Image: mapbender-slim-wolfi:latest"
  echo "    Test:  docker run --rm --entrypoint /usr/bin/php mapbender-slim-wolfi:latest-amd64 ./application/bin/console mapbender:config:check"
  echo "    Run:   docker run -p 8080:8080 mapbender-slim-wolfi:latest-amd64"
  echo ""
}

assemble_mapbender() {
  echo "==> Assembling mapbender OCI image with Apko..."
  docker run --rm \
    -v "$PROJECT_ROOT:/work" \
    -w /work \
    cgr.dev/chainguard/apko build $APKO_DIR/apko.yaml \
      "mapbender-wolfi:latest" \
      /work/$APKO_DIR/mapbender-wolfi.tar \
      --keyring-append /work/$APKO_DIR/melange.rsa.pub \
      --repository-append /work/$APKO_DIR/packages \
      --arch x86_64

  echo "==> Loading mapbender image into Docker..."
  docker load < "$SCRIPT_DIR/mapbender-wolfi.tar"

  echo ""
  echo "    Image: mapbender-wolfi:latest"
  echo "    Test:  docker run --rm --entrypoint /usr/bin/php mapbender-wolfi:latest-amd64 ./application/bin/console mapbender:config:check"
  echo "    Run:   docker run -p 8080:8080 mapbender-wolfi:latest-amd64"
  echo ""
}

assemble_puppeteer() {
  echo "==> Assembling mapbender-puppeteer OCI image with Apko..."
  docker run --rm \
    -v "$PROJECT_ROOT:/work" \
    -w /work \
    cgr.dev/chainguard/apko build $APKO_DIR/apko-puppeteer.yaml \
      "mapbender-puppeteer-wolfi:latest" \
      /work/$APKO_DIR/mapbender-puppeteer-wolfi.tar \
      --keyring-append /work/$APKO_DIR/melange.rsa.pub \
      --repository-append /work/$APKO_DIR/packages \
      --arch x86_64

  echo "==> Loading mapbender-puppeteer image into Docker..."
  docker load < "$SCRIPT_DIR/mapbender-puppeteer-wolfi.tar"

  echo ""
  echo "    Image: mapbender-puppeteer-wolfi:latest"
  echo "    Test:  docker run --rm --entrypoint /usr/bin/php mapbender-puppeteer-wolfi:latest-amd64 ./application/bin/console mapbender:config:check"
  echo "    Run:   docker run -p 8080:8080 -e MB_PUPPETEER_NO_SANDBOX=true mapbender-puppeteer-wolfi:latest-amd64"
  echo ""
}

case "$TARGET" in
  mapbender-slim)
    build_mapbender_apk
    assemble_slim
    ;;
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
    assemble_slim
    assemble_mapbender
    assemble_puppeteer
    ;;
  *)
    echo "Unknown target: $TARGET"
    echo "Usage: $0 [mapbender-slim|mapbender|mapbender-puppeteer|all]"
    exit 1
    ;;
esac

echo "==> Build complete!"
