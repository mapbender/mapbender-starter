#!/usr/bin/env bash
#
# build-wolfi-image.sh — Build mapbender as a distroless-style Wolfi OCI image
#
# Uses melange and apko via their official Docker images.
#
# Usage:
#   ./build-wolfi-image.sh [image-name:tag]
#
set -euo pipefail

IMAGE_NAME="${1:-mapbender-wolfi:latest}"
WORKDIR="$(cd "$(dirname "$0")" && pwd)"
PACKAGES_DIR="$WORKDIR/packages"

echo "==> Cleaning previous build artifacts..."
rm -rf "$PACKAGES_DIR"
mkdir -p "$PACKAGES_DIR"

# ─── Step 1: Generate a temporary signing key for Melange ───
echo "==> Generating Melange signing key..."
docker run --rm \
  -v "$WORKDIR:/work" \
  -w /work \
  cgr.dev/chainguard/melange keygen

# ─── Step 2: Build the mapbender APK with Melange ───
echo "==> Building mapbender APK with Melange..."
docker run --rm --privileged \
  -v "$WORKDIR:/work" \
  -w /work \
  cgr.dev/chainguard/melange build melange.yaml \
    --arch x86_64 \
    --signing-key melange.rsa \
    --out-dir /work/packages \
    --source-dir /work

# ─── Step 3: Assemble the OCI image with Apko ───
echo "==> Assembling OCI image with Apko..."
docker run --rm \
  -v "$WORKDIR:/work" \
  -w /work \
  cgr.dev/chainguard/apko build apko.yaml \
    "$IMAGE_NAME" \
    /work/mapbender-wolfi.tar \
    --keyring-append /work/melange.rsa.pub \
    --repository-append /work/packages \
    --arch x86_64

# ─── Step 4: Load into Docker ───
echo "==> Loading image into Docker..."
docker load < "$WORKDIR/mapbender-wolfi.tar"

echo ""
echo "==> Build complete!"
echo "    Image: $IMAGE_NAME"
echo ""
echo "    Test with:"
echo "      docker run --rm $IMAGE_NAME /usr/bin/php ./application/bin/console mapbender:config:check"
echo ""
echo "    Run with:"
echo "      docker run -p 8080:8080 $IMAGE_NAME"
