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
		<h1>Formulario para Actualizar Evento </h1>
		<?php $formEvento = new \es\ucm\fdi\aw\FormulariosEvento('actualiza',$_POST['id_update']);//,$_POST['id_e']); 
		$formEvento->gestiona(); ?>
	</div>

	<div class= "footer"> 
		<?php
			$app->doInclude('comun/pie.php');
		?>
	</div>
</div>
</html>
