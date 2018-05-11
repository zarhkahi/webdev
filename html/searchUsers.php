<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

function mostrarContenido() {
    $value = $_GET['search'];
    $min_length = 3;
    $html = '<div class="wrapper">';
    $html .='<div class="search-switch"><div><form method = "GET" action = "searchEvents.php" class="formSearchEvent">
        <input class="null" name="search" value="'. $value . '" type="hidden" readonly>	
		<button class="button-switch"><span>E-vents</span></button>
        </form></div>';
    $html .='<div><form method = "GET" action = "searchUsers.php" class="formSearchUser">
        <input class="null" name="search" value="'. $value . '" type="hidden" readonly>	
		<button class="button-switch"><span>Users</span></button>
        </form></div></div>';
        
    $html .= '<div class="search-title"><h1> Resultados busqueda usuarios: </h1></h1></div>';
	if(strlen($value) >= $min_length){
        $users = \es\ucm\fdi\aw\Usuario::searchUsers($value);
        if($users){
            $html .= '<div class="search-title"><h3>Users found: </h3></div>';
            $html .= '<div class="content-search">';
            foreach( $users as $item => $user) {
                $html .= '<section class="centered-container"><form method="POST" action="user.php" id="' . $user['nombre'] . '" class="null" enctype="">
                    <input class="null" name="user" value="'. $user['id_usuario'] . '" type="hidden" readonly>
                    <button class = "link--arrowed" type="submit" id="searchButton"><h2>&#9654; ' . $user['nombre'] . " "  
                    .  $user['apellidos'] .  '   Email: ' . $user['email'] . '</h2></button></form></section>';
            }
            $html .= '</div>';
        }
        else
            $html .= '<div class="search-title"><h3>No users found.</h3></div>';
    }
    else{
		$html .= '<div class="search-title"><h3>Minimum length is ' . $min_length . '</h3></div>';
    }
    
    $html .= '</div>';

    return $html;
  }

?><!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
    <title>SearchResults</title>
</head>
<body>
<div class="site">
	<?php
		$app->doInclude('comun/cabecera.php');
		$app->doInclude('comun/sidebarIzq.php');
	?>

	<div class="maincontent">
        <div class="site-content">
            <?= mostrarContenido() ?>
        </div>
	</div>
	
	<?php
		$app->doInclude('comun/pie.php');
	?>
</div>
</body>
</html>
