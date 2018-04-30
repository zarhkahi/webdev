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
		  $html = "<div class= 'event'> <h1 style=\"background-color:DodgerBlue; border:2px solid Tomato;\">" . $event->nombre_evento() . "</h1><br><p>Fecha: " . $event->fecha_evento() . "</p><br><p> Precio: "  . $event->precio_evento() . "€ </p><br><p>Lugar: " . $event->lugar_evento() . "</p></div><br>";
      if($app->usuarioLogueado() && ($event->id_usuario() == $app->idUsuario())){
        $html .=  '<form method="POST" action="updateEvento.php" class="null" enctype="">
        <input class="null" name="id_e" value="'. $id . '" type="hidden" readonly>	
        <button type="submit" id="edit">Edit</button>
        </form>';
        $html .=  '<form method="POST" action="deleteEvento.php" class="null" enctype="">
        <input class="null" name="id_e" value="'. $id . '" type="hidden" readonly>	
        <button type="submit" id="edit">Delete</button>
        </form>';
      }
    } else 
		  $html = "Error 404";
  }
  else
    $html = "Event not specified";
  return $html;
}

function mostrarImg(){
  $imagesDirectory = "fotos-eventos/";
  if(is_dir($imagesDirectory)) {
    $opendirectory = opendir($imagesDirectory);
    while (($image = readdir($opendirectory)) !== false) {
      if(($image == '.') || ($image == '..')) continue;
        $imgFileType = pathinfo($image,PATHINFO_EXTENSION);
      if(($imgFileType == 'jpg') || ($imgFileType == 'png') ||($imgFileType == 'jpeg'))
        echo "<img src='fotos-eventos/".$image."' width='200'> ";
    }
   closedir($opendirectory);
  }
  else 
    echo 'no se puede mostrar la imagen';
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
  <div id="contenedor">
    <?php
      $app->doInclude('comun/cabecera.php');
      $app->doInclude('comun/sidebarIzq.php');
    ?>
    <div class="col-10" id="contenido">
	    <?= mostrarContenido() ?>
    </div>
    <?php
      $app->doInclude('comun/pie.php');
    ?>
  </div>
</body>
</html>
