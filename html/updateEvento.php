<?php
require_once __DIR__.'/includes/config.php';
?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  	<title>Actualizar Evento</title>
</head>
<body>
<div class="site">
	<?php
		$app->doInclude('comun/cabecera.php');
		$app->doInclude('comun/sidebarIzq.php');
	?>

	<div class="maincontent">
		<div class="site-content">
			<h1>Formulario para Actualizar Evento </h1>
			<?php $formEvento = new \es\ucm\fdi\aw\FormActualizarEvento($_POST['id_update']); $formEvento->gestiona(); ?>
		</div>
	</div>

	<?php
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>