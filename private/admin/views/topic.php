<?php

	include_once __DIR__ . '/../../class/ClassTopic.php';
	include_once __DIR__ . '/../../class/ClassReply.php';
	include_once __DIR__ . '/../../class/ClassValidation.php';

	//ako je POST onda obradi podatke koji su došli sa forme reply_form, koja je include niže
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
		$validation = new Validation($_POST);
		$validation->checkReply();
		$validation->cleanReply();
		
		if($validation->hasPassed() === true && isset($_GET['id']) && (strlen($_GET['id']) < 11)){
			$replyData = $validation->returnData();
			
			$reply = new Reply();
			if($reply->postReply($replyData, $_GET['id']) === true){
				//ažurira 'last_active' atribut teme
				$topic = new Topic();
				$topic->updateActive($_GET['id']);
				self::addGlobalMessage('Odgovor je uspješno objavljen!');
			}
		} else {
			self::addGlobalMessage('Odgovor nije objavljen!');
		}
	}
	
	
	//dio koda koji obrađuje zahtjev admina za brisanje odgovora
	if(isset($_GET['delete_reply_id']) && (strlen($_GET['delete_reply_id']) < 11)){
		$reply = new Reply();

		if($reply->deleteReply($_GET['delete_reply_id']) === true){
			self::addGlobalMessage('Odgovor je uspješno izbrisan!');
		} else {
			self::addGlobalMessage('Odgovor nije izbrisan!');
		}
	} 

	
	//provjerava ako je id teme u redu za provjeru ako je u bazi(kasnije u reply_form)
	if(isset($_GET['id']) && (strlen($_GET['id']) < 11)){
		$topic = new Topic();
		$topicData = $topic->getTopicById($_GET['id']);
		
		//ako ne nađe temu u bazi pokaže poruku, ako nađe pokaže temu, odgovore i formu za novi odgovor
		if($topicData === false){
			echo '<p>Tema nije pronađena!</a>';
		} else {

			//ide na skript gdje pokazuje naslov teme, odgovore, i formu za novi odgovor
			include(__DIR__ . '/topic_display.php');
			//forma za novi odgovor koristi var $topicData da napravi GET link za formu odgovora
			include(__DIR__ . '/reply_form.php');
			
		}
	} else {
		echo '<p>Tema nije odabrana!</a>';
	}

?>
