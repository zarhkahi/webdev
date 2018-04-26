<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

function mostrarContenido() {
    $value = $_GET['search'];
    $min_length = 3;
    $html = '';

	if(strlen($value) >= $min_length){
        $events = \es\ucm\fdi\aw\Evento::searchEvents($value);
        $users = \es\ucm\fdi\aw\Usuario::searchUsers($value);

        if($events){
            $html .= "<h3> Events found: </h3><br>";
            foreach( $events as $item => $event) {
                $html .= '<form method="POST" action="event.php" id="' . $event['nombre'] . '" class="null" enctype="">
                <input class="null" name="event" value="'. $event['id_evento'] . '" type="hidden" readonly>	
                <button type="submit">' . $event['nombre'] . '</button>
                </form>';
                $html .= "<p><h2>" . $event['nombre'] . "</h2></p><br>";          }
        }
        else
            $html .= "No events found. <br>";
        if($users){
            $html .= "<h3> Users found: </h3><br>";
            foreach( $users as $item => $user) { 
                //$form = '';
                $html .= '<form method="POST" action="user.php" id="' . $user['nombre'] . '" class="null" enctype="">
                        <input class="null" name="user" value="'. $user['id_usuario'] . '" type="hidden" readonly>	
                        <button type="submit">' . $user['nombre'] . '</button>
                        </form>';
                $html .= "<p><h2>" .  $user['nombre'] . " " . $user['apellidos'] . "</h2></p><br><p>Email: " . $user['email'] . " Sing up date: "  . $user['fecha'] . "</p> <br>";
                //$html .= $form;
            }
        }
        else
            $html .= "No users found.";
    }
    else{
		$html .= "Minimum length is " . $min_length;
	}

    return $html;
  }

?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  <title>SearchResults</title>
</head>
<body>
<div id="contenedor">
	<?php
		$app->doInclude('comun/cabecera.php');
		$app->doInclude('comun/sidebarIzq.php');
	?>

	<div id="contenido">
		<h1> Resultados busqueda: </h1>
		<?= mostrarContenido() ?>
	</div>
	
	<?php
		$app->doInclude('comun/sidebarDer.php');
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>
