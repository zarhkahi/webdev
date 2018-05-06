<?php
 
namespace es\ucm\fdi\aw;
 
use es\ucm\fdi\aw\Aplicacion as App;
use es\ucm\fdi\aw\Usuario as Usuario;
use es\ucm\fdi\aw\Evento as Evento;
 
class ActividadAsistira {
 
  /*
  private $id_evento;
 
  private $id_usuario;
  
  public function __construct($id_evento, $id_usuario){
    $this->id_evento = $id_evento;
    $this->id_usuario = $id_usuario;
  }*/
 
  public function crearActividad($id_e, $id_u){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("INSERT INTO actividades (id_usuario) VALUES ($id_u)", $conn->real_escape_string($id_u));
    $rs = $conn->query($query);
    if ($rs) {
      $id_actividad = $conn->insert_id;
      $query = sprintf("INSERT INTO asistira (id_actividad, id_evento) VALUES ($id_actividad, $id_e)", $conn->real_escape_string($id_e));
      if ($conn->query($query) === TRUE){
        return true;
      }
    }
    return false;
  } 


  public function eliminarActividad($id_e){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_actividad FROM crea WHERE id_evento = %s", $conn->real_escape_string($id_e));
    $searchAct = $conn->query($query);
    if ($searchAct && $searchAct->num_rows == 1){
      $value = $searchAct->fetch_assoc();
      $id_a = $value['id_actividad'];
      $query = sprintf("DELETE FROM asistira WHERE id_actividad=$id_a");
      if($conn->query($query) === TRUE){
        $query = sprintf("DELETE FROM actividades WHERE id_actividad=$id_a");
        if($conn->query($query) ===TRUE){
          $searchAct->free();////////////////////////////////////////////////////////////////
          return true;
        }
      }
    }
    return false;
  } 

  public function recopilarActAsisteSeguidores($id_u, $count = null){
    $listaCrea = array();
    $followers = Usuario::getFollowing($id_u); //
    if($followers !== FALSE){
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      foreach ($followers as $value) {
        $id_usu = $value['id_siguiendo'];
        $query = sprintf("SELECT id_actividad FROM actividades WHERE id_usuario = $id_usu ");
        $getAct = $conn->query($query);
        if($getAct && $getAct->num_rows > 0){
          while($id_a = $getAct->fetch_assoc()){
            $listaAsistira[] = array('id_u' => $id_u, 'id_f' => $value, 'actividad' => ActividadAsistira::evento_asiste($id_a['id_actividad']));
          }
          $getAct->free();
        }
      }
      return $listaAsistira;
    }
    return false;
  }

  public function evento_asiste($id_a){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_evento FROM asistira WHERE id_actividad = $id_a");
    $getE = $conn->query($query);
    if($getE && $getE->num_rows == 1){
      $id_e = $getE->fetch_assoc();
      $evento = Evento::searchEventById($id_e['id_evento']);
      $getE->free();
      return $evento;
    }
    return false;
  }
  
  //Work in progress
  public function motrarActividad($id_u){//, $fecha) {
    $html = "Actividad: <br>";
    $listaAsistira = ActividadAsistira::recopilarActAsisteSeguidores($id_u);
    if(!empty($listaAsistira)){
      foreach( $listaAsistira as $item) { 
        if($item['actividad'])
          $html .= "<h1>" . "id_f: " . $item['id_f'] .  " Asistira a: " .  $item['actividad']->nombre_evento() . "</h1> <br>";
      }
    }
    else{
      $html .= "No actividad.";
    }
    return $html;
  }
  
}
?>
