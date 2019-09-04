<?php

	include_once __DIR__ . '/../../class/ClassUser.php';
	include_once __DIR__ . '/../../class/ClassValidation.php';
	
	//priprema $get variablu za provjeru ako je neki specifični id poslan
	if(isset($_GET['id']) && (strlen($_GET['id']) < 11)){
		$get = $_GET['id'];
		
		$user = new User();
		$deleteUserData = $user->getUserById($_GET['id']);
	} else {
		$get = false;
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		
		if($_POST['choice'] === 'Da'){
			
			$validation = new Validation($_POST);
			$passed = $validation->checkPassword($_POST['password'], "'Lozinka'");
			$user = new User();
			$currentUser = $user->getCurrentUser();
			
			if($passed === true){
				if(password_verify($_POST['password'], $currentUser['password'])){
					
					//ako je $get odradi brisanje na $deleteUserData['id'], ako ne onda na ulogiranom korisniku
					if($get === false){
						if($user->deleteUser($currentUser['id'])){
							User::resetState();
							header('location: index.php');
						} else {
							User::addGlobalMessage('Korisnik nije obrisan!');
						}
					} else {
						//stao si ovdje 22.5
						if($user->deleteUser($deleteUserData['id'])){
							User::resetState();
							header('location: index.php');
						} else {
							User::addGlobalMessage('Korisnik nije obrisan!');
						}
					}
					
				} else {
					User::addGlobalMessage('Lozinka nije točna!');
				}
			}
		} else {
			header('location: profile.php');
		}
	}

	//dodaje get linku forme ako je zahtjev za brisanjem došao sa njime
	if($get === false){
		echo '<form action="deleteuser.php" method="POST">';
	} else {
		echo '<form action="deleteuser.php?id='.$get.'" method="POST">';
	}
?>

    Lozinka: <br>
    <input type="password" name="password">
    <br><br>
	
	<p>Jeste li sigurni da želite izbrisati korisnika<?php if($get !== false){echo ' "'.$deleteUserData['username'].'"';} ?>?</p>
    <input type="submit" value="Da" name="choice">
    <input type="submit" value="Ne" name="choice">
</form>