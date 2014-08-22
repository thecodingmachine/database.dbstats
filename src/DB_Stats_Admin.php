<?php
use Mouf\MoufUtils;
use Mouf\MoufManager;

MoufUtils::registerMainMenu('dbMainMenu', 'DB', null, 'mainMenu', 70);
MoufUtils::registerMenuItem('dbStatsAdminSubMenu', 'DB stats', null, 'dbMainMenu', 70);
MoufUtils::registerChooseInstanceMenuItem('dbStatsGenerateStatAdminSubMenu', 'Generate stat table', 'dbStatsAdmin/', "Mouf\\Database\\Dbstats\\DB_Stats", 'dbStatsAdminSubMenu', 10);
MoufUtils::registerChooseInstanceMenuItem('dbStatsRecomputeStatAdminSubMenu', 'Recompute stat table', 'dbStatsAdmin/recomputeForm', "Mouf\\Database\\Dbstats\\DB_Stats", 'dbStatsAdminSubMenu', 20);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('dbStatsAdmin', 'Mouf\\Database\\Dbstats\\controllers\\DbStatsController', true);
MoufManager::getMoufManager()->bindComponents('dbStatsAdmin', 'template', 'moufTemplate');
MoufManager::getMoufManager()->bindComponents('dbStatsAdmin', 'contentBlock', 'block.content');

?>