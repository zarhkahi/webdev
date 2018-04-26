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
<div id="cabecera">
	<h1>E-Vents</h1>
	<div class = "Buscar">
		 <form method = "GET" action = "search.php">
	   		<input class="form-control moduleField smallSearchField required dooFinderSearch"
		    id="smallSearchCriteriaField" name="search" placeholder="Buscar " value="" 
		    data-positional="{&quot;on_right_side&quot;:true,&quot;left_side&quot;:355,&quot;right_side&quot;:1013}" type="text">	
			<input type="submit" class="boton" value="Buscar"/>
		</form>
	</div>
	<div class="saludo">
	  <?=	mostrarSaludo() ?>
	</div>
</div>

