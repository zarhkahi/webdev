<?php
use es\ucm\fdi\aw;

$app = aw\Aplicacion::getSingleton();
?>

<div id="sidebar-left">
	<h3>Menú</h3>
	<ul>
		<li><a href="<?= $app->resuelve('/index.php')?>">Inicio</a></li>
		<li><a href="<?= $app->resuelve('/contenido.php')?>">Ver contenido</a></li>
		<!--<li><a href="<?= $app->resuelve('/admin.php')?>">Administrar</a></li>
		<li><a href="<?= $app->resuelve('/mensajes.php')?>">Mensajes</a></li>-->
		<?php
			if ($app->usuarioLogueado()) {
			echo "<li><a href=", $app->resuelve('/createEvento.php'), ">Crear Evento</a></li>";
			echo "<li><a href=", $app->resuelve('/deleteEvento.php'), ">EliminarEvento</a></li>";
			}
		?>
		<li><a href="<?= $app->resuelve('/register.php')?>">Registro</a></li>
		
	</ul>
</div>
