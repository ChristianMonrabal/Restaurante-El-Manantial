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

-- Tabla de franjas horarias
CREATE TABLE tbl_franja_horaria (
    id_franja INT AUTO_INCREMENT PRIMARY KEY,
    franja_horaria TIME NOT NULL
);

-- Tabla de reservas
CREATE TABLE tbl_reserva (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, -- Opcional para reservas anónimas
    id_sala INT NOT NULL,
    id_mesa INT NOT NULL,
    id_franja INT NOT NULL,
    nombre_reserva VARCHAR(50) NOT NULL,
	cantidad_personas VARCHAR(2) NOT NULL,
    fecha_reserva DATE NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES tbl_usuario(id_usuario),
    FOREIGN KEY (id_sala) REFERENCES tbl_sala(id_sala),
    FOREIGN KEY (id_mesa) REFERENCES tbl_mesa(id_mesa),
    FOREIGN KEY (id_franja) REFERENCES tbl_franja_horaria(id_franja)
);

-- Inserciones iniciales en tbl_usuario
INSERT INTO tbl_usuario (nombre_usuario, tipo_usuario, email_usuario, password_usuario) VALUES
('alberto.desantos', 'administrador', 'alberto.desantos@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('anuel.aa', 'gerente', 'anuel.aa@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('christian.monrabal', 'camarero', 'christian.monrabal@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('san.benito', 'camarero', 'san.benito@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('juan.perez', 'camarero', 'juan.perez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('maria.gomez', 'camarero', 'maria.gomez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('luis.rodriguez', 'camarero', 'luis.rodriguez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('ana.martin', 'camarero', 'ana.martin@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('pedro.lopez', 'camarero', 'pedro.lopez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('laura.sanchez', 'camarero', 'laura.sanchez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('jose.luna', 'camarero', 'jose.luna@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('elena.garcia', 'camarero', 'elena.garcia@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('carlos.flores', 'camarero', 'carlos.flores@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('marta.gonzalez', 'camarero', 'marta.gonzalez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('david.moreno', 'camarero', 'david.moreno@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('sofia.mendez', 'camarero', 'sofia.mendez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('jorge.palma', 'camarero', 'jorge.palma@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('cristina.silva', 'camarero', 'cristina.silva@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('victor.martinez', 'camarero', 'victor.martinez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('lucia.castro', 'camarero', 'lucia.castro@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('felipe.garcia', 'camarero', 'felipe.garcia@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('patricia.herrera', 'camarero', 'patricia.herrera@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('manuel.rodriguez', 'camarero', 'manuel.rodriguez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('natalia.ramos', 'camarero', 'natalia.ramos@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('victoria.cano', 'camarero', 'victoria.cano@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('josefina.vazquez', 'camarero', 'josefina.vazquez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('pedro.jimenez', 'camarero', 'pedro.jimenez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('elvira.navarro', 'camarero', 'elvira.navarro@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('guillermo.suarez', 'camarero', 'guillermo.suarez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('susana.martinez', 'camarero', 'susana.martinez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('pedro.gomez', 'camarero', 'pedro.gomez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('marta.perez', 'camarero', 'marta.perez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('felix.diaz', 'camarero', 'felix.diaz@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi'),
('adriana.rodriguez', 'camarero', 'adriana.rodriguez@elmanantial.com', '$2a$12$NtbM8IYMhhkOlUl9uZ7XMenWrzmSEp6DcFfQijiMs/cmjwN2MP2bi');

-- Inserción de franjas horarias
INSERT INTO tbl_franja_horaria (franja_horaria)
VALUES 
('09:00'), ('10:00'), ('11:00'), ('12:00'),
('13:00'), ('14:00'), ('15:00'), ('16:00'),
('17:00'), ('18:00'), ('19:00'), ('20:00'),
('21:00'), ('22:00');