#!/bin/bash
#
# Script: amanuensis.crontab.sh
# Author: Parmar Amit <parmaramit1111@gmail.com>
#
# Description
# This script manages the order fulfilment process on a 5 minute boundary

#FOLLOWING ARE THE NEW SETTINGS
#BINDIR=/var/www/vhosts/world-of-wisdom.com/astrowow.com/bin/amanuensis

BINDIR=/home/astrowow/public_html/bin/amanuensis
BINYEARDIR=/home/astrowow/public_html/bin/year-report-transit
CRONDIR=/home/astrowow/public_html/cron

#FLAGS="-q -c /var/www/vhosts/world-of-wisdom.com/etc/php.ini"
FLAGS="-q -c /usr/local/lib/php.ini"

# GOLDEN CIRCLE QUESTION - ASSIGN QUESTIONS TO ASTROLOGERS
php ${FLAGS} ${BINYEARDIR}/generate-transit.php
