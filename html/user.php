<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

$app = aw\Aplicacion::getSingleton();

function mostrarContenido() {
  $html = '';
  if(isset($_POST['user'])){
    $id = $_POST['user'];
    $user = aw\Usuario::searchUserById($id);
    $app = aw\Aplicacion::getSingleton();
	  if ($user) {
      $html = "<h1>" . $user->nombre() . " " . $user->apellidos() . "</h1><br><p>Email: " . $user->email() . "</p><br><p>Member since: "  . $user->fecha() . "</p><br>";
      if($app->usuarioLogueado()){
        $id_u = $app->idUsuario();
        if ($id != $id_u) {
          $html .='<form method="POST" action="user.php?follow=true" class="null" enctype="">
                  <input class="null" name="user" value="'. $id . '" type="hidden" readonly>	
                  <button type="submit" id="follow">Follow</button>
                  </form>';
        }
      }
	  } else 
		  $html = "Error 404"; 
  }
  else
    $htmL = 'User not specified';
  return $html;
}

function follow(){
  $app = aw\Aplicacion::getSingleton();
  if(isset($_POST['user'])){
    $id = $_POST['user'];
    if($app->usuarioLogueado())
    $id_u = $app->idUsuario();
    if(aw\Usuario::follow($id_u, $id))
      return true;
  }
  return false;
}

if (isset($_GET['follow'])) {
  follow();
}
?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Usuario</title>
</head>
<body>
<div id="contenedor">
  <?php
    $app->doInclude('comun/cabecera.php');
    $app->doInclude('comun/sidebarIzq.php');
  ?>
  <div id="contenido">
	  <?= mostrarContenido() ?>
  </div>
  <?php
    $app->doInclude('comun/sidebarDer.php');
    $app->doInclude('comun/pie.php');
  ?>
</div>
</body>
</html>