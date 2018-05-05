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

?><!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  </head>
      <div class= "headerContent">
        <h1 class= "webTitle">E-Vents</h1>
        <p>Your N*1 place to create, share and follow events!</p>
      </div>

      <div class = "search">
          <form method = "GET" action = "search.php">
            <input type="text" name="search" placeholder="Buscar ">	
            <input type="submit" class="inputSearch" value="Buscar"/>
          </form>
      </div>

      <div class="welcome">
          <?=	mostrarSaludo() ?>
      </div>
</html>

