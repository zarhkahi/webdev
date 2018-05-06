<?php

namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion as App;

class FormularioUsuario extends Form {

  private $HTML5_EMAIL_REGEXP = "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$";
  private $HTML5_EMAIL_TITLE = "Debes introducir una dirección de E-mail válida";
  private $TEXTO_REGEXP = ".{0,30}";
  private $TEXTO_TITLE = "Cualquier cosa";
  private $PASS_REGEXP = ".{5,255}";
  private $PASS_TITLE = "Debes introducir una contraseña válida (a partir de 5 caracteres)";

  private $type;

  public function __construct($type) {
    $this->type = $type;
    parent::__construct($type);
  }

  protected function generaCamposFormulario ($datos) {
    if ($this->type == "login") return $this->generaCamposLogin($datos);
    else if ($this->type == "register") return $this->generaCamposRegister($datos);
    else return $this->type;
  }

  protected function generaCamposLogin($datos) {
    $email = 'user@example.org';
    $password = '';
    if ($datos) {
      $email = isset($datos['email']) ? $datos['email'] : $email;
      $password = isset($datos['password']) ? $datos['password'] : $password;
    }

    $camposFormulario=<<<EOF
    <fieldset>
      <legend>Login</legend>
      <p><label>E-mail:</label> <input type="email" pattern="$this->HTML5_EMAIL_REGEXP" title="$this->HTML5_EMAIL_TITLE" name="email" value="$email" required/></p>
      <p><label>Password:</label> <input type="password" name="password" value="$password" required/><br /></p>
      <button id="submit" type="submit">Entrar</button>
    </fieldset>
EOF;
    return $camposFormulario;
  }

  protected function generaCamposRegister($datos) {
    $app = App::getSingleton();
    if ($app->usuarioLogueado()) {
      $camposFormulario='<p>Debes cerrar sesion para registrar un nuevo usuario.</p>';
    }
    else{
      $email = '';
      $nombre = '';
      $apellidos ='';
      $password = '';
      if ($datos) {
        $email = isset($datos['email']) ? $datos['email'] : $email;
        $nombre = isset($datos['nombre']) ? $datos['nombre'] : $email;
        $apellidos = isset($datos['apellidos']) ? $datos['apellidos'] : $email;
        $password = isset($datos['password']) ? $datos['password'] : $password;
      }
      $camposFormulario=<<<EOF
      <fieldset>
      <legend>Registro</legend>
      <p><label>E-mail:</label> <input type="email" pattern="$this->HTML5_EMAIL_REGEXP" title="$this->HTML5_EMAIL_TITLE" name="email" value="$email" required/></p>
      <p><label>Nombre:</label> <input type="text" pattern="$this->TEXTO_REGEXP" title="$this->TEXTO_TITLE" name="nombre" value="$nombre" required/></p>
      <p><label>Apellidos:</label> <input type="text" pattern="$this->TEXTO_REGEXP" title="$this->TEXTO_TITLE" name="apellidos" value="$apellidos" required/></p>
      <p><label>Password:</label> <input type="password" pattern="$this->PASS_REGEXP" title="$this->PASS_TITLE" name="password" value="$password" required/><br /></p>
      <input id="submit" type="submit" value="Registrarse">
    </fieldset>
EOF;
    }
    return $camposFormulario;
  }


  /**
   * Procesa los datos del formulario.
   */
  protected function procesaFormulario($datos) {
    if ($this->type == "login") return $this->procesaLogin($datos);
    else if ($this->type == "register") return $this->procesaRegistro($datos);
    else return 'Aquí va un código de error, o algo';
  }

  protected function procesaLogin($datos) {
    $result = array();
    $ok = true;
    $email = isset($datos['email']) ? $datos['email'] : null ;
    if ( !$email || ! mb_ereg_match($this->HTML5_EMAIL_REGEXP, $email) ) {
      $result[] = $this->HTML5_EMAIL_TITLE;
      $ok = false;
    }
    $password = isset($datos['password']) ? $datos['password'] : null ;
    if ( ! $password || ! mb_ereg_match($this->PASS_REGEXP, $password) ) {
      $result[] = $this->PASS_TITLE;
      $ok = false;
    }
    if ( $ok ) {
      $user = Usuario::login($email, $password);
      if ( $user ) {
        session_regenerate_id(true);
        Aplicacion::getSingleton()->login($user);
        $result = \es\ucm\fdi\aw\Aplicacion::getSingleton()->resuelve('/index.php');
      }
      else {
        $result[] = 'Usuario o contraseña incorrectos';
      }
    }
    return $result;
  }

  protected function procesaRegistro($datos) {
    $result = array();
    $ok = true;
    $email = isset($datos['email']) ? $datos['email'] : null ;
    if ( !$email || ! mb_ereg_match($this->HTML5_EMAIL_REGEXP, $email) ) {
      $result[] = $this->HTML5_EMAIL_TITLE;
      $ok = false;
    }
    $password = isset($datos['password']) ? $datos['password'] : null ;
    if ( ! $password || ! mb_ereg_match($this->PASS_REGEXP, $password) ) {
      $result[] = $this->PASS_TITLE;
      $ok = false;
    }
    $nombre = isset($datos['nombre']) ? $datos['nombre'] : null ;
    if ( ! $nombre || ! mb_ereg_match($this->TEXTO_REGEXP, $nombre) ) {
      $result[] = $this->TEXTO_TITLE;
      $ok = false;
    }
    $apellidos = isset($datos['apellidos']) ? $datos['apellidos'] : null ;
    if ( ! $apellidos || ! mb_ereg_match($this->TEXTO_REGEXP, $apellidos) ) {
      $result[] = $this->TEXTO_TITLE;
      $ok = false;
    }
    if ( $ok ) {
      $user = Usuario::register($email, $nombre, $apellidos, $password);
      if ( $user ) {
        return $this->procesaLogin($datos);
      }
      else {
        $result[] = "Ese e-mail ya estaba registrado";
      }
    }
    return $result;
  }

}
?>