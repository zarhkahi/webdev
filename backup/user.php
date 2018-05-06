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
      $followers = aw\Usuario::getFollower($user->id());
      $following = aw\Usuario::getFollowing($user->id());
      $html = '<div class="cont"><div class="wrapperEvent">
      <div class="user-title"><div class="nested">Followers: '. (($followers == false) ? '0' : count($followers)) . '<br> Following: ' . (($following == false) ? '0' : count($following)) .'</div>
      <div class="nested"><h1>' . $user->nombre() . ' ' . $user->apellidos() . '</h1></div>';
      if($app->usuarioLogueado()){//COMPROBAR SI YA ESTA SEGUIDO
        $id_u = $app->idUsuario();
        if ($id != $id_u) {//id="follow"
          if(aw\Usuario::checkFollow($id_u, $user->id()) === TRUE){
            $html .=  '<div><form method="POST" action="follow.php?unfollow=true" class="null">
					    <input class="null" name="id_u" value="'. $id_u . '" type="hidden" readonly>
					    <input class="null" name="id_f" value="'. $user->id() . '" type="hidden" readonly>
        	    <button class="buttonFollow buttonDelete" type="submit"><span class="button__inner">Unfollow</span></button>
					    </form></div>';
          }
          else{
          $html .='<div class="nested"><form method="POST" action="user.php?follow=true" class="null" enctype="">
                  <input class="null" name="user" value="'. $id . '" type="hidden" readonly>	
                  <button class="buttonFollow" type="submit" ><span class="button__inner">Follow</span></button>
                  </form></div>';
          }
        }
      }
      $html .= '</div><div class="user-inf"><div class="nested"> Email: '. $user->email() . '</div><div class="nested">Member since: '  . $user->fecha() . '</div></div>';
      $html .= '<div class="user-title"><h3>Last five E-vents:</h3></div>';

      $events = \es\ucm\fdi\aw\Evento::userEvents($user->id(), 5);
      $numShown = 5;
        if($events){
        $html .= '<div class="user-events">';
          foreach( $events as $item => $event) { 
          if($numShown > 0){
            $html .= '<div class="single-event event' . $numShown . '">';
            $html .= '<div class="miniEvent" ><form method="POST" action="event.php" id="' . $event['nombre'] . '" class="null">
                    <input class="null" name="event" value="'. $event['id_evento'] . '" type="hidden" readonly>	
                    <input class="imgEventMini" type="image" src="'. aw\Evento::showImageById($event['id_evento']).'" alt="Submit Form" /></form>';
                    $html .= '<p>' . $event['nombre'] . '</p></div></div>';
            $numShown = $numShown - 1;
          }
        }
        $html .= "</div>";
        }
      $html .= "</div></div>";
      //$html .= '</div><div class="user-events"><div class="nested"> Email: '. $user->email() . '</div><div class="nested">Member since: '  . $user->fecha() . '</div></div></div></div>';
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Usuario</title>
</head>
<body>
<div class="site">
  <?php
    $app->doInclude('comun/cabecera.php');
    $app->doInclude('comun/sidebarIzq.php');
  ?>
  <div class="maincontent">
	  <?= mostrarContenido() ?>
  </div>
  <?php
    $app->doInclude('comun/pie.php');
  ?>
</div>
</body>
</html>