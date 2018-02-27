#!/bin/bash
APPDIR="$(dirname ${BASH_SOURCE[0]})""/../application"
pushd $APPDIR >/dev/null

# Start PhantomJS, get PID
phantomjs --webdriver=9876 --webdriver-logfile=/tmp/ghostdriver.log &
export PJSID=$!

# kill -0 doesn't do any harm to the process but returns an error
# exit code if no such process exists >= detect if phantomjs started up
sleep .2
kill -0 $PJSID 2>/dev/null
if [ $? -ne 0 ]; then
    # we're done here
    # the invocation should have already written an appropriate error message
    popd >/dev/null
    exit 1
fi

echo Running PhantomJS on port 9876 with PID $PJSID

# Run PHPUnit
phpunit --stop-on-failure -c app/

# Kill PhantomJS
kill $PJSID
popd >/dev/null