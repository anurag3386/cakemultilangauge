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


# SETUP UP PREVIEW REPORT QUEUE FOR NEWLY SIGNUP USERS
php ${FLAGS} ${CRONDIR}/set_preview_report.php

# GENERATING REPORT FROM QUEUED ORDER
php ${FLAGS} ${BINDIR}/wowuk.php
php ${FLAGS} ${BINDIR}/wowuk.calender.php
php ${FLAGS} ${BINDIR}/TestYearReport.php
php ${FLAGS} ${BINDIR}/yearreport.preview.php

# GOLDEN CIRCLE QUESTION - ASSIGN QUESTIONS TO ASTROLOGERS
php ${FLAGS} ${BINDIR}/assign-question-to-astrologer.php
php ${FLAGS} ${CRONDIR}/check_time_of_assigned_questions.php
php ${FLAGS} ${BINYEARDIR}/generate-transit.php

php ${FLAGS} ${BINDIR}/elite-astrowow.php
php ${FLAGS} ${BINDIR}/elite.year.report.php

#11-Mar-2014 AMIT PARMAR Goes LIVE
php ${FLAGS} ${BINDIR}/bermanbraun.php
php ${FLAGS} ${BINDIR}/bb.year.report.php
#11-Mar-2014 AMIT PARMAR Goes LIVE

# CREATING ORDER FOR FREE SOFTWARE DOWNLOADs
php ${FLAGS} ${CRONDIR}/free-software-download-orders.php
php ${FLAGS} ${CRONDIR}/invite-friends.php

# Tactical email delivery fix
# CHANGED BY AMIT PARMAR
#( cd home/astrowow/public_html/test; php ${FLAGS} nuq.php )
#
# End
