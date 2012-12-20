<?php 
/**
 * This page generates the Stats table
 * 
 */

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../Mouf.php';
} else {
	require_once '../../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../../MoufUniversalParameters.php';
	require_once '../../../../../mouf/MoufAdmin.php';
}

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once '../../../../../mouf/direct/utils/check_rights.php';

$dropIfExist = $_REQUEST['dropIfExist'];

$dbStatsInstanceName = $_REQUEST["name"];
$dbStats = MoufManager::getMoufManager()->getInstance($dbStatsInstanceName);
/* @var $dbStats DB_Stats */

$dbStats->createStatsTable($dropIfExist == "true");
$dbStats->createTrigger();
?>