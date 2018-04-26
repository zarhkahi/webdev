<?php

namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion as App;
use es\ucm\fdi\aw\Actividad as Actividad;

class Evento {
  private $id_evento;

  private $nombre;

  private $fecha;

  private $lugar;

  private $precio;

  private $id_usuario;

  public function __construct($id_evento, $nombre, $fecha, $lugar, $precio, $id_usuario) {
    $this->id_evento = $id_evento;
    $this->nombre = $nombre;
    $this->fecha = $fecha;
    $this->lugar = $lugar;
    $this->precio = $precio;
    $this->id_usuario = $id_usuario;
  }

    public static function deleteEvent($EventName, $idUser) {
    $app = App::getSingleton();
       if(self::searchEventByName($EventName, $idUser)){
         $conn = $app->conexionBd();
         //Ojito si intentas eliminar de Eventos un evento que este en la actividad crea se queja, primero tienes que eliminar
         //la de la tabla crea quizas se puede hacer mejor pero esto lo apaña, funciona si el usuario no tiene dos eventos con el mismo nombre. 
         $query = sprintf("SELECT * FROM Eventos E, Crea C WHERE E.id_evento=C.id_evento and E.id_usuario=$idUser and E.nombre='%s'", $conn->real_escape_string($EventName));
         $rs = $conn->query($query);
         $fila = $rs->fetch_assoc();
         $idAct=$fila['id_actividad'];
         $idEvent=$fila['id_evento'];
         $rs->free(); //Por ssi acaso al sobrescribir la variable no lo libera.
         $query = sprintf("DELETE FROM Crea WHERE id_actividad=$idAct");
         $rs = $conn->query($query);
         $query = sprintf("DELETE FROM Actividades WHERE id_actividad=$idAct");
         $rs = $conn->query($query);
         $query = sprintf("DELETE FROM Imagen WHERE id_evento=$idEvent");
         $rs = $conn->query($query);
         $query = sprintf("DELETE FROM Eventos WHERE nombre='%s' and id_usuario=$idUser",$conn->real_escape_string($EventName));
         $rs = $conn->query($query);
          if ($rs===TRUE) {
            return true;//new Evento($conn->insert_id, $nombre, $fecha, $lugar, $precio, $id_usuario);
          }
          else
            return 'Error al eliminar el evento.';   
          }
      else
        return 'No tienes un evento con ese nombre o no tienes eventos. ';         

   }
 
 
  public static function createEvent($EventName, $EventDate, $EventLugar, $precio, $idUser) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("INSERT INTO Eventos (nombre,fecha,lugar,precio,id_usuario) VALUES('%s','%s','%s',$precio,$idUser)",$conn->real_escape_string($EventName),$conn->real_escape_string($EventDate),$conn->real_escape_string($EventLugar) ); //Sanear precio?
    $rs = $conn->query($query);
    if ($rs===TRUE) {
      $event=new Evento($conn->insert_id, $EventName, $EventDate, $EventLugar, $precio, $idUser);
      $act = array('tipo' => "crea", 'id_evento' => $conn->insert_id, 'id_usuario' => $idUser, 'id_siguiendo' => null, 'descripcion' => null);
      if(!Actividad::crearActividad($act))
        return false;
      return $event;//new Evento($conn->insert_id, $nombre, $fecha, $lugar, $precio, $id_usuario);
    }   
    else 
      return false;//'Error al crear el evento, fallo en la BD.';     
  }

  //$conn->error con esto comprobamos los errore de la consulta, devuelve un string con el error usadlo.
  
  public static function searchEventById($id_evento) { //nombre
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Eventos WHERE id_evento='%s'", $conn->real_escape_string($id_evento));
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows == 1) {
      $fila = $rs->fetch_assoc();
      $event = new Evento($fila['id_evento'], $fila['nombre'], $fila['fecha'], $fila['lugar'], $fila['precio'], $fila['id_usuario']);
      $rs->free();

      return $event;
    }
    return false;
  }

