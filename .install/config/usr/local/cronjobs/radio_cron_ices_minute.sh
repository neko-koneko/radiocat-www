#!/bin/bash
# RadioCat cron job - restart ices on command
# check for file CHKFILE and if it contained keyword "restart" than restart ices daemon
# set for 1 minute execution

for (( i = 1 ; i <= 59 ; i++ )); do
  sleep 1

    CHKFILE=/usr/local/www/radio/tmp/ices-controller
    # quit if the file isn't there or has zero length
    if [ -s "$CHKFILE" ]
    then
	 echo "found signal"
	# extract the command from the file and run it
	CMD=$(cat $CHKFILE)
	if [ "$CMD" = "restart" ]
	    then
    		service ices restart
	    fi
	rm -f $CHKFILE
    fi
done

