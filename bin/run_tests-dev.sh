#!/bin/bash
SCRIPTPATH="$(readlink ${BASH_SOURCE[0]})"
SCRIPTPATH="${SCRIPTPATH:-${BASH_SOURCE[0]}}"
SCRIPTDIR="$(dirname ${SCRIPTPATH})"
# use composer-installed phpunit
# regular phpunit won't really work
"${SCRIPTDIR}/../application/bin/phpunit" \
  -c "${SCRIPTDIR}/../application/app/" \
  "--bootstrap=${SCRIPTDIR}/../application/app/autoload.php" \
  --exclude-group=functional \
  "$@"
