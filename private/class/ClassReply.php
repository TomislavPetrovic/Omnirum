<?php
	
	include_once __DIR__ . '/ClassDatabase.php';
	
	//model odgovora
	class Reply extends Database{
		
		
		public function addReplyTopic($replyId, $topicId){
			$sql = 'INSERT INTO reply_topic(id_reply, id_topic)';
			$sql .= ' VALUES(:id_reply, :id_topic)';

			$query = self::prepareQuery($sql);
			$query->bindParam(':id_reply', $replyId, PDO::PARAM_INT, 11);
			$query->bindParam(':id_topic', $topicId, PDO::PARAM_INT, 11);
			
			if($this->executeQuery($query) === true){
				return true;
			} else {
				return false;
			}

		}
		
		
		public function addReplyUser($replyId, $userId){
			$sql = 'INSERT INTO reply_user(id_reply, id_user)';
			$sql .= ' VALUES(:id_reply, :id_user)';

			$query = self::prepareQuery($sql);
			$query->bindParam(':id_reply', $replyId, PDO::PARAM_INT, 11);
			$query->bindParam(':id_user', $userId, PDO::PARAM_INT, 11);
			
			if($this->executeQuery($query) === true){
				return true;
			} else {
				return false;
			}

		}
		
		
		public function postReply($replyData,$topicId){
			$sql = 'INSERT INTO reply(reply_text, time_created, time_edited)';
			$sql .= ' VALUES(:reply_text, :time_created, :time_edited)';
			$currentUserId = self::getUserId();
			$currentTime = self::getCurrentDatetime();
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':reply_text', $replyData['reply_text'], PDO::PARAM_STR, 10000);
			$query->bindParam(':time_created', $currentTime, PDO::PARAM_STR);
			$query->bindParam(':time_edited', $currentTime, PDO::PARAM_STR);
			
			if($this->executeQuery($query) === true){
				$replyId = self::$connection->lastInsertId();
				if($this->addReplyTopic($replyId, $topicId) === true && $this->addReplyUser($replyId, $currentUserId) === true) {
					return true;
				} else {
					return true;
				}
			} else {
				return false;
			}

		}
		
		
		//dohvaca sve informacije o odgovorima, i ime i id korisnika koji ga je objavio
		//(za prikaz na stranici teme)
		public function getRepliesByTopicId($topicId){
			$sql = 'SELECT reply.*, users.username, reply_topic.id_topic AS id_topic, reply_user.id_user AS id_user  FROM reply';
			$sql .= ' LEFT JOIN reply_topic ON reply.id = reply_topic.id_reply';
			$sql .= ' LEFT JOIN reply_user ON reply.id = reply_user.id_reply';
			$sql .= ' LEFT JOIN users ON reply_user.id_user = users.id';
			$sql .= ' WHERE reply_topic.id_topic = :id_topic';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id_topic', $topicId, PDO::PARAM_INT, 11);
			
			$passed = $this->executeQuery($query);
			
			if($passed === true){
			$results = $query->fetchAll(PDO::FETCH_ASSOC);
				return $this->cleanData($results);
			} else {
				return false;
			}

		}
		
		
		public function deleteReplyUser($replyId){
			$sql = 'DELETE FROM reply_user';
            $sql .= ' WHERE id_reply = :id_reply';
            $sql .= ' LIMIT 1';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id_reply', $replyId, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			
			if($query->rowCount() === 1){
				return true;
			} else {
				return false;
			}
		}
		
		
		public function deleteReplyTopic($replyId){
			$sql = 'DELETE FROM reply_topic';
            $sql .= ' WHERE id_reply = :id_reply';
            $sql .= ' LIMIT 1';
			
			$query = self::prepareQuery($sql);
			$query->bindParam(':id_reply', $replyId, PDO::PARAM_INT, 11);
			$this->executeQuery($query);
			
			if($query->rowCount() === 1){
				return true;
			} else {
				return false;
			}
		}
		
		
		public function deleteReply($id){
		    $sql = 'DELETE FROM reply';
            $sql .= ' WHERE id = :id';
            $sql .= ' LIMIT 1';
			
			//brisanje povezanih unosa u reply_topic i reply_user
			$this->deleteReplyTopic($id);
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