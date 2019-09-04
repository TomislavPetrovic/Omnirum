<?php

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		include_once __DIR__ . '/../../class/ClassUser.php';
		include_once __DIR__ . '/../../class/ClassValidation.php';
		
		$validation = new Validation($_POST);
		$validation->checkRegistration();
		
		if($validation->hasPassed() === true){
			$validation->cleanRegistration();
			$registrationData = $validation->returnData();
			
			$user = new User();
			if($user->registerUser($registrationData) === true){
				User::addGlobalMessage('Registracija uspješna!');
				$user->loginUser([
					'username' => $registrationData['username'],
					'password' => $registrationData['password']
				]);
				header('location: profile.php');
				die();
			}
		} else {
			User::addGlobalMessage('Registracija neuspješna!');
		}
	}
	
?>

<form action="registration.php" method="POST">
    Ime: <br>
    <input type="text" name="name">
    <br><br>
    Prezime: <br>
    <input type="text" name="surname">
    <br><br>
    Email: <br>
    <input type="email" name="email">
    <br><br>
    Korisničko ime: <br>
    <input type="text" name="username">
    <br><br>
    Lozinka: <br>
    <input type="password" name="password">
    <br><br>
    <input type="submit" value="Registriraj se">
</form>