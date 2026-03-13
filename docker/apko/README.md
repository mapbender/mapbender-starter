# Wolfi Distroless Container Images for Mapbender

This directory contains the build configuration for Mapbender's distroless-style container images built on [Wolfi OS](https://wolfi.dev) using [Melange](https://github.com/chainguard-dev/melange) (APK builder) and [Apko](https://github.com/chainguard-dev/apko) (OCI assembler).

## Why Wolfi / Distroless?

- **Zero known CVEs** — Wolfi packages are continuously patched; images scan clean with Trivy/Grype
- **Minimal attack surface** — No package manager, shell login, or unnecessary binaries at runtime
- **Reproducible builds** — Declarative YAML configs produce bit-for-bit identical images
- **SBOM built-in** — Every image ships with an SPDX Software Bill of Materials

## Image Variants

| Variant | Description | Approx. Size |
|---------|-------------|--------------|
| **mapbender-slim** | SQLite only — no PostgreSQL, LDAP, or bz2 | ~411 MB |
| **mapbender** | Full image — adds PostgreSQL (`pdo_pgsql`), LDAP, bz2 | ~412 MB |
| **mapbender-puppeteer** | Full image + Node.js + Chrome Headless Shell for vector tile printing | ~1.09 GB |

## Architecture

The build is split into two phases:

### Phase 1: Melange — Build APK packages

- **`melange.yaml`** — Builds the `mapbender` APK: copies the application source, runs `composer install`, patches VectorTilesBundle, installs Apache/PHP configs, and creates the entrypoint script.
- **`melange-puppeteer.yaml`** — Builds the `mapbender-puppeteer` APK: installs Puppeteer (Node.js library) and downloads `chrome-headless-shell` (a lightweight headless Chrome binary, ~256 MB, much smaller than full Chromium).

### Phase 2: Apko — Assemble OCI images

- **`apko-slim.yaml`** — Assembles the slim image from Wolfi packages + the `mapbender` APK
- **`apko.yaml`** — Assembles the full image (adds `pdo_pgsql`, `ldap`, `bz2` on top of slim)
- **`apko-puppeteer.yaml`** — Assembles the puppeteer image (full + `mapbender-puppeteer` APK + system libs for Chrome)

## Quick Start

### Build all images

```bash
docker/apko/build-wolfi-image.sh all
```

### Build a single variant

```bash
docker/apko/build-wolfi-image.sh mapbender           # Full image
docker/apko/build-wolfi-image.sh mapbender-slim       # Slim image
docker/apko/build-wolfi-image.sh mapbender-puppeteer  # Puppeteer image
```

### Run an image

```bash
# Full or slim
docker run -p 8080:8080 mapbender-wolfi:latest-amd64

# Puppeteer (--no-sandbox required in containers)
docker run -p 8080:8080 mapbender-puppeteer-wolfi:latest-amd64
```

Then open http://localhost:8080 in your browser.

### Run the config check

```bash
docker run --rm --entrypoint /usr/bin/php \
  mapbender-puppeteer-wolfi:latest-amd64 \
  ./application/bin/console mapbender:config:check
```

## Debugging & Useful Commands

### Start a container for interactive testing

```bash
docker run --rm -d --name mb-test -p 8080:8080 mapbender-puppeteer-wolfi:latest-amd64
```

### Shell into the running container

```bash
docker exec -it mb-test sh
```

### Check PHP configuration

```bash
docker exec mb-test php -i | grep -E "variables_order|error_log|memory_limit"
```

### Check Apache environment passthrough

```bash
# Create a quick env test endpoint
docker exec -u root mb-test sh -c \
  "echo '<?php header(\"Content-Type: text/plain\"); echo \"MB_PUPPETEER_NO_SANDBOX: \" . getenv(\"MB_PUPPETEER_NO_SANDBOX\") . \"\\n\";' \
  > /var/mapbender/application/public/test-env.php"

curl http://localhost:8080/test-env.php
# Expected: MB_PUPPETEER_NO_SANDBOX: true
```

### Test vector tile print rendering (as www-data, simulating Apache)

This simulates the exact code path used by `VectorTilesRenderer.php` — runs Chrome headless shell, renders a vector tile map, and outputs a base64-encoded PNG:

```bash
docker exec -u www-data mb-test sh -c '
cd /var/mapbender/application && php -r "
use Symfony\Component\Process\Process;
require \"vendor/autoload.php\";

\$npmProcess = new Process([\"npm\", \"root\", \"-g\"]);
\$npmProcess->run();
\$nodeRoot = trim(\$npmProcess->getOutput());

\$config = json_encode([
    \"bbox\" => [1000000, 6000000, 1100000, 6100000],
    \"width\" => 256, \"height\" => 256, \"dpi\" => 96,
    \"scaleCorrection\" => 1,
    \"styleUrl\" => \"https://sgx.geodatenzentrum.de/gdz_basemapde_vektor/styles/bm_web_col.json\"
]);

\$process = new Process(
    [\"node\", \"mapbender/src/Mapbender/VectorTilesBundle/Resources/js/print-vectortile.js\"],
    \"/var/mapbender/application\",
    [
        \"NODE_PATH\" => \$nodeRoot,
        \"MB_VT_PRINT_CONFIG\" => \$config,
        \"MB_PUPPETEER_NO_SANDBOX\" => \$_ENV[\"MB_PUPPETEER_NO_SANDBOX\"] ?? \"false\",
    ]
);
\$process->setTimeout(60);
\$process->run();

if (\$process->isSuccessful()) {
    echo \"SUCCESS: \" . strlen(trim(\$process->getOutput())) . \" bytes of base64 PNG\n\";
} else {
    echo \"FAILED (exit \" . \$process->getExitCode() . \"): \" . \$process->getErrorOutput() . \"\n\";
}
"'
```

