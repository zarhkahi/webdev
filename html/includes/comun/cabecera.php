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

	
<div class="header">
  <div class= "headerContent">
      <h1 class= "webTitle">E-Vents</h1>
      <!--<img src="/AW/img/logo.svg" width="32" height"32">-->
  </div>
      
  <div class = "search">
      <form class = "formSearch" method = "GET" action = "searchEvents.php">
      <input type="text" name="search" placeholder="Buscar ">	
		  <input type="submit" class="inputSearch" value="Buscar"/>
		  </form>
  </div>

  <div class="welcome">
    <?=	mostrarSaludo() ?>
  </div>
  
</div>

