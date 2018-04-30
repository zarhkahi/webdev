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

  private $idEvento;

  public function __construct($type,$idEvento=null) {

    switch ($type) {
      case 'crea':
      $opciones= array('enctype'=>'multipart/form-data');//,'action'=>'includes/uploadImg.php');
      parent::__construct('formEvento',$opciones);
      break;
      case 'elimina':
      parent::__construct('formEvento');
      break;
      case 'actualiza':
      parent::__construct('formEvento');
      break;
      }
      $this->type=$type;
      $this->idEvento=$idEvento;
      $this->fecha= new DateTime('now');
  }

 /*
  * Genera el formulario adecuado segun $type.
  */
  protected function generaCamposFormulario ($datos) {
   $app = App::getSingleton();
    if ($app->usuarioLogueado()) {
      switch ($this->type) {
        case 'crea':
        $camposFormulario=self::formCreateEvent($datos);
        break;
        case 'elimina':
        $camposFormulario=self::formDeleteEvent($datos);
        break;
        case 'actualiza':
        $event=Evento::searchEventById($this->idEvento);
        $camposFormulario=$event !==FALSE ? self::formUpdateEvent($event) :'<p>No hay un evento con ese id. </p>' ;
        break;
        }
    }else
      $camposFormulario='<p>Debes loguearte para getionar los eventos. </p>';

    return $camposFormulario;
  }

  /**
   * Procesa los datos del formulario según $type.
   */
  protected function procesaFormulario($datos) {
    $result = array();
    $ok = true;
    if(strcmp($this->type,'elimina')!==0){   
      $EventName = isset($datos['EventName']) ? $datos['EventName'] : null ;
      if ( mb_strlen($EventName)<5 || $EventName=='Nombre del evento') {
        $result[] = 'Tiene que tener un nombre, min 5 caracteres.';
        $ok = false;
      }

      if(strtotime($datos['EventDate']) < strtotime($this->fecha->format('Y-m-d'))){
        $result[] = 'Fecha incorrecta.';
        $ok = false;
      }
    }
    else{
      $Eliminar=isset($datos['formDelete']) ? $datos['formDelete'] : null ;  
        if($Eliminar && strcmp($Eliminar,'no')===0){
          $result = \es\ucm\fdi\aw\Aplicacion::getSingleton()->resuelve('/index.php');
          $ok = false;
        }
    }
      if ($ok===TRUE) {
        $app = App::getSingleton();
        $datos['id_usuario']=$app->idUsuario();
        //Decide que accion ejecutar.
        $event=self::chooseAction($datos);
        $event===TRUE ? $result = \es\ucm\fdi\aw\Aplicacion::getSingleton()->resuelve('/index.php') : $result[]=$event;
      }
      return $result;
  }


//Funciones privadas que usan las funciones protegidas de arriba.

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

    //<p><label><input name="uploadImg" type="image" /></p>
    //<input type="file" name="archivo" id="archivo" /></p>
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

    private function formUpdateEvent($event){
      //Probarlo en el container por que en casa no va.
      $defaultName = $event->nombre_evento();
      $defaultPlace = $event->lugar_evento();
      $defaultPrice = $event->precio_evento();
      $defaultDes = $event->descripcion_evento();
      $defaultDate= $event->fecha_evento();
      $today=$this->fecha->format('Y-m-d');
      $camposFormulario=<<<EOF
      <fieldset>
        <legend>Datos del Evento</legend>
        <p><label>Nombre:</label> <input type="text" name="EventName" placeholder="Nombre del evento" value="$defaultName" required/></p>
        <p><label>Fecha:</label> <input type="date" name="EventDate" value="$defaultDate" min="$today" required/><br /></p>
        <p><label>Lugar:</label> <input type="text" name="EventLugar" placeholder="Lugar" value="$defaultPlace" required/><br /></p>
        <p><label>Descripcion:</label> <input type="text" name="EventDes" placeholder="Comenta de que trata el evento" value="$defaultDes" required/><br /></p>
        <p><label>Precio:</label> <input type="number" name="EventPrice" min="0" value="$defaultPrice" /><br/></p>
        <p><button type="submit">Actualizar</button></p>
      </fieldset>
EOF;

        return $camposFormulario;
    }

    private function chooseAction($datos){
      $ok=FALSE;
      switch ($this->type) {
        case 'crea':
          $event=Evento::createEvent($datos['EventName'], $datos['EventDate'], $datos['EventLugar'], $datos['EventPrice'], $datos['id_usuario'],$datos['EventDes']);
          if(is_object($event)){ 
            $ok=true;
          /*$ok=self::checkFile();
              if($ok===FALSE){
                $ok='Tipo de archivo a subir no valido.';
                $ok=$event->deleteEvent();
              } 
              /*else{
                $nombreOriginal=self::sanitize_file_uploaded_name($_FILES['uploadImg']['name']);
                $ok=$event->addImage($_FILES['uploadImg']['tmp_name'], $nombreOriginal); 
                if($ok!==TRUE)
                  $ok=$event->deleteEvent();
              }*/
          }  
        break;
        case 'elimina':
          //$ok=$event->deleteImage();
          //if($ok===TRUE)
          $event=Evento::searchEventById($this->idEvento);
          if($event!==FALSE)
            $ok=$event->deleteEvent(); 
        break;
        case 'actualiza':
          $event= new Evento($this->idEvento, $datos['EventName'], $datos['EventDate'], $datos['EventLugar'], $datos['EventPrice'], $datos['id_usuario'],$datos['EventDes']);
          $ok=$event->updateEvent();
          /*if($ok===TRUE){
            $ok=self::checkFile();
            if($ok===FALSE)
              $ok='Tipo de archivo a subir no valido.'; 
            /*else{
              $nombreOriginal=self::sanitize_file_uploaded_name($_FILES['uploadImg']['name']);
              $ok=$event->addImage($_FILES['uploadImg']['tmp_name'], $nombreOriginal); 
            }*/
          //}
          break;
        }//Switch

        return $ok;
    }

    private function checkFile(){
      //Si solo es un unico elemento y no hay errores.
      $ok=TRUE;
      $ok = count($_FILES) == 1 && $_FILES['uploadImg']['error'] == UPLOAD_ERR_OK;
      //Checkea el nombre(que hace esto?¿).
      //$ok=($ok && (mb_ereg_match('/^[0-9A-Z-_\.]+$/i',$_FILES['uploadImg']['name']) === 1) ? true : false );
      $ok=$ok && ($_FILES['uploadImg']['type'] =="image/jpeg" 
      || $_FILES['uploadImg']['type'] =="image/jpg" 
      || $_FILES['uploadImg']['type'] =="image/png" );
      $ok=$ok && ($uploadedfile_size=$_FILES['uploadImg']['size']<=300000);
      return $ok===TRUE ? 
        self::sanitize_file_uploaded_name($_FILES['uploadImg']['name'])
        :FALSE;
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
