<?php

	include_once __DIR__ . '/../../class/ClassUser.php';
	include_once __DIR__ . '/../../class/ClassValidation.php';

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		
		if($_POST['choice'] === 'Da'){
			
			$validation = new Validation($_POST);
			$passed = $validation->checkPassword($_POST['password'], "'Lozinka'");
			$user = new User();
			$currentUser = $user->getCurrentUser();
			
			if($passed === true){
				if(password_verify($_POST['password'], $currentUser['password'])){
					//ako šifra trenutnog korisnika odgovara, izbriši trenutnog korisnika i logout
					if($user->deleteUser($currentUser['id'])){
						User::resetState();
						header('location: index.php');
					} else {
						User::addGlobalMessage('Korisnik nije obrisan!');
					}
				} else {
					User::addGlobalMessage('Lozinka nije točna!');
				}
			}
		} else {
			header('location: profile.php');
		}
	}

?>

<form action="deleteuser.php" method="POST">
    Lozinka: <br>
    <input type="password" name="password">
    <br><br>
	
	<p>Jeste li sigurni da želite izbrisati korisnika?</p>
    <input type="submit" value="Da" name="choice">
    <input type="submit" value="Ne" name="choice">
</form>