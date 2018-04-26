<?php

require_once __DIR__.'/includes/config.php';

?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Crear Evento</title>
</head>
<body>
<div id="contenedor">
	<?php
		$app->doInclude('comun/cabecera.php');
		$app->doInclude('comun/sidebarIzq.php');
	?>

	<div id="contenido">
		<h1>Formulario para Crear el Evento </h1>
		<?php $formEvento = new \es\ucm\fdi\aw\FormulariosEvento('crea'); $formEvento->gestiona(); ?>
	</div>

	<?php
		$app->doInclude('comun/sidebarDer.php');
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>
