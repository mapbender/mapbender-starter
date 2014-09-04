#!/bin/sh

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
OWD="$(pwd)"

cd "$DIR/../application"

# Start PhantomJS, get PID
phantomjs --webdriver=9876 --webdriver-logfile=/tmp/ghostdriver.log &
export PJSID=$!
echo Running PhantomJS on port 9876 with PID $PJSID

# Run PHPUnit
phpunit --stop-on-failure -c app/

# Kill PhantomJS
kill $PJSID

cd $OWD
