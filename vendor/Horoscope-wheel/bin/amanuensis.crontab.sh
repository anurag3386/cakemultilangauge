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

# UPDATING ORDER WITH PORTALID = 1
php ${FLAGS} ${BINDIR}/update-portal-id.php

# GENERATING REPORT FROM QUEUED ORDER
php ${FLAGS} ${BINDIR}/wowuk.php
php ${FLAGS} ${BINDIR}/wowuk.calender.php
php ${FLAGS} ${BINDIR}/TestYearReport.php

php ${FLAGS} ${BINDIR}/wowdk.php
php ${FLAGS} ${BINDIR}/wowdk.calender.php

php ${FLAGS} ${BINDIR}/elite-astrowow.php
php ${FLAGS} ${BINDIR}/elite.year.report.php

#11-Mar-2014 AMIT PARMAR Goes LIVE
php ${FLAGS} ${BINDIR}/bermanbraun.php
php ${FLAGS} ${BINDIR}/bb.year.report.php
#11-Mar-2014 AMIT PARMAR Goes LIVE

#26-Nov-2014 AMIT PARMAR Goes LIVE R & R Music
php ${FLAGS} ${BINDIR}/wowuk-mini-report.php
php ${FLAGS} ${BINDIR}/wowdk-mini-report.php
php ${FLAGS} ${BINDIR}/wowuk-mini-year-report.php

#19-Nov-2014 AMIT PARMAR Goes LIVE R & R Music
php ${FLAGS} ${BINDIR}/randr-mini-report.php
php ${FLAGS} ${BINDIR}/r-and-r-mini-year-report.php
#19-Nov-2014 AMIT PARMAR Goes LIVE R & R Music

#08-Sep-2015 SETUP FOR VERTICAL RESPONSE
php ${FLAGS} ${CRONDIR}/vr-import-api.php
php ${FLAGS} ${CRONDIR}/vr-import-software-api.php
php ${FLAGS} ${CRONDIR}/vr-import-unregistered-report-buyer-api.php
php ${FLAGS} ${CRONDIR}/vr-import-unregister-software-users-api.php
#08-Sep-2015 SETUP FOR VERTICAL RESPONSE

