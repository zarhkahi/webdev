<?php

require_once __DIR__.'/includes/config.php';

?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Admin</title>
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
	<?php
		if ($app->tieneRol('admin', 'Acceso Denegado', 'No tienes permisos suficientes para administrar la web.')) {
				echo "<h1>Consola de administración</h1>";
				echo "<p>Aquí estarían todos los controles de administración</p>";
		}
	?>
		</div>
		<div class= "footer"> 
			<?php
				$app->doInclude('comun/pie.php');
			?>
		</div>
</div>
</html>
