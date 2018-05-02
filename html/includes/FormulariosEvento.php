<?php

namespace es\ucm\fdi\aw;
use \Datetime;

use es\ucm\fdi\aw\Aplicacion as App;

/**
 * Clase de  de gestión de formularios.
 *
 * Gestión de token CSRF está basada en: https://www.owasp.org/index.php/PHP_CSRF_Guard
 */
class FormulariosEvento extends Form {

  private $type;

  private $event;

  public function __construct($type,$idEvento=null) {

    switch ($type) {
      case 'crea':
        $opciones= array('enctype'=>'multipart/form-data', 'class' => 'formNewEvent');//,'action'=>'includes/uploadImg.php');
        parent::__construct('formCreaEvento',$opciones);
      break;
      case 'elimina':
        $opciones = array('class' => 'formDeleteEvent');
        parent::__construct('formEliminaEvento', $opciones);
      break;
      case 'actualiza':
        $opciones= array('enctype'=>'multipart/form-data', 'class' => 'formUpdateEvent');//,'action'=>'includes/uploadImg.php');
        parent::__construct('formActualizaEvento',$opciones);
      break;
      }
      $this->type=$type;
      $this->fecha= new DateTime('now');
      $this->event=new Evento($idEvento);
  }

 /*
  * Genera el formulario adecuado segun $type.
  */
  protected function generaCamposFormulario ($datos) {
    $app = App::getSingleton();
    switch ($this->type) {
      case 'crea':
        $camposFormulario=self::formCreateEvent($datos);
      break;
      case 'elimina':
        $this->event=Evento::searchEventById($this->event->id_evento());
        $camposFormulario=($this->event!==FALSE && $this->event->id_usuario()===$app->idUsuario() ? //NO NEED
        self::formDeleteEvent($datos) :'<p>No hay un evento con ese id o no te pertenece ese evento. </p>');
      break;
      case 'actualiza':
        $this->event=Evento::searchEventById($this->event->id_evento());
        $camposFormulario=$this->event !==FALSE && $this->event->id_usuario()===$app->idUsuario() ?
        self::formUpdateEvent() :'<p>No hay un evento con ese id o no te pertenece ese evento. </p>' ;
      break;
    }
    return $camposFormulario;
  }

  private function formCreateEvent($datos){
    //$this->fecha->format('Y-m-d');
    $defaultName = '';
    $defaultPlace = '';
    $defaultPrice = '';
    $defaultDes = '';
    $defaultPrice='';
    $defaultDate=$this->fecha->format('Y-m-d');
    $today=$defaultDate;
        if ($datos) {
          $defaultName = isset($datos['EventName']) ? $datos['EventName'] : $defaultName;
          $defaultPlace = isset($datos['EventLugar']) ? $datos['EventLugar'] : $defaultPlace;
          $defaultPrice = isset($datos['EventPrice']) ? $datos['EventPrice'] :$defaultPrice;
          $defaultDes = isset($datos['EventDes']) ? $datos['EventDes'] : $defaultDes;
          $defaultDate = isset($datos['EventDate']) ? $datos['EventDate'] : $defaultDate;
        }
          $camposFormulario=<<<EOF
      <fieldset>
        <legend>Datos del Evento</legend>
        <p><label>Nombre:</label> <input type="text" name="EventName" placeholder="Nombre del evento" value="$defaultName" required/></p>
        <p><label>Fecha:</label> <input type="date" name="EventDate" value="$defaultDate" min="$today" required/><br /></p>
        <p><label>Lugar:</label> <input type="text" name="EventLugar" placeholder="Lugar" value="$defaultPlace" required/><br /></p>
        <p><label>Descripcion:</label> <input type="textarea" name="EventDes" placeholder="Comenta de que trata el evento" value="$defaultDes" required/><br /></p>
        <p><label>Precio(euros):</label> <input type="number" name="EventPrice" placeholder="Si es gratis mejor" min="0" value="$defaultPrice" /><br/></p>
        <p><label>Imagen:</label><input name="uploadImg" type="file" required /><br /></p>
        <p><button type="submit" name=submit>Crear</button></p>
      </fieldset>
EOF;
        return $camposFormulario;
    }

    private function formDeleteEvent($datos){
      $camposFormulario=<<<EOF
      <fieldset>
        <legend>¿Seguro que quieres eliminar este evento?</legend>
        <p><button type="submit" name="formDelete" value="si">Si</button></p>
        <p><button type="submit" name="formDelete" value="no">No</button></p>
      </fieldset>
EOF;
      return $camposFormulario;
    }

  private function formUpdateEvent(){
    //Probarlo en el container por que en casa no va.
    $defaultName = $this->event->nombre_evento();
    $defaultPlace = $this->event->lugar_evento();
    $defaultPrice = $this->event->precio_evento();
    $defaultDes = $this->event->descripcion_evento();
    $defaultDate= $this->event->fecha_evento();
    $today=$this->fecha->format('Y-m-d');
    $camposFormulario=<<<EOF
    <fieldset>
      <legend>Datos del Evento</legend>
      <p><label>Nombre:</label> <input type="text" name="EventName" placeholder="Nombre del evento" value="$defaultName" required/></p>
      <p><label>Fecha:</label> <input type="date" name="EventDate" value="$defaultDate" min="$today" required/><br /></p>
      <p><label>Lugar:</label> <input type="text" name="EventLugar" placeholder="Lugar" value="$defaultPlace" required/><br /></p>
      <p><label>Descripcion:</label> <input type="text" name="EventDes" placeholder="Comenta de que trata el evento" value="$defaultDes" required/><br /></p>
      <p><label>Precio(euros):</label> <input type="number" name="EventPrice" min="0" value="$defaultPrice" /><br/></p>
      <p><label>Imagen:</label><input name="uploadImg" type="file" required /><br /></p>
      <p><button type="submit">Actualizar</button></p>
    </fieldset>
EOF;

      return $camposFormulario;
  }

