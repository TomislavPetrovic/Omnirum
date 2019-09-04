<?php

	include_once __DIR__ . '/../../class/ClassUser.php';
	//inicijalizira korisnika da bude dostupan za popunjavanje forme
	$user = new User();
	
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		include_once __DIR__ . '/../../class/ClassValidation.php';
		
		$validation = new Validation($_POST);
		$validation->checkUserUpdate();
		
		if($validation->hasPassed() === true){
			$registrationData = $validation->returnData();
			
			if($user->updateUser($registrationData) === true){
				self::addGlobalMessage('Podaci uspješno ažurirani!');
				header('location: profile.php');
				die();
			} else {
				self::addGlobalMessage('Ažuriranje neuspješno!');
			}
		} else {
			self::addGlobalMessage('Ažuriranje neuspješno!');
		}
	}
	
	//priprema podatke za popuniti formu, da je korisniku lakše
	$formData = $user->getCurrentUser();

?>

<h3>Unesite nove osobne informacije</h3>

<form action="edituser.php" method="POST">
    Ime: <br>
    <input type="text" name="name" value="<?php echo $formData['name']; ?>">
    <br><br>
    Prezime: <br>
    <input type="text" name="surname" value="<?php echo $formData['surname']; ?>">
    <br><br>
    Email: <br>
    <input type="email" name="email" value="<?php echo $formData['email']; ?>">
    <br><br>
    Korisničko ime: <br>
    <input type="text" name="username" value="<?php echo $formData['username']; ?>">
    <br><br>
    Lozinka: <br>
    <input type="password" name="password">
    <br><br>
	Nova lozinka: <br>
    <input type="password" name="new_password">
    <br><br>
	O meni: <br>
	<textarea rows="5" cols="50" name="about"><?php echo $formData['about']; ?></textarea> 
	<br>
    <input type="submit" value="Ažuriraj podatke">
</form>