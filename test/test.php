<?php
require_once("../../../dbconnectionsettings/1.0/DB_ConnectionSettings.php");
require_once("../../../dbconnection/1.0/DB_MySqlConnection.php");

require_once('../DB_Stats.php');
require_once('../DB_Stats_Query.php');

$conn = new DB_MySqlConnection();
$conn->setHost("localhost");
$conn->setDbName("addb");
$conn->setUser("root");
$conn->connect();


$dbStats = new DB_Stats();
$dbStats->setDbConnection($conn);
$dbStats->setSourceTable("connectionstats");
$dbStats->setStatsTable("aggregatedconnections");


$year = new DB_StatColumn();
$year->setColumnName("year");
$year->setDataOrigin("YEAR([statcol].starttime)");
$year->setType("INT");

$month = new DB_StatColumn();
$month->setColumnName("month");
$month->setDataOrigin("MONTH([statcol].starttime)");
$month->setType("INT");

$day = new DB_StatColumn();
$day->setColumnName("day");
$day->setDataOrigin("DAY([statcol].starttime)");
$day->setType("INT");

$hour = new DB_StatColumn();
$hour->setColumnName("hour");
$hour->setDataOrigin("HOUR([statcol].starttime)");
$hour->setType("INT");

$apideoKey = new DB_StatColumn();
$apideoKey->columnName = "apideokey";
$apideoKey->dataOrigin = "[statcol].apideokey";
$apideoKey->type = "VARCHAR(18)";

$roomName = new DB_StatColumn();
$roomName->columnName = "roomname";
$roomName->dataOrigin = "[statcol].roomname";
$roomName->type = "VARCHAR(255)";

$dimensionDate = new DB_Dimension();
$dimensionDate->addColumn($year);
$dimensionDate->addColumn($month);
$dimensionDate->addColumn($day);
$dimensionDate->addColumn($hour);


$dimensionRoom = new DB_Dimension();
$dimensionRoom->columns = array();
$dimensionRoom->columns[] = $apideoKey;
$dimensionRoom->columns[] = $roomName;

$dimensions = array();
$dimensions[] = $dimensionDate;
$dimensions[] = $dimensionRoom;
$dbStats->dimensions = $dimensions;


$totalBytesWritten = new DB_StatColumn();
$totalBytesWritten->setColumnName("total_bytes_written");
$totalBytesWritten->setDataOrigin("[statcol].writtenbytes");
$totalBytesWritten->setType("BIGINT");

$totalBytesRead = new DB_StatColumn();
$totalBytesRead->columnName = "total_bytes_read";
$totalBytesRead->dataOrigin = "[statcol].readbytes";
$totalBytesRead->type = "BIGINT";

$totalTime = new DB_StatColumn();
$totalTime->columnName = "time";
$totalTime->dataOrigin = "TIMESTAMPDIFF(SECOND, [statcol].starttime, [statcol].endtime)";
$totalTime->type = "BIGINT";

$nbConnections = new DB_StatColumn();
$nbConnections->columnName = "nb_connections";
$nbConnections->dataOrigin = "IF ([statcol].connectiontype = 0, 1, 0)";
$nbConnections->type = "INT";

$nbPublishedStreams = new DB_StatColumn();
$nbPublishedStreams->columnName = "nb_published_streams";
$nbPublishedStreams->dataOrigin = "IF ([statcol].connectiontype = 1, 1, 0)";
$nbPublishedStreams->type = "INT";

$nbReadStreams = new DB_StatColumn();
$nbReadStreams->columnName = "nb_read_streams";
$nbReadStreams->dataOrigin = "IF ([statcol].connectiontype = 2, 1, 0)";
$nbReadStreams->type = "INT";

$dbStats->addValue($totalBytesWritten);
$dbStats->addValue($totalBytesRead);
$dbStats->addValue($totalTime);
$dbStats->addValue($nbConnections);
$dbStats->addValue($nbPublishedStreams);
$dbStats->addValue($nbReadStreams);

$dbStats->createStatsTable(true);
//var_dump($dbStats->computeArrayCombinations(array(array("1","2","3"), array("A","B"), array("X","Y","Z"))));

$dbStats->fillTable();

$dbStats->createTrigger();




// Query!
$query = new DB_Stats_Query();
$query->setDbStats($dbStats);

$yearFilter = new DB_ValueFilter();
$yearFilter->setColumnName("year");
$yearFilter->setValue("2009");

$monthFilter = new DB_AllValuesFilter();
$monthFilter->setColumnName("month");

$query->addFilter($yearFilter);
$query->addFilter($monthFilter);

var_dump($query->query());

?>