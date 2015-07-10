#!/bin/bash
# RadioCat cron job - restart ices on command
# check for file CHKFILE and if it contained keyword "restart" than restart ices daemon
# set for 1 minute execution

#if $( ps aux | grep "bin/ices" | grep -v grep > /dev/null)
#then
# echo "Running"
#fi
#exit

if [[ "$(pidof -x $(basename $0))" != $$ ]] 
then
    echo "Already running"
    exit
fi

function getJsonVal () { 
    python -c "import json,sys;sys.stdout.write(json.dumps(json.load(sys.stdin)$1))"; 
}

haslive_old="0"
hasdefault_old="0"

for ((  ;  ;  )); do
  sleep 1

    #echo "===================================================================================="

    content=$(curl -sb -H "Accept: application/json" "http://127.0.0.1:8000/status-json.xsl")
    #echo "json=$content"
    sources=$(echo $content | getJsonVal "['icestats']['source']" 2>/dev/null )
    #echo "sources=$sources"

    i="0"
    while true
    do
        url=$(echo $sources | getJsonVal "[$i]['listenurl']" 2>/dev/null )    
	#echo "i=$i, url=$url"
        start=$(echo $sources | getJsonVal "[$i]['stream_start_iso8601']" 2>/dev/null )    
	#echo " start=$start"
	if [ -z "$url" ]
	then
            url=$(echo $sources | getJsonVal "['listenurl']" 2>/dev/null )    
	    if [ -z "$url" ]
	    then
		break
	    fi
	    if [ "$i" != "0" ]
	    then
		break
	    fi
	fi
	
	if [ -z "$start" ]
	then
            start=$(echo $sources | getJsonVal "['stream_start_iso8601']" 2>/dev/null )    
	    if [ -z "$start" ]
	    then
		break
	    fi
	fi
	i=$[$i+1]
	streams[$i]=$url
    done        

    if [ "$i" == "0" ]
    then 
        echo ">GET STREAMS: NO active streams!"
        if $(ps aux | grep "bin/ices" |grep -v grep  > /dev/null)
        then
	    echo ">ices running"
	else
	    echo ">>>Live STREAM SWITCH OFF"
	    echo "ICES RESTART REQUIRED"
	    service ices restart
		
	    haslive_old="0"
	fi
    else
	#echo "GET STREAMS: $i active streams found"

	haslive="0"

	for((j=1; j <= $i; j++)) 
	do
	    url=${streams[$j]}
	    #echo "check stream with url=$url"
    	    streamname="live"
	    if $(echo "$url" | grep -q "$streamname")
    	    then
		#echo ">Live stream found"
		haslive="1"
    		if [ "$haslive_old" = "0"  ]    
		then
		    echo ">>>LIVE STREAM SWITCH ON"
		    echo "ICES stop"
		    service ices stop
		    killall ices
		    haslive_old="1"
		fi
		
    	    else
		#echo ">Default stream"
		hasdefault="1"
	    fi
	done

	if [ "$haslive" = "0"  ]
	then
	    # echo "NO LIVE STREAMS FOUND"
	    if [ "$haslive_old" = "1" ]
	    then
		echo ">>>Live STREAM SWITCH OFF"
		echo "ICES RESTART REQUIRED"
		service ices restart
		
		haslive_old="0"
	    fi
	fi

    fi
    
done
