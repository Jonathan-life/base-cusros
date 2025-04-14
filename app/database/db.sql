CREATE DATABASE cursosdb;
USE cursosdb;

-- Crear la tabla de categorías
CREATE TABLE categorias
(
	id 				INT AUTO_INCREMENT PRIMARY KEY,
    categoria 		VARCHAR(100) 	NOT NULL,
    creado 			DATETIME 		NOT NULL DEFAULT NOW(),
    modificado 		DATETIME		NULL,
    CONSTRAINT uk_categoria_cat UNIQUE (categoria)
) ENGINE = INNODB;

-- Crear la tabla de cursos
CREATE TABLE cursos
(
	id 				INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria 	INT 			NOT NULL,
    titulo 			VARCHAR(255) 	NOT NULL,
    duracion_horas	INT 			NULL,
    nivel 			ENUM('Básico', 'Intermedio', 'Avanzado') NULL,
    precio 			DECIMAL(10,2) 	NULL,
    fecha_inicio 	DATE 			NULL,
    creado 			DATETIME 		NOT NULL DEFAULT NOW(),
    modificado 		DATETIME 		NULL,
    CONSTRAINT fk_idcategoria_crs FOREIGN KEY (id_categoria) REFERENCES categorias (id)
) ENGINE = INNODB;

-- Insertar algunas categorías
INSERT INTO categorias (categoria) VALUES
    ('Programación'),
    ('Matemáticas'),
    ('Ciencias Sociales'),
    ('Arte');

-- Insertar algunos cursos
INSERT INTO cursos (id_categoria, titulo, duracion_horas, nivel, precio) VALUES
    (1, 'Curso de Java Básico', 40, 'Básico', 150.00),
    (2, 'Matemáticas Avanzadas', 60, 'Avanzado', 200.00),
    (3, 'Historia del Mundo', 30, 'Intermedio', 100.00);

-- Crear una vista para ver todos los cursos
CREATE VIEW vista_cursos_todos AS
SELECT
    CR.id,
    CT.categoria,
    CR.titulo,
    CR.duracion_horas,
    CR.nivel,
    CR.precio,
    CR.fecha_inicio
FROM cursos CR
INNER JOIN categorias CT ON CR.id_categoria = CT.id
ORDER BY CR.id;

-- Procedimiento almacenado para filtrar cursos por nivel
DELIMITER //
CREATE PROCEDURE spu_cursos_filtrar_nivel(IN _nivel ENUM('Básico', 'Intermedio', 'Avanzado'))
BEGIN
    SELECT * FROM vista_cursos_todos WHERE nivel = _nivel;
END //
DELIMITER ;

-- Procedimiento almacenado para registrar un nuevo curso
DELIMITER //
CREATE PROCEDURE spu_cursos_registrar(
    IN _id_categoria INT, 
    IN _titulo VARCHAR(255),
    IN _duracion_horas INT,
    IN _nivel ENUM('Básico', 'Intermedio', 'Avanzado'),
    IN _precio DECIMAL(10,2),
    IN _fecha_inicio DATE
)
BEGIN
    INSERT INTO cursos (id_categoria, titulo, duracion_horas, nivel, precio, fecha_inicio)
    VALUES
        (_id_categoria, _titulo, _duracion_horas, _nivel, _precio, _fecha_inicio);
END //
DELIMITER ;

-- Trigger para actualizar la fecha de modificación de un curso
DELIMITER //
CREATE TRIGGER cursos_actualizar_fecha_modificacion
BEFORE UPDATE ON cursos
FOR EACH ROW
BEGIN
    SET NEW.modificado = NOW();
END //
DELIMITER ;
