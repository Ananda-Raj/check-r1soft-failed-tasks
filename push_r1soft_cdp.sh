#/bin/bash

# Script to check and push R1Soft alert to slack 
# Author: Ananda Raj
# Date: 31 Jan 2020
# Version 1.31012020

## Collect information from R1Soft
`/usr/bin/php /root/check_r1soft_cdp.php > /root/r1soft_alerts/out`

var1=`cat /root/r1soft_alerts/out | grep "State is OK" | wc -l`;
var2=`cat /root/r1soft_alerts/out | grep "has ERROR" | wc -l`;
var3=`cat /root/r1soft_alerts/out | grep "has ALERT" | wc -l`;
var4=`cat /root/r1soft_alerts/out | grep -i "unknown" | wc -l`;
var5=`cat /root/r1soft_alerts/out | grep -v "State is OK"`

## Print results
#echo -e "\nDetailed Report: \n$var5";
#echo -e "\nSummary Report: \n"
#echo "State is Okay: $var1";
#echo "State is Error: $var2";
#echo "State is Alert: $var3";
#echo -e "State is Unknown: $var4\n";

## Output results to Slack
curl -X POST --data-urlencode "payload={\"channel\": \"#r1soft-mysql-backups\", \"text\": \"\n\nProblem Report:\n\n${var5}\n\"}" https://hooks.slack.com/services/xxxxxxxxx/xxxxxxxxx/xxxxxxxxxxxxxxxxxxxxxxxx

`sleep 5`

curl -X POST --data-urlencode "payload={\"channel\": \"#r1soft-mysql-backups\", \"text\": \"\n\n\nSummary Report:\n\nState is Okay: ${var1}\nState is Error: ${var2}\nState is Alert: ${var3}\nState is Unknown: ${var4}\"}" https://hooks.slack.com/services/xxxxxxxxx/xxxxxxxxx/xxxxxxxxxxxxxxxxxxxxxxxx

## Remove temp file
`rm -rf /root/r1soft_alerts/out`
