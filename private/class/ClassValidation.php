<?php
	
	include_once(__DIR__ . '/ClassAppState.php');
	
	class Validation extends AppState{
		
		public $passed;
		private $data;
		
		public function __construct($data){
			$this->passed = true;
			$this->data = $data;
		}
		
		public function hasPassed(){
			return $this->passed;
		}
		
		public function returnData(){
			return  $this->data;
		}
		
		private function checkEmpty($data, $fieldName){
			if(empty($data)){
				self::addGlobalMessage('Polje '.$fieldName.' ne smije biti prazno!');
				return true;
			}
			return false;
		}
			
		private function checkLength($data, $fieldName, $max = 50, $min = 0){
			$length = strlen($data);
			
			if($length > $max){
				self::addGlobalMessage('Polje '.$fieldName.' ne smije biti duže od '.$max.' znakova!');
				return false;
			} else if($length < $min){
				self::addGlobalMessage('Polje '.$fieldName.' ne smije biti kraće od '.$min.' znakova!');
				return false;
			}
			
			return true;
		}
		
		private function checkName($name, $fieldName){
			$name = trim($name);
			if($this->checkEmpty($name, $fieldName) === true){
				return false;
			} else if($this->checkLength($name, $fieldName, 50) === false){
				return false;
			}
			
			return true;
		}
		
		private function checkEmail($email, $fieldName){
			$email = trim($email);
			if($this->checkEmpty($email, $fieldName) === true){
				return false;
			} else if($this->checkLength($email, $fieldName, 50) === false){
				return false;
			} else if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
				self::addGlobalMessage('Polje '.$fieldName.' mora sadržavati ispravan email!');
				return false;
			}
			
			return true;
		}
		
		public function checkPassword($password, $fieldName){
			if($this->checkEmpty($password, $fieldName) === true){
				return false;
			} else if($this->checkLength($password, $fieldName, 100) === false){
				return false;
			}
			
			return true;
		}
		
		public function cleanRegistration(){
			//uklanja razmake u podacima za registraciju u kojima je to prikladno
			$this->data['name'] = trim($this->data['name']);
			$this->data['name'] = strip_tags($this->data['name']);
			$this->data['surname'] = trim($this->data['surname']);
			$this->data['surname'] = strip_tags($this->data['surname']);
			$this->data['email'] = trim($this->data['email']);
			$this->data['email'] = strip_tags($this->data['email']);
			$this->data['username'] = trim($this->data['username']);
		}
		
		public function checkRegistration(){
			if($this->checkName($this->data['name'], "'Ime'") === false){
				$this->passed = false;
			}
			if($this->checkName($this->data['surname'], "'Prezime'") === false){
				$this->passed = false;
			}
			if($this->checkEmail($this->data['email'], "'Email'") === false){
				$this->passed = false;
			}
			if($this->checkName($this->data['username'], "'Korisničko ime'") === false){
				$this->passed = false;
			}
			if($this->checkPassword($this->data['password'], "'Lozinka'") === false){
				$this->passed = false;
			}
		}
		
		public function cleanLogin(){
			//uklanja razmake u podacima za prijavu u kojima je to prikladno
			$this->data['username'] = trim($this->data['username']);
		}
		
		public function checkLogin(){
			if($this->checkName($this->data['username'], "'Korisničko ime'") === false){
				$this->passed = false;
			}
			if($this->checkPassword($this->data['password'], "'Lozinka'") === false){
				$this->passed = false;
			}
		}
		
		private function checkTitle($title, $fieldName){
			$title = trim($title);
			if($this->checkEmpty($title, $fieldName) === true){
				return false;
			} else if($this->checkLength($title, $fieldName, 200) === false){
				return false;
			}
			
			return true;
		}
		
		//koristi se i za polje teksta teme i za individualni odgovor
		public function checkReplyText($text, $fieldName){
			//provjeri u testiranju ako se data za temu uspješno trima kad je ovaj ttrim zakomentirano
			//$text = trim($text);
			if($this->checkEmpty($text, $fieldName) === true){
				return false;
			} else if($this->checkLength($text, $fieldName, 10000) === false){
				return false;
			}
			
			return true;
		}
		
		//koristi se za odgovor na temi
		public function checkReply(){
			if($this->checkReplyText($this->data['reply_text'], "'Tekst odgovora'") === false){
				$this->passed = false;
			}
		}
		
		public function cleanReply(){
			$this->data['reply_text'] = trim($this->data['reply_text']);
			$this->data['reply_text'] = strip_tags($this->data['reply_text']);
		}
		
		public function cleanTopic(){
			//uklanja razmake u podacima za novu temu u kojima je to prikladno
			$this->data['topic_title'] = trim($this->data['topic_title']);
			$this->data['topic_title'] = strip_tags($this->data['topic_title']);
			$this->data['topic_text'] = trim($this->data['topic_text']);
			$this->data['topic_text'] = strip_tags($this->data['topic_text']);
		}
		
		public function checkTopicPost(){
			if($this->checkTitle($this->data['topic_title'], "'Naslov teme'") === false){
				$this->passed = false;
			}
			if($this->checkReplyText($this->data['topic_text'], "'Tema'") === false){
				$this->passed = false;
			}
		}
		
		
		//napravi gotovo isto kao u checkRegistration()
		public function checkUserUpdate(){
			//check registration radi većinu istih provjera
			$this->checkRegistration();
			$this->cleanRegistration();
			
			if($this->checkPassword($this->data['new_password'], "'Nova lozinka'") === false){
				$this->passed = false;
			}

			$this->data['about'] = trim($this->data['about']);
		}
		
	}

?>