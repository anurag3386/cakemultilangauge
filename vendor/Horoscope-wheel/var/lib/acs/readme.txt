Description of fields in Atlas ASCII files
Note: The Windows Arial character set is used in INTTABLE.TXT.
Those developers who are not working on Windows systems can request
the ISO Standard file (INTABISO.TXT) instead, which has slightly
fewer diacritical marks, but is strictly iso-8859-1 standard.

USATABLE.TXT and INTTABLE.TXT or INTABISO.TXT

Fields are enclosed in double quotes, separated by commas.
Field 1: city name
Field 2: political sub-division (county, province, etc.) name if available
Field 3: state name for US, country name for international
Field 4: latitude in the form degreesN/Sminutes'seconds
Field 5: longitude in the form degreesE/Wminutes'seconds
Field 6: time zone or zone table number + 12000
Field 7: time type or type table number + 50

In a city name, a ">" or "(" means that the rest of the name is an alternate
spelling, and can be ignored or shown as you wish.

If Field 6 is less than 12000, it is the time zone for the city, which has
not changed.  The time zone number is the hours from Greenwich times 900.  It
can be negative: positive numbers are zones west of Greenwich; negative
numbers are east of Greenwich.  Thus, for example, the value for US Eastern
Time is 4500; the value for Japan time (9 hours east of Greenwich) is -8100.
A value greater than 12000 is a table number in the time tables file.

If Field 7 is less than 50, it is the time type for the city.  Time types
defined are:
0 Standard
1 Daylight
2 War
3 Double Summer
4 Local Mean
A value greater than 50 for Field 7 is a table number in the time tables file.

Note that many states (and countries) have more than one city with the same
name.  In that case, the political sub-division can help to differentiate
between them.



TIMETABS.TXT

This file has the time change tables for both US and international cities.
The tables appear consecutively.  Each table consists of a series of three
fields (quoted, separated by commas):
Field 1: Table number.
Field 2: Date of change: year*624000 + month*48000 + day*1500 + hour*60 + minute
Field 3: time type or zone that went into effect on the date in Field 1, or
         another table number, or a special case.
The last date in each table is the maximum positive 32-bit number.

To find the time zone and type for a city at a particular date and time,
follow this procedure:

 1. Set the zone to city Field 6.
 2. If the zone is < 12000, go to step 7.
 3. Subtract 12000 from the zone and look up that table number.
 4. Look through the dates in the table until you find a pair between which
    the date for which you are searching falls.  If your date is before the
    first date in the table, the zone is undefined (i.e., the city was not
    yet using Standard Time); set the zone to the longitude multiplied by 60
    (to make it equivalent to hours from Greenwich * 900) and go to step 7.
 5. Set the zone to the value for the earlier date bracketing your date.
 6. Go to step 2.
 7. Set the type to city Field 7.
 8. If the type is < 50, goto step 14.
 9. If the type is > 30000, goto step 18.
10. Subtract 50 from the type and look up that table number.
    If your date is before the first date in the table, set the type to 4
    (Local Mean Time), and you are done.
    If the value in the first table entry is 30004, it is Illinois.  Illinois
    had a law that births should be recorded in Standard time, even when
    Daylight time was in effect.  The law was on the books from 1916 through
    June, 1959, except during World War II.  If you are using the tables to find
    the time type in order to get the actual time that a person was born, you
    probably want to assume the law was observed (absent any knowledge to the
    contrary).  In that case, set the type to 91 (Table 41) and go to step 8.
    If you are using the tables to find what time type was actually in effect,
    then just fall through to the next step.  The Illinois tables all have the
    same date for the first (30004) and second entries, so a date will never be
    bracketed between them.
    If the value in the first table entry is 30005, it is Pennsylvania.  That
    state had a law like the Illinois law, but researchers believe that it was
    not generally observed.  You can, if you wish, put up a warning message.
    The recommended procedure is to just fall through to step 11.
11. Look through the dates in the table until you find a pair between which
    the date for which you are searching falls.  
12. Set the type to the value for the earlier date bracketing your date.
13. Go to step 8.
14. If the type is 4, subtract 450 (1/2 hour) from the zone and set the type
    to 0 (Standard).  You are done.
15. If the type is 6, subtract 300 (20 minutes) from the zone and set the type
    to 0 (Standard).  You are done.
16. If the type is 7, subtract 600 (40 minutes) from the zone and set the type
    to 0 (Standard).  You are done.
17. You are done.
18. If the type is 30002, then if the zone is 4500 (Eastern) set the type to 0
    (Standard); otherwise set the type to 1 (Daylight).  You are done.
19. If the type is 30003, then if the zone is 4500 (Eastern) set the type to 0
    (Standard) and you are done; otherwise set the type to 30001.
20. If the type is 30001, the city is on the US standard table: before 1987
    if the date is before the last Sunday in April or after the last Sunday
    in October of the year, set the type to 0 (Standard); otherwise set the
    type to 1 (Daylight).  For 1987 and after, the switch dates are instead
    the first Sunday in April and the last Sunday in October.  See the
    firstsunday routine and code fragment below.  You are done.



/* This function calculates what day the first Sunday of the month falls on
   for the given year: it only expects to be passed April or October as month.
   int values are 16 bits; long are 32 bits.
*/
int firstsunday(int month, int year)
{double d;
 long jd;
 int itemp;
 int daysofar[10] = {30,61,91,122,152,183,214,244,275,305};

 itemp = year/4 + daysofar[month-3] + 1;
 d = -693933.0 + 365.0*((double) year) + ((double) itemp); 
 if (year < 0 && year%4 != 0) --d; 
 itemp = year/400 - year/100 + 2;
 d += itemp;
 jd = d;
 itemp = jd%7L;
 if (itemp == 0) return(1);
 else return(8 - itemp);
} 



