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
			<?php $formLogin = new \es\ucm\fdi\aw\FormularioUsuario("login"); $formLogin->gestiona(); ?>
		</div>
		
		<div class="footer"> 
			<?php
				$app->doInclude('comun/pie.php');
			?>
		</div>
	</div>
</html>
