#!/bin/bash
APPDIR="$(dirname ${BASH_SOURCE[0]})""/../application"
pushd $APPDIR >/dev/null
# use composer-installed phpunit
# regular phpunit won't really work
bin/phpunit -c app/ --bootstrap=app/autoload.php --exclude-group=functional "$@"
popd >/dev/null
