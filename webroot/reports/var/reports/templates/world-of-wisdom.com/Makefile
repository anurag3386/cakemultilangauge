#!/usr/bin/env
# Script: Makefile
# Author: Andy Gray
#
# Description
# Builds wow.dk automated reports order pages

PHP	= php5 -q
INSTALL	= ../../../../../html/03_reports_beta

none:

all:	\
	personal \
	relationship \
	season \
	lz_psychological \
	lz_career \
	lz_child \
	lz_relationship \
	rh_calendar \
	complete \
	cancel

personal:
	$(PHP) $(PHPFLAGS) personal.php

relationship:
	$(PHP) $(PHPFLAGS) relationship.php

season:
	$(PHP) $(PHPFLAGS) season.php

lz_psychological:
	$(PHP) $(PHPFLAGS) lz_psychological.php

lz_career:
	$(PHP) $(PHPFLAGS) lz_career.php

lz_child:
	$(PHP) $(PHPFLAGS) lz_child.php

lz_relationship:
	$(PHP) $(PHPFLAGS) lz_relationship.php

rh_calendar:
	$(PHP) $(PHPFLAGS) rh_calendar.php

complete:
	$(PHP) $(PHPFLAGS) complete.php

cancel:
	$(PHP) $(PHPFLAGS) cancel.php

install:
	cp build/personal.html $(INSTALL)/personal.htm
	cp build/relationship.html $(INSTALL)/relationship.htm
	cp build/season.html $(INSTALL)/season.htm
	cp build/lz_psychological.html $(INSTALL)/lz_psychological.htm
	cp build/lz_career.html $(INSTALL)/lz_career.htm
	cp build/lz_child.html $(INSTALL)/lz_child.htm
	cp build/lz_relationship.html $(INSTALL)/lz_relationship.htm
	cp build/rh_calendar.html $(INSTALL)/rh_calendar.htm
	cp build/complete.html $(INSTALL)/complete.php
	cp build/cancel.html $(INSTALL)/cancel.php
	(cd /home/andy/src/mtwow; make wow)

clean:
	@rm ./build/*.html
