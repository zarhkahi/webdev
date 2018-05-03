<?php
use es\ucm\fdi\aw;

$app = aw\Aplicacion::getSingleton();
?>

<div class="left-sidebar" id="sidebar-left">
	<h3>Menu</h3>
	<ul>
		<li><a class="effect-3" href="<?= $app->resuelve('/index.php')?>">Home</a></li>
		
		<!--<li><a href="<?= $app->resuelve('/admin.php')?>">Administrar</a></li>-->
		<?php
			if ($app->usuarioLogueado()) {
				echo "<li><a class=\"effect-3\" href=", $app->resuelve('/contenido.php'), ">Your Events</a></li>";
				echo "<li><a class=\"effect-3\" href=", $app->resuelve('/follow.php'), ">Following</a></li>";
				echo "<li><a class=\"effect-3\" href=", $app->resuelve('/createEvento.php'), ">Create Event</a></li>";
				
			}
			else
				echo "<li><a class=\"effect-3\" href=", $app->resuelve('/register.php'), ">Registro</a></li>";
		?>		
	</ul>
</div>
