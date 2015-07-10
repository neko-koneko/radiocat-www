#!/bin/bash
# RadioCat cron job - backup/restore sql database on command
# check for file CHKFILE and if it contained keyword "backup" than do 'manual' backup
# if it contained "restore" keyword followed by group designator ["auto"|"manual"] and
# archive file name do restore
# set for 1 minute execution

for (( i = 1 ; i <= 59 ; i++ )); do
  sleep 1
  echo "ready"

    CHKFILE=/usr/local/www/radio/tmp/backup-controller
    # quit if the file isn't there or has zero length
    if [ -s "$CHKFILE" ]
    then
	echo "found signal"
	# extract the command from the file and run it
	CMD=`sed -n '1p' $CHKFILE`
	echo "$CMD"
	if [ "$CMD" = "backup" ]
	    then
		echo "found signal backup"
    		/bin/bash /usr/local/cronjobs/radio_backup_sql_export.sh manual
	    fi
	if [ "$CMD" = "restore" ]
	    then
		echo "found signal restore"
		group=`sed -n '2p' $CHKFILE`
		archive=`sed -n '3p' $CHKFILE`

		echo "archive file $archive, group $group"
    		/bin/bash /usr/local/cronjobs/radio_backup_sql_import.sh $group $archive
	    fi
	echo "femoving file $CHKFILE"
	rm -f $CHKFILE
    fi
done

