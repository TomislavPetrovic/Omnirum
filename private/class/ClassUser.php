<?php
	
	//model korisnika, obrađuje zadatke koji uključuju dohvaćanje podataka iz baze o korisniku
	include_once(__DIR__ . '/ClassDatabase.php');

    class User extends Database{
		
		private $userInfo;
		
		
		public function userExists($userName, $email, $excludeCurrentUserFlag = false){
		    $sql = 'SELECT * FROM users';
            $sql .= ' WHERE username = :username OR email = :email';
			//dodatak za updateUser(), kad se email ili lozinka pokuša ažurirati na već postojeću
			if($excludeCurrentUserFlag === true){
				$sql = 'SELECT * FROM users';
				$sql .= ' WHERE (username = :username OR email = :email)';
				$sql .= ' AND id <> ' . self::getUserId();
			}

			$query = self::prepareQuery($sql);
			$query->bindParam(':username', $userName, PDO::PARAM_STR, 50);
			$query->bindParam(':email', $email, PDO::PARAM_STR, 50);
			$this->executeQuery($query);
			$this->userInfo = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($this->userInfo) === 1){
				return true;
			} else {
				return false;
			}
		}
        
		
        public function registerUser($userData){
			if($this->userExists($userData['username'], $userData['email']) === false){
				$sql = 'INSERT INTO users(name, surname, email, username, password, time_registered, last_active)';
				$sql .= ' VALUES(:name, :surname, :email, :username, :password, :time_registered, :last_active)';
				
				$currentTime = self::getCurrentDatetime();
				$userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
				
				$query = self::prepareQuery($sql);
				$query->bindParam(':name', $userData['name'], PDO::PARAM_STR, 50);
				$query->bindParam(':surname', $userData['surname'], PDO::PARAM_STR, 50);
				$query->bindParam(':email', $userData['email'], PDO::PARAM_STR, 50);
				$query->bindParam(':username', $userData['username'], PDO::PARAM_STR, 50);
				$query->bindParam(':password', $userData['password'], PDO::PARAM_STR, 100);
				$query->bindParam(':time_registered', $currentTime , PDO::PARAM_STR);
				$query->bindParam(':last_active', $currentTime , PDO::PARAM_STR);
				if($this->executeQuery($query) === true){
					self::addGlobalMessage('Korisnik uspješno registriran!');
					return true;
				}
			} else {
				self::addGlobalMessage('Korisnik već postoji!');
				self::addGlobalMessage('Registracija neuspješna!');
				return false;
			}
				
        }
		
		
		public function loginUser($userData){
			$username = $userData['username'];
			$email = null;
			$password = $userData['password'];

			if($this->userExists($username, $email) === true){
				$candidateUser = $this->userInfo[0];
				if(password_verify ($password, $candidateUser['password'])){
					self::setLoggedIn(true);
					self::setUserId($candidateUser['id']);
					if($candidateUser['admin'] === '1'){
						self::setUserLevel('admin');
					} else {
						self::setUserLevel('user');
					}
					self::addGlobalMessage('Korisnik prijavljen!');
					return true;
				}
			}
			self::addGlobalMessage('Korisnik nije prijavljen!');
			return false;
		}
		
		
		public function getCurrentUser(){
		    $sql = 'SELECT * FROM users';
            $sql .= ' WHERE id = :id';
			$currentUserId = $this->getUserId();
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id', $currentUserId, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			$this->userInfo = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($this->userInfo) === 1){
				//vrati tog jednog korisnika, i očisti podatke prije vraćanja
				return $this->cleanData($this->userInfo[0]);
			} else {
				return false;
			}
		}
		
		
		public function getUserById($id){
		    $sql = 'SELECT * FROM users';
            $sql .= ' WHERE id = :id';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			$this->userInfo = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($this->userInfo) === 1){
				return $this->cleanData($this->userInfo[0]);
			} else {
				return false;
			}
		}
		
		
		public function updateUser($userData){
			
			$currentUser = $this->getCurrentUser();
			
			if($currentUser === false){
				return false;
			} else if(password_verify($userData['password'], $currentUser['password']) === false){
				self::addGlobalMessage('Postojeća lozinka i nova lozinka se ne poklapaju!');
				return false;
				//provjera ako već postoji korisnik s novim podacima
			} else if($this->userExists($userData['username'], $userData['email'], true) === true){
				self::addGlobalMessage('Korisnik sa istim podacima već postoji!');
				return false;
			} else {
				
				$sql = 'UPDATE users SET name = :name, surname = :surname, email = :email, about = :about, username = :username, password = :new_password';
				$sql .= ' WHERE id = :id';

				$currentId = self::getUserId();
				$userData['new_password'] = password_hash($userData['new_password'], PASSWORD_DEFAULT);
				
				$query = self::prepareQuery($sql);
				$query->bindParam(':name', $userData['name'], PDO::PARAM_STR, 50);
				$query->bindParam(':surname', $userData['surname'], PDO::PARAM_STR, 50);
				$query->bindParam(':email', $userData['email'], PDO::PARAM_STR, 50);
				$query->bindParam(':about', $userData['about'], PDO::PARAM_STR, 10000);
				$query->bindParam(':username', $userData['username'], PDO::PARAM_STR, 50);
				$query->bindParam(':new_password', $userData['new_password'], PDO::PARAM_STR, 100);
				$query->bindParam(':id', $currentId, PDO::PARAM_INT, 11);
				
				if($this->executeQuery($query) === true){
					return true;
				}else {
					return false;
				}
			}
				
        }
		
		
		public function deleteTopicUser($userId){
			$sql = 'DELETE FROM topic_user';
            $sql .= ' WHERE id_user = :id_user';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id_user', $userId, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			
			if($query->rowCount() >= 1){
				return true;
			} else {
				return false;
			}
		}
		
		
		public function deleteReplyUser($userId){
			$sql = 'DELETE FROM reply_user';
            $sql .= ' WHERE id_user = :id_user';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id_user', $userId, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			
			if($query->rowCount() >= 1){
				return true;
			} else {
				return false;
			}
		}
		
		
		public function deleteUser($id){
		    $sql = 'DELETE FROM  users';
            $sql .= ' WHERE id = :id';
            $sql .= ' LIMIT 1';
			
			//briše veze sa korisnikom prije brisanja korisnika
			$this->deleteTopicUser($id);
			$this->deleteReplyUser($id);
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			
			if($query->rowCount() === 1){
				return true;
			} else {
				return false;
			}
		}
		
    }

?>