  protected function procesaFormulario($datos) {
      //POST ya no tiene id_evento
  
    if(strcmp($this->type,'elimina') === 0) return $this->procesaDelete($datos);
    else if(strcmp($this->type,'crea')=== 0) return $this->procesaNew($datos);
    else if(strcmp($this->type,'actualiza')=== 0) return $this->procesaUpdate($datos);
    else return 'ERROR 505';
  }

  protected function procesaDelete($datos) {
    $result = array();
    $Eliminar = isset($datos['formDelete']) ? $datos['formDelete'] : null ;  
    if($Eliminar && strcmp($Eliminar,'no')===0){
      $result = \es\ucm\fdi\aw\Aplicacion::getSingleton()->resuelve('/contenido.php');
    }
    else {
      
      if($this->event->deleteImage() === TRUE)
        ($this->event->deleteEvent() === TRUE) ? $result = \es\ucm\fdi\aw\Aplicacion::getSingleton()->resuelve('/contenido.php') : $result;
      else
        $result[]= 'ERROR 101';
    }
    return $result;
  }


  protected function procesaNew($datos) {
    $result = array();
    $EventName = isset($datos['EventName']) ? $datos['EventName'] : null ;
    if ( mb_strlen($EventName)<5 || $EventName=='Nombre del evento') {
      $result[] = 'Tiene que tener un nombre, min 5 caracteres.';
      return $result;
    }
  
    if(strtotime($datos['EventDate']) < strtotime($this->fecha->format('Y-m-d'))){
      $result[] = 'Fecha incorrecta.';
      return $result;
    }

    $app = App::getSingleton();
    $datos['id_usuario']=$app->idUsuario();
    $this->event=Evento::createEvent($datos['EventName'], $datos['EventDate'], $datos['EventLugar'], $datos['EventPrice'], $datos['id_usuario'],$datos['EventDes']);
    $check = FALSE;
    if(is_object($this->event) === TRUE){
      if(self::checkFile() === FALSE){
        $this->event->deleteEvent();
        $result[] = 'Tipo de archivo a subir no valido. ';
      }
      else{
        $nombreOriginal=self::sanitize_file_uploaded_name($_FILES['uploadImg']['name']);
        //$check = $event->addImage($_FILES['uploadImg']['tmp_name'], $nombreOriginal);
        if($this->event->addImage($_FILES['uploadImg']['tmp_name'], $nombreOriginal)!==TRUE)
          $event->deleteEvent();
        $check = TRUE;
      }
    }
  
    $check===TRUE ? $result = \es\ucm\fdi\aw\Aplicacion::getSingleton()->resuelve('/index.php') : $result;
    return $result;
  }

  protected function procesaUpdate($datos) {
    $result = array();
    $EventName = isset($datos['EventName']) ? $datos['EventName'] : null ;
    if ( mb_strlen($EventName)<5 || $EventName=='Nombre del evento') {
      $result[] = 'Tiene que tener un nombre, min 5 caracteres.';
      return FALSE;
    }
  
    if(strtotime($datos['EventDate']) < strtotime($this->fecha->format('Y-m-d'))){
      $result[] = 'Fecha incorrecta.';
      return FALSE;
    }
         
    $check = FALSE;
    $this->event->cambiarName($datos['EventName']); $this->event->cambiaDate($datos['EventDate']); $this->event->cambiaPlace($datos['EventLugar']);
    $this->event->cambiaPrice($datos['EventPrice']); $this->event->cambiarDescripcion($datos['EventDes']);
    if($this->event->updateEvent() ===TRUE){
      if(self::checkFile()===FALSE)
        $result[]='Tipo de archivo a subir no valido.';
      else{
        $nombreOriginal=self::sanitize_file_uploaded_name($_FILES['uploadImg']['name']);
        $check=$this->event->addImage($_FILES['uploadImg']['tmp_name'], $nombreOriginal);
      }
    }
      
    $check===TRUE ? $result = \es\ucm\fdi\aw\Aplicacion::getSingleton()->resuelve('/index.php') : $result;
    return $result;
    }

  private function checkFile(){
    //Si solo es un unico elemento y no hay errores.
    $ok=TRUE;
    $ok = count($_FILES) == 1 && $_FILES['uploadImg']['error'] == UPLOAD_ERR_OK;
    //$ok=($ok && (mb_ereg_match('/^[0-9A-Z-_\.]+$/i',$_FILES['uploadImg']['name']) === 1) ? true : false );
    $ok=$ok && ($_FILES['uploadImg']['type'] =="image/jpeg"
    || $_FILES['uploadImg']['type'] =="image/jpg"
    || $_FILES['uploadImg']['type'] =="image/png" );
    $ok=$ok && ($uploadedfile_size=$_FILES['uploadImg']['size']<=300000);
    return $ok===TRUE ? self::sanitize_file_uploaded_name($_FILES['uploadImg']['name']) : FALSE;
  }

  private function sanitize_file_uploaded_name($filename) {
      /* Remove anything which isn't a word, whitespace, number
       * or any of the following caracters -_~,;[]().
       * If you don't need to handle multi-byte characters
       * you can use preg_replace rather than mb_ereg_replace
       * Thanks @Łukasz Rysiak!
       */
    $newName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
      // Remove any runs of periods (thanks falstro!)
    $newName = mb_ereg_replace("([\.]{2,})", '', $newName);

    return $newName;
  }

  }//Class
?>
