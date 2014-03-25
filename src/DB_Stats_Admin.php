<?php
use Mouf\MoufUtils;
use Mouf\MoufManager;

MoufUtils::registerMainMenu('dbMainMenu', 'DB', null, 'mainMenu', 70);
MoufUtils::registerMenuItem('dbStatsAdminSubMenu', 'DB stats', null, 'dbMainMenu', 70);
MoufUtils::registerMenuItem('dbStatsGenerateStatAdminSubMenu', 'Generate stat table', 'javascript:chooseInstancePopup("Mouf\\Database\\Dbstats\\DB_Stats", "'.ROOT_URL.'dbStatsAdmin/?name=", "'.ROOT_URL.'")', 'dbStatsAdminSubMenu', 10);
MoufUtils::registerMenuItem('dbStatsRecomputeStatAdminSubMenu', 'Recompute stat table', 'javascript:chooseInstancePopup("Mouf\\Database\\Dbstats\\DB_Stats", "'.ROOT_URL.'dbStatsAdmin/recomputeForm?name=", "'.ROOT_URL.'")', 'dbStatsAdminSubMenu', 20);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('dbStatsAdmin', 'Mouf\\Database\\Dbstats\\controllers\\DbStatsController', true);
MoufManager::getMoufManager()->bindComponents('dbStatsAdmin', 'template', 'moufTemplate');
MoufManager::getMoufManager()->bindComponents('dbStatsAdmin', 'contentBlock', 'block.content');

?>