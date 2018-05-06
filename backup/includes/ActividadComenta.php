<?php
 
namespace es\ucm\fdi\aw;
 
use es\ucm\fdi\aw\Aplicacion as App;
use es\ucm\fdi\aw\Usuario as Usuario;
use es\ucm\fdi\aw\Evento as Evento;
 
class ActividadComenta {
 
  private $id_actividad;

  private $id_usuario;
  
  public function __construct($id_actividad, $id_usuario){
    $this->id_actividad = $id_actividad;
    $this->id_usuario = $id_usuario;
  }
  
    //Work in progress

  public function crearActividad($datos){
    
  } 

  protected function comentaAct($id_actividad, $descripcion){
  }

  public function eliminarActividad($datos){
   
  } 

  public function recopilarActividadSeguidores($id_u){
   
  }

  public function motrarActividad($id_u){
  }
 
  public function id_actividad(){
    return $this->id_actividad;
  }
    
  public function id_usuario(){
    return $this->id_usuario;
  }
    
    
  public function setId_actividad($id_actividad){
    $this->id_actividad = $id_actividad;
  }
    
  public function setId_usuario($id_usuario){
    $this->id_usuario=$id_usuario;
  }
  
}
?>
