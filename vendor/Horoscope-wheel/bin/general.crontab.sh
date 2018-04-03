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
CRONDIR=/home/astrowow/public_html/cron

FLAGS="-q -c /usr/local/lib/php.ini"

# DAILY CRON JOB TO SEND SUNSIGN TO MEMBERS
php ${FLAGS} ${CRONDIR}/send_daily_sunsign.php
#php ${FLAGS} ${CRONDIR}/send_weekly_sunsign.php
#php ${FLAGS} ${CRONDIR}/send_monthly_sunsign.php
php ${FLAGS} ${CRONDIR}/send_late_expiry_membership_mail.php
php ${FLAGS} ${CRONDIR}/send_early_expiry_membership_mail.php
php ${FLAGS} ${CRONDIR}/check_membership_expiration_date.php

php ${FLAGS} ${BINDIR}/birthday.gift.yearreport.preview.php

# Tactical email delivery fix
# CHANGED BY AMIT PARMAR
#( cd /var/www/vhosts/world-of-wisdom.com/astrowow/test; php ${FLAGS} nuq.php )
#
# End