C code fragment for special case 30001:

   if (month < 4 || month > 10) return(0);         /* standard */
   else if (month > 4 && month < 10) return(1);    /* daylight */
   else
     {
      checkday = firstsunday(month, year);
      if (month == 4)
        {
         if (year < 1987)		/* last Sunday instead of first */
           {
            checkday += 28;
            if (checkday > 30) checkday -= 7;
           }
         if (day < checkday) return(0);
         else if (day > checkday) return(1);
         else if (hour * 100 + minute < 200) return(0);
         else return(1);
        }
      else
        {
         checkday += 28;
         if (checkday > 31) checkday -= 7;
         if (day < checkday) return(1);
         else if (day > checkday) return(0);
         else if (hour * 100 + minute < 200) return(1);
         else return(0);
        }
     }




Here are a couple of examples of lookups for Detroit, Michigan.
The values for Detroit are:

"Detroit","Wayne","Michigan","42N19'53","83W02'45","12802","604"

This means that the zone comes from table 802 (12802 - 12000), and the
type from table 554 (604 - 50).

Suppose you want to know the zone and type for July 12, 1952 at 9:30 am.

1952 * 624000 + 7 * 48000 + 12 * 1500 + 9 * 60 + 30 = 1218402570.

Here is table 802 from the time tables file:

"802","1176699720","5400"
"802","1195222620","4500"
"802","2147483647","0"

The date/time value of 1218402570 falls between the second and third entries,
so the zone value is 4500 (5 hours west of Greenwich).
Here is table 554 from the time tables file:

"554","1176699720","0"
"554","1211917620","82"
"554","1214157120","0"
"554","1215781620","1"
"554","1216023120","880"
"554","2147483647","0"

1218402570 falls between the 5th and 6th entries, which says to
go to table 830 (880 - 50).

Here is table 830:

"830","1176699720","0"
"830","1176699720","82"
"830","1214157120","0"
"830","1227717001","1"
"830","1227931501","0"
"830","1231387620","1"
"830","1231674120","0"
"830","1231833120","1"
"830","1232296620","0"
"830","1232632620","1"
"830","1232919120","52"
"830","2147483647","0"

1218402570 falls between the 3rd and 4th entries, which means that
the type is "0" (Standard time).

UTC time is therefore 2:30 pm (9:30 + 5:00).


Now suppose you want to look up October 3, 1976 at 12:01 pm.

1976 * 624000 + 10 * 48000 + 3 * 1500 + 12 * 60 + 1 = 1233509221.

The zone lookup in table 802 has the same result as above: 4500.
The type lookup starts the same, going to table 830, but in that table
1233509221 is between the next to last and last entries, which directs
you to table 2 (52 - 50).

Here is table 2:
"2","1227012120","1"
"2","1227309120","0"
"2","1227645120","1"
"2","1227931620","0"
"2","1228266120","1"
"2","1228552620","0"
"2","1228888620","1"
"2","1229175120","0"
"2","1229511120","1"
"2","1229797620","0"
"2","1230133620","1"
"2","1230430620","0"
"2","1230765120","1"
"2","1231051620","0"
"2","1231387620","1"
"2","1231674120","0"
"2","1231833120","1"
"2","1232296620","0"
"2","1232530620","1"
"2","1232919120","0"
"2","1233073620","30001"
"2","2147483647","0"

1233509221 is again between the next to last and last entries, which says
that the type is 30001.  That is the US standard table, changing to DST
on the last Sunday of April before 1987, and the first Sunday of April
afterwards, and changing back to Standard time on the last Sunday of October.
Since October 3rd is before the last Sunday of October, the type is "1"
for Daylight Savings Time.

UTC is therefore 4:01 pm (12:01 + 5:00 - 1:00).


Here are some places and dates you can use to check your results:

zone type     place  	       date and time  	    	 checking
4500 0	      Tioga, New York  	    April 15, 1986 12:00 pm		30001
4500 1	      Tioga, New York	    	  April 15, 1987 12:00 pm		30001
5400 1	      Evanston, Indiana		  July 1, 1960 12:00 pm				30002
4500 0	      Bloomington, Indiana	  July 1, 1960 12:00 pm					30002
4500 0	      Bloomington, Intiana 	  July 1, 1990 12:00 pm						30003
5400 1	      Evansville, Indiana 	  July 1, 1990 12:00 pm							30003
5400 1	      Chicago, Illinois		  July 1, 1950 12:00 pm								30004 Actual time in effect
5400 0	      Chicago, Illinois		  July 1, 1950 12:00 pm								      30004 Standard Time Law observed
4500 1	      Ypsilanti, Michigan	  January 15, 1974 12:00 pm							      energy crisis DST
-5400	      0		 Kandy, Sri Lanka March 1, 1942 12:00 pm 							      Half hour DST
-7500	      0		 Dalat, Malaysia  	October 1, 1940 12:00 pm						      20 minute DST
300	      0		 Freetown, Sierra Leone	July 1, 1938 12:00 pm 							      40 minute DST
7200	      2		 Acton, California	January 1, 1943 12:00 pm						      War time
0	      3		 Clare, United Kingdom	May 1, 1944 12:00 pm  							      two hour DST
xxxx	      4		 Acton, California 	October 1, 1883 12:00							      	  local mean time

