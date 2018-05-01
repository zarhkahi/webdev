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
			$html .= '<div class="site">';
      foreach( $events as $item => $event) { 
				$html .= '<div class="box"><form method="POST" action="event.php" class="null" enctype="">
        <input class="null" name="event" value="'. $event['id_evento'] . '" type="hidden" readonly>	
				<button id= "searchButton" type="submit" id="event_s">';
				$html .= "<h2>" . $event['nombre'] . "</h2></button></form><p> Lugar: " . $event['lugar'] . " Precio:"  . $event['precio'] . "€</p></div>";
				/*$html .= "<li tabindex=\"1\" ><div><a href=\"login.php\">";
				$html .= "<h2>" . $event['nombre'] . "</h2><p> Lugar: " . $event['lugar'] . " Precio:"  . $event['precio'] . "€</p>";
				$html .= "</a></div></li>";*/
			}
			$html .= "</div>";
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

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/reset.css') ?>">-->
	<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Portada</title>
</head>
<body class="container">
<div id="contenedor">
	<?php
		$app->doInclude('comun/cabecera.php');
	?>

		<div class="row">
			<?php
				$app->doInclude('comun/sidebarIzq.php');
			?>

			<div class="col-10" id="contenido">
				<?= mostrarContenido() ?>
			</div>
		</div>

	<?php
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>
