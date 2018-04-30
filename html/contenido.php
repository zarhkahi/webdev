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
<div id="contenido">
	<?php
		if ($app->tieneRol('user', NULL, NULL)):
	?>
		<h1>Hello!</h1>
		<?= mostrarContenido() ?>
	<?php
		elseif ($app->tieneRol('admin', NULL , NULL)):
	?>
		<h1>Hello!</h1>
		<p> Consola ADMIN (work in progress). <br></p>
		<?= mostrarContenido() ?>
	<?php
		else: 
	?>
		<h1> No estas iniciado! <br></h1> 
		<p> Debes iniciar sesión para ver tu contenido. <br></p>
	<?php
		endif;
	?>
	
</div>
<?php
$app->doInclude('comun/pie.php');
?>
</div>
</body>
</html>
