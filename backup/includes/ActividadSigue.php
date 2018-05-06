<?php
 
namespace es\ucm\fdi\aw;
 
use es\ucm\fdi\aw\Aplicacion as App;
use es\ucm\fdi\aw\Usuario as Usuario;
use es\ucm\fdi\aw\Evento as Evento;
 
class ActividadSigue {
  
  public function crearActividad($datos){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $id_u = $datos['id_usuario'];
    $id_f = $datos['id_siguiendo'];
    $query = sprintf("INSERT INTO actividades (id_usuario) VALUES ($id_u)");
    $rs = $conn->query($query);
    if ($rs) {
      $id_actividad = $conn->insert_id;
      $query = sprintf("INSERT INTO sigue (id_actividad, id_usuario) VALUES ($id_actividad, $id_f)");   
      if ($conn->query($query) === TRUE){
        return true;
        $conn->free();///////////////////////////////////////////////////////////
      }
    }
    return false;
  }

  public function eliminarActividad($datos){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $id_u = $datos['id_usuario'];
    $id_f = $datos['id_siguiendo'];
    $query = sprintf("SELECT id_actividad FROM sigue WHERE id_usuario = %s", $conn->real_escape_string($id_f));
    $searchAct = $conn->query($query);
    if ($searchAct && $searchAct->num_rows == 1){
      $value = $searchAct->fetch_assoc();
      $id_a = $value['id_actividad'];
      $query = sprintf("DELETE FROM sigue WHERE id_actividad = %s", $conn->real_escape_string($id_a));
      if($conn->query($query) === TRUE){
        $query = sprintf("DELETE FROM actividades WHERE id_actividad = %s", $conn->real_escape_string($id_a));
        if($conn->query($query) === TRUE)
            return TRUE;
            //$conn->free();///////////////////////////////////////////////////////////
      }
    }

    return FALSE;
  } 

  public function recopilarActividadSigueSeguidores($id_u, $count = null){
    $listaSigue = array();
    $followers = Usuario::getFollowing($id_u);
    if($followers !== FALSE){
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      foreach ($followers as $value) {
        $id_usu = $value['id_siguiendo'];
        $query = sprintf("SELECT id_actividad FROM actividades WHERE id_usuario = $id_usu ");//count
        $getAct = $conn->query($query);
        if($getAct && $getAct->num_rows > 0){
          $listaAct = array();
          while($id_a = $getAct->fetch_assoc()){
            $listaAct[] = array('id_u' => $id_u, 'id_f' => $id_usu, 'actividad' => ActividadSigue::usuario_sigue($id_a['id_actividad']));
          }
          $listaSigue[] = array('id_f' => $id_usu, 'listaAct' => $listaAct);
          $getAct->free();
        }
      }
      return $listaSigue;
    }
    return false;
  }

  public function usuario_sigue($id_a){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_usuario FROM sigue WHERE id_actividad = $id_a");
    $getUsu = $conn->query($query);
    if($getUsu && $getUsu->num_rows == 1){
      $id_u = $getUsu->fetch_assoc();
      $user = Usuario::searchUserById($id_u['id_usuario']);
      $getUsu->free();
      return $user;
    }
    return false;
  }
  
}
?>
