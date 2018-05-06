<?php

namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Aplicacion as App;
use es\ucm\fdi\aw\ ActividadSigue as ActSigue;
class Usuario {
  
  private $id;

  private $email;

  private $password;

  private $roles;

  private $fecha;

  private $nombre;

  private $apellidos;

  private function __construct($id, $email, $password, $nombre, $apellidos, $fecha) {
    $this->id = $id;
    $this->email = $email;
    $this->password = $password;
    $this->nombre = $nombre;
    $this->apellidos = $apellidos;
    $this->fecha = $fecha;
    $this->roles = [];
  }

  public static function login($email, $password) {
    $user = self::buscaUsuario($email);
    if ($user && $user->compruebaPassword($password)) {
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      $query = sprintf("SELECT R.nombre FROM Roles R, Usuarios U WHERE U.id_rol = R.id_rol AND U.id_usuario=%s", $conn->real_escape_string($user->id));
      $rs = $conn->query($query);
      if ($rs) {
        while($fila = $rs->fetch_assoc()) { 
          $user->addRol($fila['nombre']);
        }
        $rs->free();
      }
      return $user;
    }    
    return false;
  }

  public static function buscaUsuario($email) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Usuarios WHERE email='%s'", $conn->real_escape_string($email));
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows == 1) {
      $fila = $rs->fetch_assoc();
      $user = new Usuario($fila['id_usuario'], $fila['email'], $fila['password'], $fila['nombre'], $fila['apellidos'], $fila['fecha']);
      $rs->free();
      return $user;
    }
    return false;
  }

  public static function searchUserById($id_u) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Usuarios WHERE id_usuario='%s'", $conn->real_escape_string($id_u));
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows == 1) {
      $fila = $rs->fetch_assoc();
      $user = new Usuario($fila['id_usuario'], $fila['email'], $fila['password'], $fila['nombre'], $fila['apellidos'], $fila['fecha']);
      $rs->free();
      return $user;
    }
    return false;
  }
  
  public static function register($email, $nombre, $apellidos, $password) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("INSERT INTO Usuarios (email, nombre, apellidos, password, id_rol) VALUES ('%s', '%s', '%s', '%s', 1)", $conn->real_escape_string($email), $conn->real_escape_string($nombre), $conn->real_escape_string($apellidos), password_hash($password, PASSWORD_DEFAULT));
    if ($conn->query($query) === TRUE){
      return true;
    }
    return false;
  }

  public static function searchUsers($value) {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $value = htmlspecialchars($value);
    $value = $conn->real_escape_string($value);
    $query = sprintf("SELECT * FROM Usuarios WHERE nombre LIKE '%%".$value."%%' OR apellidos LIKE '%%".$value."%%'");
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows > 0) {
        $users = array();
        while($row = $rs->fetch_assoc()){
          $users[] = $row;
        }
        $rs->free();
        return $users;
    }
    else
        return false;
  }

  //El primer id sigue al segundo
  public static function follow($id_u, $id_f){
    $app = App::getSingleton();
    $conn = $app->conexionBd();

    $query = sprintf("SELECT COUNT(*) AS total FROM Seguidores WHERE id_usuario = %s AND id_siguiendo = %s", $conn->real_escape_string($id_u), $conn->real_escape_string($id_f));
    $rv = $conn->query($query);
    $values = $rv->fetch_assoc();

    $query = sprintf("INSERT INTO Seguidores (id_usuario, id_siguiendo) SELECT * FROM (SELECT '$id_u', '$id_f') AS tmp WHERE NOT EXISTS (
      SELECT id_usuario, id_siguiendo FROM Seguidores WHERE id_usuario = '$id_u' AND id_siguiendo = $id_f) LIMIT 1;");
    if($rs = $conn->query($query)){
      if ($values['total'] == 0) {
        $act = array('tipo' => "sigue", 'id_evento' => null, 'id_usuario' => $id_u, 'id_siguiendo' => $id_f, 'descripcion' => null);
        if(!ActSigue::crearActividad($act))
          return false;
      }
      return true;
    }
    return false;
  }

    //work in progress
  public static function unfollow($id_u, $id_f){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("DELETE FROM Seguidores WHERE id_usuario = $id_u AND id_siguiendo = $id_f");
    if($rs = $conn->query($query)){
      $act = array('tipo' => "sigue", 'id_evento' => null, 'id_usuario' => $id_u, 'id_siguiendo' => $id_f);
      if(!ActSigue::eliminarActividad($act))
        return false;
    }
    else
      return false;
  }
    

  public static function getFollowing($id_u){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_siguiendo FROM Seguidores WHERE id_usuario = $id_u");
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0){
      $followers = array();
      while($row = $rs->fetch_assoc()){
          $followers[] = $row;
      }
      $rs->free();
      return $followers;
    }
    return false;
  }

  public static function checkFollow($id_u, $id_f){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Seguidores WHERE id_usuario = %s AND id_siguiendo = %s", $conn->real_escape_string($id_u), $conn->real_escape_string($id_f));
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0)
      return true;
    return false;
  }

  public static function getFollower($id_u){ //s when all changed
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_usuario FROM Seguidores WHERE id_siguiendo = $id_u");
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0){
      $followers = array();
      while($row = $rs->fetch_assoc()){
          $followers[] = $row;
      }
      $rs->free();
      return $followers;
    }
    return false;
  }


  //Los usuarios que siguen a $id_U
  public static function getMyFollowers($id_u){
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT id_usuario FROM Seguidores WHERE id_siguiendo = $id_u");
    $rs = $conn->query($query);
    if($rs && $rs->num_rows > 0){
      $followers = array();
      while($row = $rs->fetch_assoc()){
          $followers[] = $row;
      }
      $rs->free();
      return $followers;
    }
    return false;
  }





  public function id() {
    return $this->id;
  }

  public function addRol($role) {
    $this->roles[] = $role;
  }

  public function roles() {
    return $this->roles;
  }

  public function email() {
    return $this->email;
  }

  public function fecha() {
    return $this->fecha;
  }

  public function nombre() {
    return $this->nombre;
  }

  public function apellidos() {
    return $this->apellidos;
  }

  public function compruebaPassword($password) {
    return password_verify($password, $this->password);
  }

  public function cambiaPassword($nuevoPassword) {
    $this->password = password_hash($nuevoPassword, PASSWORD_DEFAULT);
  }
}

?>