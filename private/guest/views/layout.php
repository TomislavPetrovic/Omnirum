<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Omnirum</title>
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body class="d-flex flex-column" style="min-height: 100vh">
	<div class="ml-3">
	
		<?php
			//ovdje je glavna stranica cijele aplikacije, sve se izvodi u tijelu ovog layouta(a početno se includea iz Router clase)

			Router::showView(__DIR__, 'navigation_bar');
			
			Router::showView(__DIR__, Router::getRoute());

			Router::showMessages();
			
			Router::showView(__DIR__, 'footer');
			
		?>
		
	</div>
	
    <main class="flex-fill"></main>
    <footer class="page-footer font-small bg-dark text-light">
        <div class="text-center py-3">
            <a href="/radovi">Povratak na radove</a>
        </div>
    </footer>
	
    </body>

</html>