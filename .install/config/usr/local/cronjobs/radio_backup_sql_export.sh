#!/bin/bash
# RadioCat - sql database backup script
#
# input:
# $1 = "manual" for manual, saves to 'backup_root_dir/manual'
# $1 = not "manual"  for auto, saves to 'backup_root_dir/auto'
#
# output:
# file with name in format YYYY-MM-DD_hh:mm:dd.sql.gz

# MYSQL settings
mysql_user="radio_backup"
mysql_password="password"
database="radio"
backup_root_dir="/usr/local/backup"


#
echo "backup database $database started"
cd $backup_root_dir

if [ "$1" = "manual" ]
then
    output_dir="$backup_root_dir/manual"
else
    output_dir="$backup_root_dir/auto"
fi
    echo "output $output_dir"
    if [ ! -d "$output_dir" ]; then
	mkdir $output_dir
    fi
    mysqldump -u $mysql_user -p$mysql_password $database | gzip > "$output_dir/$(date +%Y-%m-%d_%H:%M:%S).sql.gz";


echo "backup script finished"