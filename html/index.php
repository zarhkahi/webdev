<?php

/*<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
img {
    max-width: 100%;
    height: auto;
}
</style>
</head>
*/
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

function mostrarContenido() {
  $html = '';
  $app = aw\Aplicacion::getSingleton();
	if ($app->usuarioLogueado()) {
		//$html = \es\ucm\fdi\aw\Evento::showUserEvents($app->idUsuario());
		$html = \es\ucm\fdi\aw\Actividad::motrarActividad($app->idUsuario());  
	} else {

		$html = "<h1> Welcome to E-VENTS </h1><br>";
    $events = array();
    $events = \es\ucm\fdi\aw\Evento::allEvents();
    if($events){
			$html .= " <div class=\"accordion\"><ul>";
      foreach( $events as $item => $event) { 
				$html .= "<li tabindex=\"1\" ><div>" . '<form method="POST" action="event.php" class="null" enctype="">
        <input class="null" name="event" value="'. $event['id_evento'] . '" type="hidden" readonly>	
				<button type="submit" id="event_s">';
				$html .= "<h2>" . $event['nombre'] . "</h2><p> Lugar: " . $event['lugar'] . " Precio:"  . $event['precio'] . "€</p>";
				$html .= "</button></form></div></li>";
				/*$html .= "<li tabindex=\"1\" ><div><a href=\"login.php\">";
				$html .= "<h2>" . $event['nombre'] . "</h2><p> Lugar: " . $event['lugar'] . " Precio:"  . $event['precio'] . "€</p>";
				$html .= "</a></div></li>";*/
			}
			$html .= "</ul></div>";
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
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Portada</title>
</head>
<body>
<div id="contenedor">
	<?php
		$app->doInclude('comun/cabecera.php');
		$app->doInclude('comun/sidebarIzq.php');
	?>

	<div id="contenido">
		<h1>Página principal</h1>
		<p> Aquí está el contenido público, visible para todos los usuarios. </p>
		<?= mostrarContenido() ?>
	</div>
	
	<?php
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>
