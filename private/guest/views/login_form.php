<?php
	
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		include_once __DIR__ . '/../../class/ClassUser.php';
		include_once __DIR__ . '/../../class/ClassValidation.php';
		
		$validation = new Validation($_POST);
		$validation->checkLogin();
		
		if($validation->hasPassed() === true){
			$validation->cleanLogin();
			$loginData = $validation->returnData();
			
			$user = new User();
			if($user->loginUser($loginData) === true){
				User::addGlobalMessage('Prijava uspješna!');
				header('location: profile.php');
				die();
			}
		} else {
			self::addGlobalMessage('Prijava neuspješna!');
		}
	}
	
?>


<form action="login.php" method="POST">
    Korisničko ime: <br>
    <input type="text" name="username">
    <br><br>
    Lozinka: <br>
    <input type="password" name="password">
    <br><br>
    <input type="submit" value="Prijavi se">
</form>