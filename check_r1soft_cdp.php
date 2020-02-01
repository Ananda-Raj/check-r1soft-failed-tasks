<?php


# Script to check failed R1Soft tasks 
# Author: Ananda Raj
# Date: 31 Jan 2020
# Version 1.31012020


####CDP Server Configuration Start
$HOST="server.hostname.com";
#set CDP server to access API
$PORT="9080";
#set CDP user
$USER="admin";
#set CDP user password
$PASS="Password1!";
####CDP Server Configuration End


# For the scripts to run sucessfully all the product features must be turned on
# These features can be turned off anytime using the configuration screen [ Configuration=> Product Features] 
####Enable ALL Product Features Start
try 	{
	$configurationClient = new soapclient("http://$HOST:$PORT/Configuration?wsdl",
					     array('login'=>"$USER",
					          'password'=>"$PASS",
						  'trace'=>1,
						  'cache_wsdl' => WSDL_CACHE_NONE,
						  'features' => SOAP_SINGLE_ELEMENT_ARRAYS
						  )
					     );
	$configurationResponse=$configurationClient->enableALLProductFeatures() ;
//	echo "Successfully enable all the product features \n" ;
  	}
catch (SoapFault $exception)
	{
	echo "Warning: Failed to enable all the product features \n" ;
	echo "Warning: This script may not work as intended \n" ;
	echo $exception;
	}
####Enable ALL Product Features End


####Get policies Start
try	{
	$policyClient = new soapclient("http://$HOST:$PORT/Policy2?wsdl",
				      array('login'=>"$USER",
					   'password'=>"$PASS",
					   'trace'=>1,
				 	   'cache_wsdl' => WSDL_CACHE_NONE,
					   'features' => SOAP_SINGLE_ELEMENT_ARRAYS
					   )
				      );
	$allPoliciesForUser = $policyClient->getPolicies();
####Get Policies End


####Print Policy Details Start
$returnstring = '';
foreach($allPoliciesForUser->return as $tmp)
	{
//	echo "$tmp->id \n";
//	echo "$tmp->name \n";
//	echo "$tmp->description \n";
	if ($tmp->state == "UNKNOWN"){
		$returnstring .= "Policy ".$tmp->name ." is in a UNKNOWN state \n";
		$unknown = 1;
	 	}
	else if ($tmp->state == "ERROR"){
		$returnstring .=  "Policy ".$tmp->name ." has ERROR \n";
		$error = 1;
		}
	else if ($tmp->state == "ALERT"){
		$returnstring .=  "Policy ".$tmp->name ." has ALERT(s) \n";
		$warning = 1;
		}
	else {
		$returnstring .=  "Policy ".$tmp->name ." State is OK \n";
		$ok = 1;
		}
//		echo "\n";
	}
####Print Policy Details End
print $returnstring;

/*
#echo $returnstring
#payload={\"channel\": \"#r1soft-mysql-backups\", \"text\": \"Testing.\" "{$returnstring}" }


	if ($error==1) {	
		exit(2);
	} elseif ($warning==1) {
		exit(1);
	} elseif ($unknown==1) {
		exit(3);
	} elseif ($ok==1) {
		exit(0);
	}
*/

} 
catch (SoapFault $exception)
	{
	echo "Failed to find all status of all policies";
	echo $exception;
	exit(1);
	}
?>

