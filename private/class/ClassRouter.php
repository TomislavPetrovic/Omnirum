<?php
	
	include_once(__DIR__ . '/ClassAppState.php');
		
	//klasa za početno preusmjeravanje na pravi pogled, ovisno o razini korisnika
	//i prikaz/include pogleda/skripti
	class Router extends AppState{
		
		protected static $route = 'home';
		
		public static function setRoute($route){
			parent::setState();
			//kad preko public logout.php dobije rutu 'logout', samo izazove reset sessiona,
			//ruta ostane po defaultu 'home' i njega prikaže
			if($route == 'logout'){
				self::resetState();
			} else {
				self::$route = $route;
			}
		}
		
		
		//ovdje se include sav kod aplikacije
		public static function go(){
			ob_start();
			if(self::checkLoggedIn() === true){
				if(self::checkUserLevel() === 'admin'){
					include __DIR__ . '/../admin/views/layout.php';
				} else {
					include __DIR__ . '/../user/views/layout.php';
				}
			} else {
				include __DIR__ . '/../guest/views/layout.php';
			}
			ob_end_flush();
		}
		
		
		public static function getRoute(){
			return self::$route;
		}
		
		
		public static function showView($dir, $route, $data = []){
			
			$path = $dir . '/' . $route . '.php';
				
			if(file_exists($path)){
				include $path;
			} else {
				include $dir . '/' . 'home' . '.php';
			}
		}
		
		
		public static function showMessages(){
			$messages = self::checkGlobalMessages();
			if($messages === false){

			} else {
				foreach($messages as $message){
					echo '<p style="color:purple"><i>' . $message . '</p></i>';
				}
				self::resetMessages();
			}
		}
		
	}

?>