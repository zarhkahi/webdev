<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

function mostrarContenido() {
  $html = '';
  if(isset($_POST['event'])){
    $id = $_POST['event'];
    $event = aw\Evento::searchEventById($id);
	  if ($event) {
		  $html = "<div class= 'event'> <h1>" . $event->nombre_evento() . "</h1><br><p>Fecha: " . $event->fecha_evento() . "</p><br><p> Precio: "  . $event->precio_evento() . "€ </p><br><p>Lugar: " . $event->lugar_evento() . "</p></div><br>";
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
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Evento</title>
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