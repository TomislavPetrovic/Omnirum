<?php

	include_once __DIR__ . '/../../class/ClassTopic.php';
	include_once __DIR__ . '/../../class/ClassReply.php';
	include_once __DIR__ . '/../../class/ClassValidation.php';

	
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

			
		}
	} else {
		echo '<p>Tema nije odabrana!</a>';
	}
	

?>
