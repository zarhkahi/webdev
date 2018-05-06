<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;


function mostrarContenido() {
  $html = '';
  $app = aw\Aplicacion::getSingleton();
  if(isset($_POST['event'])){
    $id = $_POST['event'];
    $event = aw\Evento::searchEventById($id);
	  if ($event) {
		  //$html = "<div class= 'event'> <h1>" . $event->nombre_evento() . "</h1><br><p>Fecha: " . $event->fecha_evento() . "</p><br><p> Precio: "  . $event->precio_evento() . "€ </p><br><p>Lugar: " . $event->lugar_evento() . "</p></div><br>";
      $html = '<div class="cont"><div class="wrapperEvent">
        <div class="event-title"><div class="nested"></div><div class="nested"><h1>'. $event->nombre_evento() . '</h1></div>';
     
      if($app->usuarioLogueado() && ($event->id_usuario() == $app->idUsuario())){
        $html .=  '<div class="nested"><form method="POST" action="updateEvento.php" class="null" enctype="">
        <input class="null" name="id_update" value="'. $id . '" type="hidden" readonly>	
        <button class="buttonEdit" type="submit"><span class="button__inner">Edit</span></button>
        </form>';
        $html .=  '<form method="POST" action="deleteEvento.php" class="null" enctype="">
        <input class="null" name="id_delete" value="'. $id . '" type="hidden" readonly>	
        <button class="buttonEdit buttonDelete" type="submit"><span class="button__inner">Delete</span></button>
        </form></div>';
      }
      $html .= '</div>
      <div class="event-img"><img src="'. aw\Evento::showImageById($event->id_evento()).'"></div>
      <div class="event-inf"><div class="nested">Fecha: ' . $event->fecha_evento() . '</div><div class="nested">Precio: '  . $event->precio_evento() . 
      '€ </div><div class="nested">Lugar: ' . $event->lugar_evento() . '</div></div>
      <div class="event-content"><h2>Descripción: </h2><p>' . $event->descripcion_evento() . '</p></div> 
      <div class="event-com">Comments coming soon!</div></div></div>';
    } else 
		  $html = "Error 404";
  }
  else
    $html = "Event not specified";
  return $html;
}


?><!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Evento</title>
</head>
<body>
  <div class="site">
    <?php
      $app->doInclude('comun/cabecera.php');
      $app->doInclude('comun/sidebarIzq.php');
    ?>
    <div class="maincontent">
      <div class="site-content">
        <?= mostrarContenido() ?>
      </div>
    </div>
    <?php
      $app->doInclude('comun/pie.php');
    ?>
  </div>
</body>
</html>
