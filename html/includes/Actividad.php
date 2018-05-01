<?php
 
namespace es\ucm\fdi\aw;
 
use es\ucm\fdi\aw\Aplicacion as App;
use es\ucm\fdi\aw\Usuario as Usuario;
use es\ucm\fdi\aw\Evento as Evento;
 
class Actividad {
 
  private $id_actividad;
 
  private $fecha;
 
  private $id_usuario;
  
  public function __construct($id_actividad, $fecha, $id_usuario){
    $this->id_actividad = $id_actividad;
    $this->fecha = $fecha;
    $this->id_usuario = $id_usuario;
  }
 
 
 
  protected function addActivity($datos = array()){
  } 

  
  /*
  $datos ( 'tipo' => null, 'id_evento' => null, 'id_usuario' => null, 'id_siguiendo' => null, 'descripcion' => null );
  tipo = crea || asiste || sigue || comenta
  */
  
  public function crearActividad($datos){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $id_u = $datos['id_usuario'];
    $query = sprintf("INSERT INTO Actividades (id_usuario) VALUES ($id_u)");
    $rs = $conn->query($query);
    if ($rs) {
      $id_actividad = $conn->insert_id;
      $id_evento = $datos['id_evento'];
      switch ($datos['tipo']) {
        case 'crea':
            //self::creaAct($fila['id_actividad'], $datos['id_evento']);
            $query = sprintf("INSERT INTO Crea (id_evento, id_actividad) VALUES ( $id_evento, $id_actividad)");
            break;
        case "asiste":
            //self::asisteAct($fila['id_actividad'], $datos['id_evento']);
            $query = sprintf("INSERT INTO Asistira (id_actividad, id_evento) VALUES ($id_actividad, $id_evento)");
            break;
        case "sigue":
            $id_f = $datos['id_siguiendo'];
            //self::sigueAct($id_actividad, $datos['id_siguiendo']);
            $query = sprintf("INSERT INTO Sigue (id_actividad, id_usuario) VALUES ($id_actividad, $id_f)");
            break;
        case "comenta":
            self::comentaAct($id_actividad, $datos['descripcion']);
            break;
        default:
            return false;
      }
      if ($conn->query($query) === TRUE){
        return true;
      }
    }
    return false;
  } 

  protected function comentaAct($id_actividad, $descripcion){
  }

   /*
  $datos ( 'tipo' => null, 'id_evento' => null, 'id_usuario' => null, 'id_siguiendo' => null);
  tipo = crea || asiste || sigue || comenta
  */
  public function eliminarActividad($datos){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $id_u = $datos['id_usuario'];
    
    switch ($datos['tipo']) {
      case 'crea':
          $id_actividad = $datos['id_actividad'];
          $query = sprintf("DELETE FROM Crea WHERE id_actividad=$id_actividad");
          $rs = $conn->query($query);
          $query = sprintf("DELETE FROM Actividades WHERE id_actividad=$id_actividad");
          $rs = $conn->query($query);
          break;
      case "asiste":
          break;
      case "sigue":
          $id_f = $datos['id_siguiendo'];
          $query = sprintf("SELECT id_actividad FROM Sigue WHERE id_usuario = %s", $conn->real_escape_string($id_f));
          $searchAct = $conn->query($query);
          if ($searchAct && $searchAct->num_rows == 1){
            $value = $searchAct->fetch_assoc();
            $id_a = $value['id_actividad'];

            $query = sprintf("DELETE FROM Sigue WHERE id_actividad = %s", $conn->real_escape_string($id_a));
            $conn->query($query);

            $query = sprintf("DELETE FROM Actividades WHERE id_actividad = %s", $conn->real_escape_string($id_a));
            $conn->query($query);

            return true;
          }
          $rs = false;
          break;
      case "comenta":
          self::comentaAct($id_actividad, $datos['descripcion']);
          break;
      default:
          return false;
    }

    if ($rs) {
      return true;
    }
    return false;
  } 

/*
  $listaCrea ( 'id_u' => $id_u, 'id_f' => $value, 'actividad' => null);
  tipo = crea || asiste || sigue || comenta
  */


  public function recopilarActividadSeguidores($id_u){//, $fecha) {
    $listaCrea = array();
    $followers = Usuario::getFollowing($id_u); //
    if($followers){
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      foreach ($followers as $value) {
        $id_usu = $value['id_siguiendo'];
        $query = sprintf("SELECT id_actividad FROM Actividades WHERE id_usuario = $id_usu ");//AND fecha >= $fecha");
        $getAct = $conn->query($query);
        if($getAct && $getAct->num_rows > 0){
          while($id_a = $getAct->fetch_assoc()){
            $listaCrea[] = array('id_u' => $id_u, 'id_f' => $id_usu, 'actividad' => Actividad::crea($id_a['id_actividad']));
            //$listaAsistira[] = array('id_u' => $id_u, 'id_f' => $value, 'actividad' => Actividad::asiste($id_a['id_actividad']));
            //$listaSigue[] = array('id_u' => $id_u, 'id_f' => $value, 'actividad' => Actividad::sigue($id_a['id_actividad']));
          }
          $getAct->free();
        }
      }
      return $listaCrea;
    }
    return false;
  }

  public function crea($id_a){
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

  public function asiste($id_a){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_evento FROM Asistira WHERE id_actividad = $id_a");
    $getE = $conn->query($query);
    if($getE && $getE->num_rows == 1){
      $id_e = $getE->fetch_assoc();
      $evento = Evento::searchEventById($id_e['id_evento']);
      $getE->free();
      return $evento;
    }
    return false;
  }

  public function sigue($id_a){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_usuario FROM Sigue WHERE id_actividad = $id_a");
    $getUsu = $conn->query($query);
    if($getUsu && $getUsu->num_rows == 1){
      $id_u = $getUsu->fetch_assoc();
      $user = Usuario::searchUserById($id_u['id_usuario']);
      $getUsu->free();
      return $user;
    }
    return false;
  }
  
  //Work in progress
  public function motrarActividad($id_u){//, $fecha) {
    $html = "Actividad: <br>";
    $listaCrea = Actividad::recopilarActividadSeguidores($id_u);
    if(!empty($listaCrea)){
      foreach( $listaCrea as $item) { 
        if($item['actividad'])
          $html .= "<h1>" . "id_f: " . $item['id_f'] .  " ha creado: " .  $item['actividad']->nombre_evento() . "</h1> <br>";
      }
    }
    else{
      $html .= "No actividad.";
    }
    return $html;
  }
 
  public function id_actividad(){
    return $this->id_actividad;
  }
    
  public function fecha(){
    return $this->fecha;
  }
    
  public function id_usuario(){
    return $this->id_usuario;
  }
    
    
  public function setId_actividad($id_actividad){
    $this->id_actividad = $id_actividad;
  }
    
  public function setFecha($fecha){
    $this->fecha=$fecha;
  }
    
  public function setId_usuario($id_usuario){
    $this->id_usuario=$id_usuario;
  }
  
}
?>
