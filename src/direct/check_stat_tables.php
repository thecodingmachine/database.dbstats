<?php
// This file validates that all stats tables are created.
// Otherwise, it will offer links to create those tables.

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../Mouf.php';
	$selfedit = "false";
} else {
	require_once '../../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../../MoufUniversalParameters.php';
	require_once '../../../../../mouf/MoufAdmin.php';
	$selfedit = "true";
}

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once '../../../../../mouf/direct/utils/check_rights.php';

$moufManager = MoufManager::getMoufManager();
$instances = $moufManager->findInstances("DB_Stats");

$missingStatsTable = array();

foreach ($instances as $instanceName) {
	$dbStats = $moufManager->getInstance($instanceName);
	/* @var $dbStats DB_Stats */
	
	$statsTableName = $dbStats->statsTable;
	$dbConnection = $dbStats->dbConnection;
	
	$result = $dbConnection->checkTableExist($statsTableName);
	
	if ($result !== true) {
		$missingStatsTable[$instanceName] = $statsTableName;
	}	
}

$jsonObj = array();

if (empty($missingStatsTable)) {
        $jsonObj['code'] = "ok";
        $jsonObj['html'] = "All stats tables have been found.";
} else {
        $jsonObj['code'] = "warn";
        $html = "";
        foreach ($missingStatsTable as $instanceName=>$tableName) {
	        $html .= "The stats table '".htmlentities($tableName)."' used in DB_Stats instance <a href='".ROOT_URL."mouf/instance/?name=".htmlentities($instanceName)."&selfedit=".$selfedit."'>'".htmlentities($instanceName)."'</a> was not found in database. <a href='".ROOT_URL."mouf/dbStatsAdmin/?name=".htmlentities($instanceName)."&selfedit=".$selfedit."'>Click here to create this table.</a><br/>";
	        $html .= "<hr/>";
        }
        $jsonObj['html'] = $html;
}

echo json_encode($jsonObj);
exit;

?>