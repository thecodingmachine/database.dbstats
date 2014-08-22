<?php
namespace Mouf\Database\Dbstats\controllers;
use Mouf\Html\Widgets\MessageService\Service\UserMessageInterface;

use Mouf\InstanceProxy;

use Mouf\Reflection\MoufReflectionProxy;
use Mouf\Controllers\AbstractMoufInstanceController;

/**
 * The controller to generate automatically the stats table.
 * 
 * @Component
 */
class DbStatsController extends AbstractMoufInstanceController {
	
	
	/**
	 * Admin page used to create the stats table.
	 *
	 * @Action
	 * @Logged
	 */
	public function defaultAction($name, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$this->contentBlock->addFile(__DIR__."/../../../../views/dbStats.php", $this);
		$this->template->toHtml();
	}
	
	/**
	 * This action generates the DAOs and Beans for the TDBM service passed in parameter. 
	 * 
	 * @Action
	 * @param string $name
	 * @param string $selfedit
	 */
	public function generate($name, $dropIfExist = "false", $selfedit="false") {
		$this->initController($name, $selfedit);

		$dbStatsProxy = new InstanceProxy($name, $selfedit == "true");
		$dbStatsProxy->createStatsTable($dropIfExist == "true");
		$dbStatsProxy->createTrigger();
		
		set_user_message("Stats table has been created for instance '$name'", UserMessageInterface::SUCCESS);
		header("Location: ".ROOT_URL."dbStatsAdmin/recomputeForm?name=".urlencode($name)."&selfedit=".$selfedit);
	}
	
	/**
	 * Displays the form asking if the user wants to recompute the stats table.
	 *
	 * @Action
	 * @Logged
	 */
	public function recomputeForm($name, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$this->contentBlock->addFile(__DIR__."/../../../../views/recompute.php", $this);
		$this->template->toHtml();
	}
	
	/**
	 * This action generates the DAOs and Beans for the TDBM service passed in parameter. 
	 * 
	 * @Action
	 * @param string $name
	 * @param string $selfedit
	 */
	public function recompute($name, $transaction = "false", $selfedit="false") {
		$this->initController($name, $selfedit);

		$dbStatsProxy = new InstanceProxy($name, $selfedit == "true");
		$dbStatsProxy->fillTable($transaction=="true");
		set_user_message("Stats table has been recomputed for instance '$name'", UserMessageInterface::SUCCESS);
		
		header("Location: ".ROOT_URL."ajaxinstance/?name=".urlencode($name)."&selfedit=".$selfedit);
	}
	
}