public static function searchEventByName($EventName,$idUser) { //nombre
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Eventos WHERE nombre='%s' and id_usuario=$idUser", $conn->real_escape_string($EventName));
    $rs = $conn->query($query);
    if ($rs and $rs->num_rows > 0) {
      $rs->free();
      return true;
    }
    return false;
  }
  
  public static function searchEvents($value) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $value = htmlspecialchars($value);
    $value = $conn->real_escape_string($value);
    $query = sprintf("SELECT * FROM Eventos WHERE nombre LIKE '%%".$value."%%' OR lugar LIKE '%%".$value."%%'");
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows > 0) {
      $events = array();
      while($row = $rs->fetch_assoc()){
        $events[] = $row;
      }
      $rs->free();
      return $events;
    }
    else
      return false;
  }

  public function addImage($rutaTemporal​, $nombreOriginal){
    if(file_exists($rutaTemporal​)){
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      $extension = substr(strrchr($nombreOriginal, "."), 1); //Obtengo la extension del archivo.
      $rutaArchivo = dirname(__DIR__).DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'fotos-eventos'.DIRECTORY_SEPARATOR.$this->id_evento.'.'.$extension;
      if(move_uploaded_file($rutaTemporal​, $rutaArchivo)){
        $query = sprintf("INSERT INTO Imagen (id_evento,descripcion) VALUES($this->id_evento,'%s')",$conn->real_escape_string($rutaArchivo));
        $rs = $conn->query($query);
        return $rs;
      }
      else
      	return 'No se ha podido mover la imagen';
    }
    return false;
  }

  public function updateEvent() {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("UPDATE Eventos SET nombre='%s',fecha='%s',lugar='%s',precio=$this->precio  WHERE id_evento=$this->id_evento",
      $conn->real_escape_string($this->nombre),$conn->real_escape_string($this->fecha),$conn->real_escape_string($this->lugar));
    $rs = $conn->query($query);
    
    return $rs===TRUE ? true : 'Error al actualizar el evento. '; 
  }

 //--

  public function allEvents() {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Eventos");
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0){
      $events = array();
      while($row = $rs->fetch_assoc()){
        //$events[] = $row;
        array_push($events,$row);
      }
      $rs->free();
      return $events;
    }
    else
      return false;
  }

  //He quitado el if de usuario logueado por que ya lo compuebas en contenido y al profe eso no le gusta ;)
  public function userEvents($id) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Eventos WHERE id_usuario = $id");
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0){
      $events = array();
      while($row = $rs->fetch_assoc()){
        if($row['id_usuario'] == $id){
          //$events[] = $row;
          array_push($events,$row);
        }
      }
      $rs->free();
      return $events;
    }
    else
      return false;
  }

  public function showAllEvents() {
    $html = "Lista de eventos: <br>";
    $events = array();
    $events = Evento::allEvents();
    //$html = implode (" <br> " , $events);
    if($events){
      foreach( $events as $item => $event) { 
        //echo $item.': id is '.$event['id'].', size is '.$event['size'];
        $html .= "<h1>" . $event['nombre'] . "</h1> <br>" . "<p> Fecha:" . $event['fecha'] . " Precio:"  . $event['precio'] . "€ Lugar: " . $event['lugar'] . "</p> <br>";
        //$html .= "<br> <p>" . $event['nombre'] . "</p>";
        //$html = "A";
      }
   }
   else{
     $html .= "No hay eventos.";
   }
    return $html;
  }

  public function showUserEvents($id) {
    $html = 'Lista de eventos: ' . "\n";
    $events = Evento::userEvents($id); 
    if($events){
      foreach ($events as $item => $event){
        //$html .= "<1h>" . $item->nombre_evento() . "</1h> \n" . "<p>" . $item->fecha_evento() . " "  . $item->precio_evento() . "</p> \n";
        $html .= "<h1>" . $event['nombre'] . "</h1> <br>" . "<p> Fecha:" . $event['fecha'] . " Precio:"  . $event['precio'] . "€ Lugar: " . $event['lugar'] . "</p> <br>";
      }
    }
    else{
      $html .= "No hay eventos.";
    }
    return $html;
  }
	//--
  public function id_evento() {
    return $this->id_evento;
  }

  public function nombre_evento() {
    return $this->nombre;
  }

  public function fecha_evento() {
    return $this->fecha;
  }

  public function lugar_evento() {
    return $this->lugar;
  }

  public function precio_evento() {
    return $this->precio;
  }
//--
  public function cambiarName($nuevo_nombre) {
    $this->nombre = $nuevo_nombre;
  }

  public function cambiaDate($nueva_fecha) {
    $this->fecha = $nueva_fecha;
  }

  public function cambiaPlace($nueva_lugar) {
    $this->fecha = $nuevo_lugar;
  }

  public function cambiaPrice($nuevo_precio) {
    $this->precio = $nuevo_precio;
  }
//--

}

?>
