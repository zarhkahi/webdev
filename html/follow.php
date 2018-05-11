<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw;

function mostrarContenido() {
	//$html = '<div class="cont">';
	$html = '<div class="wrapper">';
	$html .= '<div class="follow-title"><h2>Users you follow.</h2></div>';
  $app = aw\Aplicacion::getSingleton();
	if ($app->usuarioLogueado()) {
		$id_users = \es\ucm\fdi\aw\Usuario::getFollowing($app->idUsuario());
		if($id_users){
			$html .= '<div class="follow-user">';
		  foreach ($id_users as $item => $id_user){
				$user = \es\ucm\fdi\aw\Usuario::searchUserById($id_user['id_siguiendo']);
				$id_folowers = \es\ucm\fdi\aw\Usuario::getFollower($id_user['id_siguiendo']);
				$html .= '<div class=followCont>';
				$html .= '<div class="nameFollowing"><form method="POST" action="user.php" class="null" >
                <input class="null" name="user" value="'. $user->id() . '" type="hidden" readonly>
                <button type="submit" class="linkName"><h1>' . $user->nombre() . '</h1></button></form>'; 
      	$html .= "<h2>" .  $user->apellidos() . "</h2></div>";
			
				$events = \es\ucm\fdi\aw\Evento::userEvents($id_user['id_siguiendo']);
				if($events){
					$event = $events[count($events) - 1];
					$html .= '<div class="miniEvent" ><p>Last Event posted</p>';
					$html .= '<form method="POST" action="event.php" id="' . $event['nombre'] . '" class="null">
                <input class="null" name="event" value="'. $event['id_evento'] . '" type="hidden" readonly>	
                <input class="imgEventMini" type="image" src="'. aw\Evento::showImageById($event['id_evento']). '" alt="Submit Form" /></form>';
                $html .= '<p>' . $event['nombre'] . '</p></div>';
				}
				else{
					$html .= '<div class="no-event">Aún no tiene eventos</div>';
				}

				$html .=  '<div><form method="POST" action="follow.php?unfollow=true" class="null">
					<input class="null" name="id_u" value="'. $app->idUsuario() . '" type="hidden" readonly>
					<input class="null" name="id_f" value="'. $id_user['id_siguiendo'] . '" type="hidden" readonly>
        	<button class="buttonFollow buttonDelete" type="submit"><span class="button__inner">Unfollow</span></button>
					</form><p>Followers ';
				($id_folowers) ? $html .= sizeof($id_folowers) . '</p>' : $html .= '0</p>';
	
				$html .= "</div></div>";
		  }
		}
		else{
			$html .= '<div class="follow-title"><h2>You are following nobody.</h2></div></div>';
			return $html;
		}
		$html .= '</div></div>';
		return $html;
	}
	$html .= '</div></div>';
	return $html;
}

function unfollow(){
  $app = aw\Aplicacion::getSingleton();
  if(isset($_POST['id_u']) && isset($_POST['id_f'])){
		$id_u = $_POST['id_u'];
		$id_f = $_POST['id_f'];
    if(aw\Usuario::unfollow($id_u, $id_f))
      return true;
  }
  return false;
}

if (isset($_GET['unfollow'])) {
  unfollow();
}

?><!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/estilo.css') ?>" />
  	<title>Contenido</title>
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
