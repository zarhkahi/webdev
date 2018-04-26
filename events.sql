SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS Sigue;
DROP TABLE IF EXISTS Comenta;
DROP TABLE IF EXISTS Asistira;
DROP TABLE IF EXISTS Crea;
DROP TABLE IF EXISTS Actividades;
DROP TABLE IF EXISTS Imagen;
DROP TABLE IF EXISTS Asistentes;
DROP TABLE IF EXISTS Eventos;
DROP TABLE IF EXISTS Seguidores;
DROP TABLE IF EXISTS Usuarios;
DROP TABLE IF EXISTS Roles;


CREATE TABLE Roles (
  id_rol int(10) NOT NULL AUTO_INCREMENT,
  nombre varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO Roles (id_rol, nombre) VALUES
(1, 'admin'),
(2, 'user');


CREATE TABLE Usuarios (
  id_usuario int(10) NOT NULL AUTO_INCREMENT,
  nombre varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  apellidos varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  password varchar(70) NOT NULL,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  email varchar(50) NOT NULL,
  id_rol int(10) NOT NULL,
  PRIMARY KEY (id_usuario),
  UNIQUE KEY (email),
  FOREIGN KEY(id_rol) REFERENCES Roles(id_rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

INSERT INTO Usuarios (id_usuario, nombre, apellidos, password, fecha, email, id_rol) VALUES
(1, 'User', 'U.', '$2y$10$0eR.KhfTH5ybn/jlB86hwe/1nQeCKXk2RcLEjBscJbpUaF504kSOi', '2018-04-10 00:00:00', 'user@example.org', 1),
(2, 'Admin', 'A.', '$2y$10$0eR.KhfTH5ybn/jlB86hwe/1nQeCKXk2RcLEjBscJbpUaF504kSOi', '2018-04-10 00:00:00', 'admin@example.org', 2);


CREATE TABLE Seguidores (
  id_usuario int(10) NOT NULL,
  id_siguiendo int(10) NOT NULL,
  FOREIGN KEY(id_usuario) REFERENCES Usuarios(id_usuario),
  FOREIGN KEY(id_siguiendo) REFERENCES Usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


CREATE TABLE Eventos (
  id_evento int(10) NOT NULL AUTO_INCREMENT,
  nombre varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL,
  fecha date NOT NULL,
  lugar varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  precio int(5) DEFAULT NULL,
  id_usuario int(10) NOT NULL,
  PRIMARY KEY (id_evento),
  FOREIGN KEY(id_usuario) REFERENCES Usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


CREATE TABLE Asistentes (
  id_usuario int(10) NOT NULL,
  id_evento int(10) NOT NULL,
  FOREIGN KEY(id_usuario) REFERENCES Usuarios(id_usuario),
  FOREIGN KEY(id_evento) REFERENCES Eventos(id_evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


CREATE TABLE Imagen (
  id_imagen int(10) NOT NULL AUTO_INCREMENT,
  id_evento int(10) NOT NULL,
  tipo CHAR(15) NOT NULL,
  imagen MEDIUMBLOB NOT NULL,
  PRIMARY KEY (id_imagen),
  FOREIGN KEY(id_evento) REFERENCES Eventos(id_evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;



CREATE TABLE Actividades (
  id_actividad int(10) NOT NULL AUTO_INCREMENT,
  fecha datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_usuario int(10) NOT NULL,
  PRIMARY KEY (id_actividad),
  FOREIGN KEY(id_usuario) REFERENCES Usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


CREATE TABLE Crea (
  id_actividad int(10) NOT NULL,
  id_evento int(10) NOT NULL,
  PRIMARY KEY (id_actividad),
  FOREIGN KEY(id_actividad) REFERENCES Actividades(id_actividad),
  FOREIGN KEY(id_evento) REFERENCES Eventos(id_evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


CREATE TABLE Asistira (
  id_actividad int(10) NOT NULL,
  id_evento int(10) NOT NULL,
  PRIMARY KEY (id_actividad),
  FOREIGN KEY(id_actividad) REFERENCES Actividades(id_actividad),
  FOREIGN KEY(id_evento) REFERENCES Eventos(id_evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


CREATE TABLE Comenta (
  id_actividad int(10) NOT NULL,
  descripcion varchar(1000) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (id_actividad),
  FOREIGN KEY(id_actividad) REFERENCES Actividades(id_actividad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;


CREATE TABLE Sigue (
  id_actividad int(10) NOT NULL,
  id_usuario int(10) NOT NULL,
  PRIMARY KEY (id_actividad),
  FOREIGN KEY(id_actividad) REFERENCES Actividades(id_actividad),
  FOREIGN KEY(id_usuario) REFERENCES Usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;
