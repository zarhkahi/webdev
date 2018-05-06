<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

function mostrarContenido() {
  $html = '';
  $app = aw\Aplicacion::getSingleton();
	if ($app->usuarioLogueado()) {
		$html = '<div class="site-content">';
		$html .= mostrarActividadCrea($app->idUsuario(), 4);
		$html .= mostrarActividadSigue($app->idUsuario(), 4);
		$html .= '</div>';
	} else {
		$html = '<div class="site-content">';
		$html .= mostrarLastEvents(6);
		$html .= '</div>';
  }
  return $html;
}


function mostrarActividadCrea($id_u, $count) {
	//$html = '<div class="cont">';
	$html = '';
	$numShown = $count;
	$listaCrea = aw\ActividadCrea::recopilarActividadCreaSeguidores($id_u);
	if(!empty($listaCrea)){
		foreach( $listaCrea as $item) {
			$html .= '<div class="wrapperIndex">';
			$html .= '<div class="index-title"><h1>' . aw\Usuario::searchUserById($item['id_f'])->nombre(). ' ha creado: </h1></div><div class="index-content">';
			$listaAct = $item['listaAct'];
			foreach( $listaAct as $act) {
				if($act['actividad']){
					if($numShown > 0){
					$html .= '<div class="single-event event' . $numShown . '">'; //<div class="index-content"> 1 por usuario
					$html .= '<div class="miniEvent" ><form method="POST" action="event.php" id="' . $act['actividad']->nombre_evento() . '" class="null">
									<input class="null" name="event" value="'. $act['actividad']->id_evento() . '" type="hidden" readonly>	
									<input class="imgEventMini" type="image" src="'. aw\Evento::showImageById($act['actividad']->id_evento()).'" alt="Submit Form" /></form></div>';				
					$html .= "<div><h1>" . $act['actividad']->nombre_evento() . "</h1><p> Fecha: " .$act['actividad']->fecha_evento()  . "</p></div></div>";
					}
					//$html .= "<h1>" . "id_f: " . $item['id_f'] .  " ha creado: " .  $item['actividad']->nombre_evento() . "</h1> <br>";
					//$usuario = $item['id_f'];
					$numShown = $numShown - 1;
				}
			}
			$numShown = $count;
			$html .= '</div></div>';
		}
	}
	else
		$html .= '<div class="index-title"><h2>Ningun usuario al que sigues ha creado eventos.</h2></div>';
	//$html .= '</div>';
	//$html .= '</div>';
	return $html;

}
function mostrarActividadSigue($id_u, $count) {
	$html = '';
	$numShown = $count;
	$listaSigue = aw\ActividadSigue::recopilarActividadSigueSeguidores($id_u);
		if(!empty($listaSigue)){
			$html .= '<div class="wrapperIndex">';
			foreach( $listaSigue as $item) {
				$listaAct = $item['listaAct'];
				foreach( $listaAct as $act) {
					if($act['actividad']){
						if($numShown > 0){
							$html .= '<div class="index-sigue">' . aw\Usuario::searchUserById($item['id_f'])->nombre(). ' ha seguido a '. $act['actividad']->nombre() .' </div>';
						$numShown = $numShown - 1;
						}
					}
				}
				$numShown = $count;
				
			}
			$html .= '</div>';
		}
/*
		else
			$html .= '<div class="index-title"><h2>Ningun usuario al que sigues ha seguido ha otro/s usuarios.</h2></div>';*/
		//$html .= '</div>';
	return $html;
	
}


function mostrarLastEvents($count) {
	$html = '<div class="wrapperIndex">';
	$html .= '<div class="index-title"><h1> Welcome to E-VENTS </h1></div>';
	$html .= '<div class="index-title"><h2> Last E-VENTS created.</h2></div>';
	$events = \es\ucm\fdi\aw\Evento::getNumEvents($count, null, 'last');
	$numShown = $count;
  	if($events){
		$html .= '<div class="index-contLogout">';
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
		$numShown = $count;
		$html .= "</div>";
  	}
  	else
		$html .= "WELL IT APPEARS WE HAVE NO DATA :/";



	$events = \es\ucm\fdi\aw\Evento::getNumEvents($count, 1 , 'next');
	$html .= '<div class="index-title"><h2>E-VENTS happening next week.</h2></div>';
	$numShown = $count;
  	if($events){
		$html .= '<div class="index-contLogout">';
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
		$numShown = $count;
		$html .= "</div>";
  	}
  	else
		$html .= "WELL IT APPEARS WE HAVE NO DATA :/";


	$html .= "</div>";
	return $html;
}

?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Portada</title>
</head>
<body>
<div class="site">
	<?php
		$app->doInclude('comun/cabecera.php');
	?>	
	<?php
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
