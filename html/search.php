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
                <p><h2><button type="submit" id="searchButton">' . $event['nombre'] . '</button>
                </form>';
                $html .= "</h2></p><br>";
            }
        }
        
        else
            $html .= "No events found. <br>";
        if($users){
            $html .= "<h3> Users found: </h3><br>";
            foreach( $users as $item => $user) { 
                //$form = '';
                $html .= '<section class="centered-container"><p><h2><form method="POST" action="user.php" id="' . $user['nombre'] . '" class="null" enctype="">
                        <input class="null" name="user" value="'. $user['id_usuario'] . '" type="hidden" readonly>
                        <button class = "link--arrowed" type="submit" id="searchButton">' . $user['nombre'] . " "  .  $user['apellidos'];
                $html .=    '<svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                            <g fill="none" stroke="#2175FF" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                            <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                            <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                            </g>
                            </svg></button></form>';
                $html .= "</h2></p></section><p>Email: " . $user['email'] . " Sing up date: "  . $user['fecha'] . "</p><br>";
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
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>
