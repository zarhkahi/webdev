<?php
use es\ucm\fdi\aw;

function mostrarSaludo() {
  $html = '';
  $app = aw\Aplicacion::getSingleton();
  if ($app->usuarioLogueado()) {
    $nombreUsuario = $app->nombreUsuario();
    $logoutUrl = $app->resuelve('/logout.php');
    $html = "Bienvenido, ${nombreUsuario}.<a href='${logoutUrl}'>(salir)</a>";
  } else {
    $loginUrl = $app->resuelve('/login.php');
    $html = "Usuario desconocido. <a href='${loginUrl}'>Login</a>";
  }

  return $html;
}

?>

	
<div class="headercontent">
	<h1 class= "hch1">E-Vents</h1>
	<div class = "Buscar">
		 <form method = "GET" action = "search.php">
		 <fieldset>	<input name="search" placeholder="Buscar " type="text">	
			<input type="submit" class="inputSearch" value="Buscar"/></fieldset>
		</form>
	</div>
	<div class="saludo">
	  <?=	mostrarSaludo() ?>
	</div>
</div>

