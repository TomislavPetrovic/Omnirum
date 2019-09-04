<hr>

<h3>Novi odgovor:</h3>

<form action="topic.php?id=<?php echo $topicData['id']; ?>" method="POST">
    Tekst odgovora: <br>
    <textarea rows="5" cols="50" name="reply_text"></textarea> 
    <br><br>
    <input type="submit" value="Objavi odgovor">
</form>