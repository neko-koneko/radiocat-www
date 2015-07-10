#!/bin/bash
# RadioCat - sql database restore script
#
# input:
# $1 = "manual" for manual, set current directory to 'backup_root_dir/manual'
# $1 = "auto"  for auto, set current directory to 'backup_root_dir/auto'
# $2 - filename - without extention - in format YYYY-MM-DD_hh:mm:dd
#

# MYSQL settings
mysql_user="radio_backup"
mysql_password="password"
database="radio"
backup_root_dir="/usr/local/backup"

#
group=$1
if [[ $group =~ ^auto$|^manual$ ]]
then
  folder=$group
else
  echo "bad group ($group), exiting"
  exit;
fi

filename=$2

if [[ $filename =~ ^[0-9]{4}\-[0-9]{2}\-[0-9]{2}\_[0-9]{2}\:[0-9]{2}\:[0-9]{2}$ ]];
 then
    echo "restore database $database"
    cd $backup_root_dir
    gzip -dc $backup_root_dir/$group/$filename.sql.gz > $backup_root_dir/$group/$filename.sql
    mysql -u $mysql_user -p$mysql_password $database < $backup_root_dir/$group/$filename.sql
    rm $backup_root_dir/$group/$filename.sql
    echo "restore script finished"
else
    echo "bad filename ($filename),exiting"
fi