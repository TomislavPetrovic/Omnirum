<?php
	
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		include_once __DIR__ . '/../../class/ClassValidation.php';
		include_once __DIR__ . '/../../class/ClassTopic.php';
		
		$validation = new Validation($_POST);
		$validation->checkTopicPost();
		
		if($validation->hasPassed() === true){
			$validation->cleanTopic();
			$topicData = $validation->returnData();
			
			$topic = new Topic();
			if($topic->postTopic($topicData) === true){
				self::addGlobalMessage('Tema uspješno objavljena!');
				header('location: forum.php');
			}
		} else {
			self::addGlobalMessage('Tema nije objavljena!');
		}
	}
	
?>

<form action="newtopic.php" method="POST">
    Naslov teme: <br>
    <input type="text" size="100" name="topic_title">
    <br><br>
    Tema: <br>
    <textarea rows="4" cols="100" name="topic_text"></textarea>
    <br><br>
    <input type="submit" value="Objavi temu">
</form>