USE tienda;

-- Consultas simples
SELECT * FROM marcas ORDER BY id;
SELECT * FROM productos ORDER BY id;
SELECT * FROM vista_productos_todos; -- Test vista

-- Procedimientos almacenados
CALL spu_productos_registrar(3, 'Proyector Multimedia', 'TR1000', 2600, 24, 'S');
CALL spu_productos_filtrar_condicion('Sí');

-- Modificamos un registro y validamos acción del trigger
UPDATE productos SET precio = 1500 WHERE id = 2; -- Luego verifique campo "modificado"