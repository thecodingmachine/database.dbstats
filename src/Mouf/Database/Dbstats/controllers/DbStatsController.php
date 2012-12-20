<?php
namespace Mouf\Database\Dbstats\controllers;
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
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/dbStats.php", $this);
		$this->template->draw();
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
						
		$url = MoufReflectionProxy::getLocalUrlToProject()."plugins/database/dbstats/2.0/direct/generateStatTable.php?name=".urlencode($name)."&selfedit=".$selfedit."&dropIfExist=".$dropIfExist;
		$response = self::performRequest($url);
		
		if (trim($response) != "") {
			throw new Exception($response);
		}
		
		header("Location: ".ROOT_URL."mouf/instance/?name=".urlencode($name)."&selfedit=".$selfedit);
	}
	
	/**
	 * Displays the form asking if the user wants to recompute the stats table.
	 *
	 * @Action
	 * @Logged
	 */
	public function recomputeForm($name, $selfedit="false") {
		$this->initController($name, $selfedit);
		
		$this->template->addContentFile(dirname(__FILE__)."/../views/recompute.php", $this);
		$this->template->draw();
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

		MoufProxy::getInstance($name, $selfedit=="true")->fillTable($transaction=="true");
		
		header("Location: ".ROOT_URL."mouf/instance/?name=".urlencode($name)."&selfedit=".$selfedit);
	}
	
	private static function performRequest($url) {
		// preparation de l'envoi
		$ch = curl_init();
				
		curl_setopt( $ch, CURLOPT_URL, $url);
		
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt( $ch, CURLOPT_POST, FALSE );
		
		if( curl_error($ch) ) { 
			throw new Exception("TODO: texte de l'erreur curl");
		} else {
			// Let's forward all cookies so the session in preserved.
			// Problem: because the session file is locked, we cannot do that without closing the session first
			session_write_close();
			
			$cookieArr = array();
			foreach ($_COOKIE as $key=>$value) {
				$cookieArr[] = $key."=".urlencode($value);
			}
			$cookieStr = implode("; ", $cookieArr);
			curl_setopt($ch, CURLOPT_COOKIE, $cookieStr);
			
			
			$response = curl_exec( $ch );
	
			// And let's reopen the session...
			session_start();
		}
		curl_close( $ch );
		
		return $response;
	}
	
}