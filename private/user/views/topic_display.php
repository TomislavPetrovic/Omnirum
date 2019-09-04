<a href="forum.php">Povratak na forum</a> | 
<a href="newtopic.php">Nova tema</a>
<br><br>

<table style="border: 2px solid">
	<tr>
		<td style="width:200ch; "><strong>Naslov teme: </strong><?php echo $topicData['topic_title']; ?></td>
	</tr>
	<tr>
	
<?php

	//ako je korisnik koji je unio odgovor izbrisan(nepostojeći)
	if(empty($topicData['username'])){
		echo '<td>By:  NEPOSTOJEĆI KORISNIK</td>';
	} else {
		echo '<td>By:  <a href="profile.php?id='.$topicData['creator_id'].'">'.$topicData['username'].'</a></td>';
	}
	
?>

	</tr>
	<tr>
		<td><?php echo $topicData['time_created']; ?></td>
	</tr>
	<tr>
		<td style="border: 1px solid"><?php echo $topicData['topic_text']; ?></td>
	</tr>
</table>
<br>

<?php

	//ovaj skript se include iz topic.php, i koristi variable od tamo za prikaz teme i odgovora iz baze
	
	$reply = new Reply();
	$topicReplies = $reply->getRepliesByTopicId($topicData['id']);
	
	foreach($topicReplies as $reply):
?>

<table style="border: 1px solid">
	<tr>
	
<?php

	//ako je korisnik koji je unio odgovor izbrisan(nepostojeći)
	if(empty($reply['username'])){
		echo '<td>By:  NEPOSTOJEĆI KORISNIK</td>';
	} else {
		echo '<td>By:  <a href="profile.php?id='.$reply['id_user'].'">'.$reply['username'].'</a></td>';
	}
	
?>

	</tr>
	<tr>
		<td><?php echo $reply['time_created']; ?></td>
	</tr>
	<tr>
		<td style="width:100ch; border: 1px solid;"><?php echo $reply['reply_text']; ?></td>
	</tr>
</table>
<br>

<?php
	endforeach;
?>