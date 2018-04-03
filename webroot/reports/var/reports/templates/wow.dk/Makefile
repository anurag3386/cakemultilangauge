#!/usr/bin/env
# Script: Makefile
# Author: Andy Gray
#
# Description
# Builds wow.dk automated reports order pages

PHP	= php5
PHPFLAGS = -q

none:

all:	personal season \
	lz_psychological lz_career lz_child lz_relationship \
	rh_calendar \
	pbs complete cancel \
	vilkar

personal:
	$(PHP) $(PHPFLAGS) personal.php

personal_se:
	$(PHP) $(PHPFLAGS) personal_se.php

#	$(PHP) $(PHPFLAGS) personal_no.php
#	$(PHP) $(PHPFLAGS) personal_es.php
#	$(PHP) $(PHPFLAGS) personal_pt.php

# not supported yet
relationship:
	$(PHP) $(PHPFLAGS) personal.php

season:
	$(PHP) $(PHPFLAGS) season.php

lz_psychological:
	$(PHP) $(PHPFLAGS) lz_psychological.php

lz_career:
	$(PHP) $(PHPFLAGS) lz_career.php

lz_child:
	$(PHP) $(PHPFLAGS) lz_child.php

# not supported yet
lz_relationship:
	$(PHP) $(PHPFLAGS) lz_relationship.php

rh_calendar:
	$(PHP) $(PHPFLAGS) rh_calendar.php

pbs:
	$(PHP) $(PHPFLAGS) pbs.php
	mv build/pbs.htm ~/src/mtwow/world-of-wisdom.com/html/06_affiliates/wowdk/pbs-form.php

complete:
	$(PHP) $(PHPFLAGS) complete.php
	mv build/complete.htm build/complete.asp

cancel:
	$(PHP) $(PHPFLAGS) cancel.php

vilkar:
	cp templates/vilkar.html build.vilkar.htm

clean:
	@rm ./build/*.html
