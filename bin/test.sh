#!/bin/bash

clear;

# PHPSpec tests
./bin/phpspec run --no-interaction;
PHPSPEC_RETURN_CODE=$?

# Behat tests
./bin/behat;
BEHAT_RETURN_CODE=$?

# Start webserver, run PHPUnit integration tests, stop webserver
php -S localhost:8089 integration-tests/webserver/index.php &> /dev/null &
WEBSERVER_PROCESS_ID=$!;
./bin/phpunit;
PHPUNIT_RETURN_CODE=$?
kill -9 $WEBSERVER_PROCESS_ID;

# Print results so you don't have to scroll
echo;
echo "PHPSpec return code: $PHPSPEC_RETURN_CODE";
echo "Behat return code:   $BEHAT_RETURN_CODE";
echo "PHPUnit return code: $PHPUNIT_RETURN_CODE";
echo;

# Work out an exit code, and exit
OVERALL_EXIT_CODE=$((PHPSPEC_RETURN_CODE + BEHAT_RETURN_CODE + PHPUNIT_RETURN_CODE))
exit $OVERALL_EXIT_CODE;