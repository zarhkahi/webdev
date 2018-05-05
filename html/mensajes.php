<?php

require_once __DIR__.'/includes/config.php';

use \es\ucm\fdi\aw\Mensaje;
use \es\ucm\fdi\aw\FormularioMensaje;
use \es\ucm\fdi\aw\FormularioRespuesta;

?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>Mensajes</title>
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
            if ($app->tieneRol('user')) {
              $formMensaje = new FormularioMensaje();
              $formMensaje->gestiona();
            }
            ?>
                    <h1>Mensajes</h1>
                    <ul>
            <?php
            $mensajes = Mensaje::mensajes();
            foreach($mensajes as $m) {
            ?>
                <li><?= $m->texto()?> (<?= $m->username()?>)<?php
                if ($app->tieneRol('user')) {
                  $formRespuesta = new FormularioRespuesta($m->id());
                  $formRespuesta->gestiona();
                }
                $respuestas = Mensaje::mensajes($m->id());
                if (count($respuestas) > 0) {
                  echo '<ul>';

                  foreach($respuestas as $r ) {
            ?>
                    <li><?= $r->texto()?> (<?= $r->username()?>)</li>
            <?php
                  }
                  echo '</ul>';
                }
                ?></li>
            <?php
            }
            ?>
        </ul>
	</div>

		<div class= "footer"> 
			<?php
				$app->doInclude('comun/pie.php');
			?>
		</div>
</div>
</html>
