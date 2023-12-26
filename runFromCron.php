<?php
namespace xnan\MarketPollWeb;
use xnan\Trurl;
use xnan\Trurl\Horus;
use xnan\Trurl\Horus\Market;
use xnan\Trurl\Horus\MarketSimulator;
use xnan\Trurl\Horus\Asset;
use xnan\Trurl\Horus\MarketPoll;

chdir( __DIR__ );

require '../vendor/autoload.php';
//require("autoloader.php");
include_once("settings.php");

Trurl\Functions::Load;
\Conf\MarketPollWeb\Functions::Load;

if(cronEnabled() || (array_key_exists("runOnce",$_GET) && $_GET["runOnce"]=="true")) {
	
	$folder = \Conf\MarketPollWeb\dataRootFolder();
	$lastRunFile = "$folder/content/runFromCron.lastRun.txt";

	$lastRunTime = file_exists($lastRunFile) ? file_get_contents($lastRunFile) : 0;
	
	if (!is_numeric($lastRunTime)) {
		$lastRunTime=0;
		print "runFromCron: warning: lastRunFile:'$lastRunFile' msg:lastRunTime(time) stored must be numeric, broken time ignored.\n";
	}
	$diff=time()-$lastRunTime;
	if ($diff<=dndPollSeconds()) {
		
		exit("runFromCron: rejected: msg: please do not disturb poll sources (last $diff seconds ago)\n");
	} else {
		runFromCronSetup();	
	}
	
	
	file_put_contents($lastRunFile,time());

	include_once("common.php");
} else {
	print "cron: msg: disabled";
}
