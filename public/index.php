<?php

	//prvi file koji se inače otvara, iclude view odgovarajućeg korisnika, ovisno o stanju u sesssionu
	//svaki drugi file u ovom direktoriju je oblikovan jednako i radi isto
	include __DIR__ . '/../private/class/ClassRouter.php';
	
	Router::setRoute('home');
	Router::go();

?>