<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

function mostrarContenido() {
	$html = 'Lista de eventos: ' . "\n";
  	$app = aw\Aplicacion::getSingleton();
	if ($app->usuarioLogueado()) {
		$events = \es\ucm\fdi\aw\Evento::userEvents($app->idUsuario()); 
		if($events){
		  foreach ($events as $item => $event){
				$html .= "<div class=\"eventCont\">";
				//$html .= '<div><img src="/AW/includes/fotos-eventos/37.jpg"></div>';
				$html .= '<div class="miniEvent" ><form method="POST" action="event.php" id="' . $event['nombre'] . '" class="null">
                <input class="null" name="event" value="'. $event['id_evento'] . '" type="hidden" readonly>	
                <input class="imgEventMini" type="image" src="/AW/includes/fotos-eventos/37.jpg" alt="Submit Form" /></form></div>';
				//$html .= "<1h>" . $item->nombre_evento() . "</1h> \n" . "<p>" . $item->fecha_evento() . " "  . $item->precio_evento() . "</p> \n";
				$html .= "<div><h1>" . $event['nombre'] . "</h1><p> Fecha: " . $event['fecha']  . "</p></div>";
				$html .=  '<div><form method="POST" action="updateEvento.php" class="null" enctype="">
        	<input class="null" name="id_update" value="'. $event['id_evento'] . '" type="hidden" readonly>	
        	<button class="buttonEdit" type="submit"><span class="button__inner">Edit</span></button>
        	</form>';
      	$html .=  '<form method="POST" action="deleteEvento.php" class="null" enctype="">
        	<input class="null" name="id_delete" value="'. $event['id_evento'] . '" type="hidden" readonly>	
        	<button class="buttonEdit buttonDelete" type="submit"><span class="button__inner">Delete</span></button>
        	</form></div>';
				$html .= "</div>";
		  }
		}
		else{
		  $html .= "No hay eventos.";
		}
		return $html;

	} else { //No!! Should be index!
		$events = \es\ucm\fdi\aw\Evento::allEvents();
		if($events){
			foreach ($events as $item => $event){
			  $html .= "<div class=\"eventCont\">";
			  $html .= '<div><img src="/AW/includes/fotos-eventos/37.jpg"></div>';
			  $html .= "<div><h1>" . $event['nombre'] . "</h1>" . "<p> Fecha:" . $event['fecha']  . "</p></div>";
			  $html .= "</div>";
			}
		  }
		else{
			$html .= "No hay eventos.";
		}
  }

  return $html;
}

?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  	<title>Contenido</title>
</head>
<body>

<div id="contenedor">
<?php
$app->doInclude('comun/cabecera.php');
$app->doInclude('comun/sidebarIzq.php');
?>
<div class="col-10" id="contenido">

		<h1>Hello!</h1>
		<?= mostrarContenido() ?>

	
</div>
<?php
$app->doInclude('comun/pie.php');
?>
</div>
</body>
</html>
