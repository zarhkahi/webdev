<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

function mostrarContenido() {
  $html = '';
  $app = aw\Aplicacion::getSingleton();
	if ($app->usuarioLogueado()) {
		$html = \es\ucm\fdi\aw\Evento::showUserEvents($app->idUsuario());  
	} else {
		$html = \es\ucm\fdi\aw\Evento::showAllEvents();
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
