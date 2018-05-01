<?php
use es\ucm\fdi\aw;

$app = aw\Aplicacion::getSingleton();
?>

<div class="col-2 menu" id="sidebar-left">
	<h3>Menú</h3>
	<ul>
		<li><a class="effect-3" href="<?= $app->resuelve('/index.php')?>">Inicio</a></li>
		
		<!--<li><a href="<?= $app->resuelve('/admin.php')?>">Administrar</a></li>-->
		<?php
			if ($app->usuarioLogueado()) {
				echo "<li><a class=\"effect-3\" href=", $app->resuelve('/contenido.php'), ">Your Events</a></li>";
				echo "<li><a class=\"effect-3\" href=", $app->resuelve('/follow.php'), ">Following</a></li>";
				echo "<li><a class=\"effect-3\" href=", $app->resuelve('/createEvento.php'), ">Crear Evento</a></li>";
				
			}
			else
				echo "<li><a class=\"effect-3\" href=", $app->resuelve('/register.php'), ">Registro</a></li>";
		?>		
	</ul>
</div>
