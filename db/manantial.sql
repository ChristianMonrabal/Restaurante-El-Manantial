CREATE DATABASE elmanantial2;

USE elmanantial2;

-- Tabla de salas
CREATE TABLE tbl_sala (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sala VARCHAR(25) NOT NULL,
    tipo_sala ENUM('terraza', 'comedor', 'privada') NOT NULL,
    capacidad_total INT NOT NULL,
    imagen_sala VARCHAR(255)
);

-- Tabla de mesas
CREATE TABLE tbl_mesa (
    id_mesa INT AUTO_INCREMENT PRIMARY KEY,
    id_sala INT NOT NULL,
    num_sillas_mesa INT NOT NULL,
    estado_mesa ENUM('libre', 'ocupada') NOT NULL DEFAULT 'libre',
    FOREIGN KEY (id_sala) REFERENCES tbl_sala(id_sala)
);

-- Tabla de usuarios
CREATE TABLE tbl_usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(30) NOT NULL,
    tipo_usuario ENUM('camarero', 'gerente', 'mantenimiento', 'administrador') NOT NULL,
    email_usuario VARCHAR(50) NOT NULL UNIQUE,
    password_usuario VARCHAR(255) NOT NULL
);

-- Tabla de reservas
CREATE TABLE tbl_reserva (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_sala INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuario(id_usuario),
    FOREIGN KEY (id_sala) REFERENCES tbl_sala(id_sala)
);

-- Tabla de ocupación de mesas
CREATE TABLE tbl_ocupacion (
    id_ocupacion INT AUTO_INCREMENT PRIMARY KEY,
    id_mesa INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_hora_ocupacion DATETIME NOT NULL,
    fecha_hora_desocupacion DATETIME,
    FOREIGN KEY (id_mesa) REFERENCES tbl_mesa(id_mesa),
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuario(id_usuario)
);

INSERT INTO tbl_sala (id_sala, nombre_sala, tipo_sala, capacidad_total) VALUES
(1, 'terraza_principal', 'terraza', 14),
(2, 'terraza_secundaria', 'terraza', 14),
(3, 'terraza_terciaria', 'terraza', 14),
(4, 'comedor_interior', 'comedor', 29),
(5, 'comedor_exterior', 'comedor', 24),
(6, 'sala_privada_1', 'privada', 10),
(7, 'sala_privada_2', 'privada', 10),
(8, 'sala_privada_3', 'privada', 10),
(9, 'sala_privada_4', 'privada', 10);

INSERT INTO tbl_mesa (id_sala, num_sillas_mesa, estado_mesa) VALUES
-- Terraza Principal
(1, 2, 'libre'),
(1, 3, 'libre'),
(1, 4, 'libre'),
(1, 5, 'libre'),

-- Terraza Secundaria
(2, 2, 'libre'),
(2, 3, 'libre'),
(2, 4, 'libre'),
(2, 5, 'libre'),

-- Terraza Terciaria
(3, 2, 'libre'),
(3, 3, 'libre'),
(3, 4, 'libre'),
(3, 5, 'libre'),

-- Comedor Interior
(4, 4, 'libre'),
(4, 5, 'libre'),
(4, 2, 'libre'),
(4, 4, 'libre'),
(4, 3, 'libre'),
(4, 5, 'libre'),
(4, 6, 'libre'),

-- Comedor Exterior
(5, 4, 'libre'),
(5, 5, 'libre'),
(5, 3, 'libre'),
(5, 6, 'libre'),
(5, 4, 'libre'),
(5, 2, 'libre'),

-- Sala Privada 1
(6, 10, 'libre'),

-- Sala Privada 2
(7, 10, 'libre'),

-- Sala Privada 3
(8, 10, 'libre'),

-- Sala Privada 4
(9, 10, 'libre');

INSERT INTO tbl_usuario (nombre_usuario, tipo_usuario, email_usuario, password_usuario) VALUES
('christian.monrabal', 'camarero', 'christian.monrabal@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('anuel.aa', 'camarero', 'anuel.aa@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('laura.perez', 'gerente', 'laura.perez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('alberto.desantos', 'administrador', 'alberto.desantos@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('carlos.gomez', 'mantenimiento', 'carlos.gomez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi');