### Test print rendering with env -i (clean environment, no inherited vars)

```bash
docker exec mb-test sh -c '
cd /var/mapbender/application && env -i \
  HOME=/tmp \
  MB_PUPPETEER_NO_SANDBOX=true \
  PUPPETEER_EXECUTABLE_PATH=/usr/local/bin/chrome-headless-shell \
  node -e "
    const puppeteer = require(\"puppeteer\");
    (async () => {
      const browser = await puppeteer.launch({headless: true, args: [\"--no-sandbox\"]});
      const page = await browser.newPage();
      await page.setContent(\"<h1>Print test</h1>\");
      const screenshot = await page.screenshot({encoding: \"base64\"});
      await browser.close();
      console.log(screenshot.substring(0, 40) + \"...\");
      console.log(\"OK: \" + screenshot.length + \" bytes\");
    })();
  "
'
```

### Check installed browsers in the puppeteer cache

```bash
docker exec mb-test ls -la /opt/puppeteer-cache/
docker exec mb-test /usr/local/bin/chrome-headless-shell --version
```

### View container logs (Apache access + error logs)

```bash
docker logs mb-test           # stdout/stderr
docker logs -f mb-test        # follow
```

### Check image size

```bash
docker images mapbender-puppeteer-wolfi --format '{{.Repository}}:{{.Tag}}\t{{.Size}}'
```

### Scan for CVEs

```bash
# With Trivy
trivy image mapbender-puppeteer-wolfi:latest-amd64

# With Grype
grype mapbender-puppeteer-wolfi:latest-amd64
```

## File Reference

| File | Purpose |
|------|---------|
| `build-wolfi-image.sh` | Build orchestration script |
| `melange.yaml` | Melange recipe: builds the main `mapbender` APK |
| `melange-puppeteer.yaml` | Melange recipe: builds the `mapbender-puppeteer` APK (Node.js + Chrome Headless Shell) |
| `apko-slim.yaml` | Apko config: slim image (SQLite only) |
| `apko.yaml` | Apko config: full image (+ PostgreSQL, LDAP, bz2) |
| `apko-puppeteer.yaml` | Apko config: puppeteer image (+ Node.js, Chrome Headless Shell) |

### Shared configuration (in `docker/`)

| File | Purpose |
|------|---------|
| `../php.ini` | PHP configuration overrides (installed as `/etc/php/conf.d/99-mapbender.ini`) |
| `../mapbender_apache.conf` | Apache vhost configuration with `PassEnv` directives |

## Key Design Decisions

### chrome-headless-shell vs full Chromium

The puppeteer image uses Google's `chrome-headless-shell` (~256 MB) instead of full Chromium (~1.1 GB with transitive dependencies). This purpose-built headless binary has no GUI dependencies (GTK, Qt, etc.) and is specifically designed for headless rendering tasks like PDF generation and screenshots.

### PassEnv + variables_order

Apache/mod_php does not automatically expose container environment variables to PHP. Two settings make this work:

1. **`variables_order = "EGPCS"`** in `php.ini` — Includes "E" so PHP populates `$_ENV` from the OS environment
2. **`PassEnv`** directives in `mapbender_apache.conf` — Tells Apache to pass specific env vars (`MB_PUPPETEER_NO_SANDBOX`, `NODE_PATH`, `PUPPETEER_CACHE_DIR`, `PUPPETEER_EXECUTABLE_PATH`) to the PHP process

### .puppeteerrc.cjs

A Puppeteer configuration file is placed at `/var/mapbender/application/.puppeteerrc.cjs` to set `cacheDirectory: '/opt/puppeteer-cache'`. This ensures Puppeteer finds the Chrome binary regardless of which environment variables are passed through by Symfony's `Process` class.

### VectorTilesBundle patching

The melange build downloads patched versions of `VectorTilesRenderer.php` and `print-vectortile.js` from a specific commit (`b762f26`) that includes vector tile print support.

## Build Artifacts (gitignored)

The build process generates the following artifacts in this directory:

- `melange.rsa` / `melange.rsa.pub` — Ephemeral signing keypair for APK packages
- `packages/` — Built APK packages
- `mapbender-*-wolfi.tar` — OCI image tarballs
- `sbom-*.spdx.json` — SPDX Software Bill of Materials
