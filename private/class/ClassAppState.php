<?php
	
	//glavna klasa za praćenje stanja u aplikaciji, sve klasu nju naslijeđuju,
	// i njezine funkcije su dostupne svugdje kroz self::
	class AppState{
		
		protected static function setState(){
			session_start();
			$_SESSION['auth'] = self::checkLoggedIn();
			$_SESSION['user_level'] = self::checkUserLevel();
			
			date_default_timezone_set('Europe/Zagreb');
		}
		
		protected static function checkLoggedIn(){
			if(isset($_SESSION['auth']) && ($_SESSION['auth'] === true)){
				return true;
			} else {
				return false;
			}
		}
		
		protected static function setLoggedIn($loggedIn){
			$_SESSION['auth'] = $loggedIn;
		}
		
		protected static function setUserId($id){
			$_SESSION['id'] = $id;
		}
		
		protected static function getUserId(){
			return $_SESSION['id'];
		}
		
		protected static function checkUserLevel(){
			if(isset($_SESSION['user_level'])){
				return $_SESSION['user_level'];
			} else {
				return 'guest';
			}
		}
		
		protected static function setUserLevel($userLevel){
			$_SESSION['user_level'] = $userLevel;
			
		}
		
		protected static function checkGlobalMessages(){
			if(isset($_SESSION['global_messages']) && !empty($_SESSION['global_messages'])){
				return $_SESSION['global_messages'];
			} else {
				return false;
			}
		}
		
		protected static function addGlobalMessage($message){
			if(!isset($_SESSION['global_messages'])){
				$_SESSION['global_messages'] = array();
			} else {
				array_push($_SESSION['global_messages'], (string)$message);
			}
		}
		
		protected static function getCurrentDatetime(){
			$date = date('Y-m-d H:i:s');
			return $date;
		}
		
		protected static function resetMessages(){
			$_SESSION['global_messages'] = array();
		}
		
		protected static function resetState(){
			session_unset();
			session_destroy();
		}
		
	}



?>