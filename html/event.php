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
      <div class="event-img"><img src="/AW/includes/fotos-eventos/37.jpg"></div>
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
/*
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
}*/

function mostrarImg(){ 
  $dir_path = "fotos-eventos".DIRECTORY_SEPARATOR; 
  $extensions_array = array('jpg','png','jpeg'); 
  if(is_dir($dir_path)) 
  { 
    $files = scandir($dir_path); 
    for($i = 0; $i < count($files); $i++) 
    { 
      if($files[$i] !='.' && $files[$i] !='..') 
      { 
        // get file extension 
        $file = pathinfo($files[$i]); 
        $extension = $file['extension']; 
 
         // check file extension 
        if(in_array($extension, $extensions_array)) 
          echo "<img src='$dir_path$files[$i]' style='width:100px;height:100px;'><br>"; 
      } 
    } 
  } 
} 


?><!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Evento</title>
</head>

<div class="site">
		<div class="header">
			<?php
				$app->doInclude('comun/cabecera.php');
			?>
		</div>

		<div class="sidebar">
			<?php
				$app->doInclude('comun/sidebarIzq.php');
			?>
		</div>

		<div class="maincontent">
	    <?= mostrarContenido() ?>
    </div>

		<div class= "footer"> 
			<?php
				$app->doInclude('comun/pie.php');
			?>
		</div>
</div>
</html>