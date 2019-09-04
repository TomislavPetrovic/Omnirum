<?php

    include_once(__DIR__ . '/../config/database.php');
	include_once(__DIR__ . '/ClassAppState.php');
	
	//temeljna klasa za klase koje koriste pristup bazi podataka
	//ovaj konstruktor se implicitno zove kad se instnciraju naslijeđene klase
    class Database extends AppState{
        protected static $connection;
        
        public function __construct() {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname='. DB_NAME . ';charset=utf8';

            try {
                self::$connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $error) {
				//var_dump($error->getMessage());
				die('Greška u sustavu!');
            }

            return true;
        }
		
		protected function executeQuery($query){
			try{
				$query->execute();
			} catch(PDOException $error) {
				//var_dump($error->getMessage());
				die('Greška u sustavu!');
            }
			return true;
		}
		
		protected function prepareQuery($sql){
			try{
				$query = self::$connection->prepare($sql);
			} catch(Exception $e) {
				//var_dump($e->getMessage());
				die('Greška u sustavu!');
			}
	
			return $query;
		}
		
		//funkcija koja uklanja potencijalno štetne html znakove iz više vrsta variabli
		protected function cleanData($data){
			if(is_array($data) === true){
				foreach($data as $key => $item){
					if(is_array($item) === true){
						foreach($item as $subKey => $subItem){
							$data[$key][$subKey] = htmlspecialchars($subItem);
						}
					} else{
						$data[$key] = htmlspecialchars($item);
					}
				}
			} else {
				$data = htmlspecialchars($data);
			}
			return $data;
		}
		

    }

?>