<a href="newtopic.php">Nova tema</a>
<br><hr>

<h3>Popis tema na forumu:</h3>

<?php

	include_once __DIR__ . '/../../class/ClassTopic.php';
	
	$topic = new Topic();
	//dohvaća sve teme iz baze podataka i prikazuje ih na stranici
	$allTopics = $topic->getAllTopics();
	
	if(empty($allTopics)):
		echo '<h4>Nema tema na forumu!</h4>';
	else:
?>

<table border="1">
	<tr style="text-align: center;">
		<th>Naziv teme</th>
		<th>Autor</th>
		<th>Broj odgovora</th>
		<th>Zadnja aktivnost</th>
		<th>Objavljena</th>
	</tr>
	
<?php
	foreach($allTopics as $item):
?>

	<tr>
		<td style="width:150ch;"><a href="topic.php?id=<?php echo $item['id']; ?>">&nbsp<?php echo $item['topic_title']; ?></a></td>
		
<?php

	if(empty($item['creator_id'])){
		echo '<td>NEPOSTOJEĆI KORISNIK</td>';
	} else {
		echo '<td>&nbsp<a href="profile.php?id='.$item['creator_id'].'">'.$item['username'].'</a>&nbsp</td>';
	}

?>

		<td style="text-align: center;">&nbsp<?php echo $item['reply_count']; ?>&nbsp</td>
		<td>&nbsp<?php echo $item['last_active']; ?>&nbsp</td>
		<td>&nbsp<?php echo $item['time_created']; ?>&nbsp</td>
	</tr>

<?php
	endforeach;
	echo '</table>';
	endif;
?>