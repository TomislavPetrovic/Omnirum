<?php
	
	include_once __DIR__ . '/ClassDatabase.php';
	
	//klasa koja sadrži metode za dohvaćanje/ažuriranje podataka iz baze potrebnim u kontekstu teme
	// uključujući i podatke iz drugih tablica kada je to potrebno na stranici
	class Topic extends Database{
		
		
		public function addTopicUser($topicId, $userId){
			$sql = 'INSERT INTO topic_user(id_topic, id_user)';
			$sql .= ' VALUES(:id_topic, :id_user)';

			$query = self::prepareQuery($sql);
			$query->bindParam(':id_topic', $topicId, PDO::PARAM_INT, 11);
			$query->bindParam(':id_user', $userId, PDO::PARAM_INT, 11);

			
			if($this->executeQuery($query) === true){
				return true;
			} else {
				return false;
			}

		}
		
		
		public function postTopic($topicData){
			$sql = 'INSERT INTO topic(topic_title, topic_text, time_created, last_active)';
			$sql .= ' VALUES(:topic_title, :topic_text, :time_created, :last_active)';
			$currentUserId = self::getUserId();
			$currentTime = self::getCurrentDatetime();

			$query = self::prepareQuery($sql);
			$query->bindParam(':topic_title', $topicData['topic_title'], PDO::PARAM_STR, 200);
			$query->bindParam(':topic_text', $topicData['topic_text'], PDO::PARAM_STR, 10000);
			$query->bindParam(':time_created', $currentTime, PDO::PARAM_STR);
			$query->bindParam(':last_active', $currentTime, PDO::PARAM_STR);
			
			if($this->executeQuery($query) === true){
				$topicId = self::$connection->lastInsertId();
				if($this->addTopicUser($topicId, $currentUserId) === true){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}

		}
		
		
		//dohvaća sve teme, uključujući i korisničko ime autora i broj odgovora(za prikaz liste tema)
		public function getAllTopics(){
			$sql = 'SELECT topic.*, users.username, users.id AS creator_id, COUNT(reply_topic.id_topic) AS reply_count FROM topic';
			$sql .= ' LEFT JOIN topic_user ON topic.id = topic_user.id_topic';
			$sql .= ' LEFT JOIN users ON topic_user.id_user = users.id';
			$sql .= ' LEFT JOIN reply_topic ON topic.id = reply_topic.id_topic';
			$sql .= ' GROUP BY topic.id';
			
			$query = self::prepareQuery($sql);
			
			if($this->executeQuery($query) === true){
				$allTopics = $query->fetchAll(PDO::FETCH_ASSOC);
				foreach($allTopics as $key => $item){
					$allTopics[$key] = $this->cleanData($item);
				}
				return $allTopics;
			} else {
				return false;
			}

		}
		
		
		public function getTopicById($id){
			//uključuje ime korisnika pomoću join-a
			$sql = 'SELECT topic.*, users.username, users.id AS creator_id FROM topic';
			$sql .= ' LEFT JOIN topic_user ON topic.id = topic_user.id_topic';
			$sql .= ' LEFT JOIN users ON topic_user.id_user = users.id';
			$sql .= ' WHERE topic.id = :id';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT, 11);
			
			$passed = $this->executeQuery($query);
			
			$results = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if($passed === true && count($results) == 1){
				return $this->cleanData($results[0]);
			} else {
				return false;
			}

		}
		
		
		public function updateActive($id){
			$sql = 'UPDATE topic SET last_active = :last_active';
			$sql .= ' WHERE id = :id';
			
			$currentTime = self::getCurrentDatetime();
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':last_active', $currentTime, PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_INT, 11);
			
			if($this->executeQuery($query) === true){
				return true;
			}else {
				return false;
			}
		}
		
		
		//briše vezu odgovora(sa teme) sa korisnicima da bi se ti odgovori kasnije mogli pobrisati
		public function deleteReplyUser($topicId){
			$sql = 'DELETE FROM reply_user';
            $sql .= ' WHERE reply_user.id_reply IN(';
            $sql .= ' SELECT reply_topic.id_reply FROM reply_topic';
            $sql .= ' WHERE reply_topic.id_topic = :id_topic)';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id_topic', $topicId, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			
			if($query->rowCount() >= 1){
				return true;
			} else {
				return false;
			}
		}
		
		
		public function deleteReplyTopic($topicId){
			$sql = 'DELETE FROM reply_topic';
            $sql .= ' WHERE id_topic = :id_topic';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id_topic', $topicId, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			
			if($query->rowCount() >= 1){
				return true;
			} else {
				return false;
			}
		}
		
		
		public function deleteTopicUser($topicId){
			$sql = 'DELETE FROM topic_user';
            $sql .= ' WHERE id_topic = :id_topic';
            $sql .= ' LIMIT 1';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id_topic', $topicId, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			
			if($query->rowCount() === 1){
				return true;
			} else {
				return false;
			}
		}
		
		
		//dovršava proces brisanja teme, briše odgovore iz baze koji ne pripadaju nijednoj temi
		public function deleteOrphanReplies(){
			$sql = 'DELETE FROM reply';
            $sql .= ' WHERE reply.id NOT IN(';
            $sql .= ' SELECT reply_topic.id_reply FROM reply_topic)';
			
			$query = self::prepareQuery($sql);
			$this->executeQuery($query);
			
			if($query->rowCount() >= 1){
				return true;
			} else {
				return false;
			}
		}
		
		
		public function deleteTopic($id){
		    $sql = 'DELETE FROM topic';
            $sql .= ' WHERE id = :id';
            $sql .= ' LIMIT 1';
			
			//briše podatke vezane sa temom prije brisanja
			$this->deleteReplyUser($id);
			$this->deleteReplyTopic($id);
			$this->deleteTopicUser($id);
			$this->deleteOrphanReplies();
			
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