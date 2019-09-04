<?php
	
	include_once __DIR__ . '/../../class/ClassUser.php';

	$user = new User();
	if(isset($_GET['id']) && (strlen($_GET['id']) < 11)){
		$get = $_GET['id'];
	} else {
		$get = false;
	}
	
	if($get !== false){
		$currentUserData = $user->getUserById($_GET['id']) ;
	} else {
		$currentUserData = $user->getCurrentUser() ;
	}
	
?>

<h3>Osobne informacije:</h3>
<table border="1" style="text-align:center;">
	<tr>
		<th>Ime: </th>
		<td><?php echo $currentUserData['name'] ?></td>
	</tr>
	<tr>
		<th>Prezime: </th>
		<td><?php echo $currentUserData['surname'] ?></td>
	</tr>
	<tr>
		<th>Email: </th>
		<td><?php echo $currentUserData['email'] ?></td>
	</tr>
	<tr>
		<th>Korisničko ime: </th>
		<td><?php echo $currentUserData['username'] ?></td>
	</tr>
	<tr>
		<th>O meni: </th>
		<td style="width:50ch;"><?php echo $currentUserData['about'] ?></td>
	</tr>
</table>


<br><hr>

<?php

	//pokaže linkove za ažuriranje i brisanje ako nema geta, tj ako trenutni korisnik gleda profil
	if($get === false){
		echo '<a href="edituser.php">Ažuriraj podatke</a> | ';
		echo '<a href="deleteuser.php">Izbriši korisnika</a>';
	}
	
?>