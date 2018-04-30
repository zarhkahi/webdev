<?php

require_once __DIR__.'/includes/config.php';

?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  	<title>Login</title>
</head>
<body>
<div id="contenedor">
	<?php
		$app->doInclude('comun/cabecera.php');
		$app->doInclude('comun/sidebarIzq.php');
	?>

		<div class="col-10" id="contenido">
			<?php $formLogin = new \es\ucm\fdi\aw\FormularioUsuario("login"); $formLogin->gestiona(); ?>
		</div>
		
	<?php
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>
