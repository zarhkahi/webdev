<?php
 
namespace es\ucm\fdi\aw;
 
use es\ucm\fdi\aw\Aplicacion as App;
use es\ucm\fdi\aw\Usuario as Usuario;
use es\ucm\fdi\aw\Evento as Evento;
 
class ActividadCrea {
 
  public function crearActividad($id_e, $id_u){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("INSERT INTO Actividades (id_usuario) VALUES ($id_u)");
    $rs = $conn->query($query);
    if ($rs) {
      $id_actividad = $conn->insert_id;
      $query = sprintf("INSERT INTO Crea (id_evento, id_actividad) VALUES ($id_e, $id_actividad)");  
      if ($conn->query($query) === TRUE)
        return true;
    }
    return false;
  } 

  public function eliminarActividad($id_e){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_actividad FROM Crea WHERE id_evento = %s", $conn->real_escape_string($id_e));
    $searchAct = $conn->query($query);
    if ($searchAct && $searchAct->num_rows == 1){
      $value = $searchAct->fetch_assoc();
      $id_a = $value['id_actividad'];
      $query = sprintf("DELETE FROM Crea WHERE id_actividad=$id_a");
      if($conn->query($query) === TRUE){
        $query = sprintf("DELETE FROM Actividades WHERE id_actividad=$id_a");
        if($conn->query($query) === TRUE){
          $searchAct->free();
          return TRUE;
        }
      }
    }
    $searchAct->free();
    return FALSE;
  } 

  public function recopilarActividadCreaSeguidores($id_u, $until = null){
    $listaCrea = Array();
    $followers = Usuario::getFollowing($id_u); //
    if($followers !== FALSE){
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      foreach ($followers as $value) {
        $id_usu = $value['id_siguiendo'];
        $query = sprintf("SELECT id_actividad FROM Actividades WHERE id_usuario = $id_usu ");//AND fecha >= $fecha");
        $getAct = $conn->query($query);
        if($getAct && $getAct->num_rows > 0){
          $listaAct = array();
          //$listaCrea = array('id_f' => $id_usu);
          while($id_a = $getAct->fetch_assoc()){
            //$listaCrea[] = array('id_usu' => $id_usu,  'act' => array('id_u' => $id_u, 'id_f' => $id_usu, 'actividad' => ActividadCrea::evento_crea($id_a['id_actividad'])));
            //$listaCrea[$id_usu] = array('id_u' => $id_u, 'id_f' => $id_usu, 'actividad' => ActividadCrea::evento_crea($id_a['id_actividad']));
            $listaAct[] = array('id_u' => $id_u, 'id_f' => $id_usu, 'actividad' => ActividadCrea::evento_crea($id_a['id_actividad']));
          }
          $listaCrea[] = array('id_f' => $id_usu, 'listaAct' => $listaAct);
          $getAct->free();
        }
      }
      return $listaCrea;
    }
    return false;
  }

  public function evento_crea($id_a){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_evento FROM Crea WHERE id_actividad = $id_a", $conn->real_escape_string($id_a));
    $getE = $conn->query($query);
    if($getE && $getE->num_rows == 1){
      $id_e = $getE->fetch_assoc();
      $evento = Evento::searchEventById($id_e['id_evento']);
      $getE->free();
      return $evento;
    }
    return false;
  }

}
?>
