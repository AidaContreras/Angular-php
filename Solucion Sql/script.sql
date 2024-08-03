-- Consulta 1 - Libros Prestados:
-- • Encuentra el título y el autor de los libros actualmente prestados, junto
--  con el nombre del usuario que los tiene prestados. Incluye también la
--  fecha de préstamo y la fecha de devolución.

SELECT
    libro.titulo,
    libro.autor,
    CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_usuario,
    prestamo.fecha_prestamo, 
    prestamo.fecha_devolucion 
FROM prestamo
JOIN libro ON libro.id = prestamo.libro_id
JOIN usuario ON usuario.id = prestamo.usuario_id;


-- Consulta 2 - Libros No Devueltos en 7 días:
-- • Encuentra los títulos y autores de los libros que fueron prestados hace
--  más de 7 días y aún no han sido devueltos. Incluye el nombre del
--  usuario que los tiene prestados y la fecha de préstamo.

SELECT
	prestamo.id,
    libro.titulo,
    libro.autor,
    CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_usuario,
    prestamo.fecha_prestamo
FROM prestamo
JOIN libro ON libro.id = prestamo.libro_id
JOIN usuario ON usuario.id = prestamo.usuario_id
WHERE prestamo.fecha_prestamo < CURDATE() - INTERVAL 7 DAY and prestamo.fecha_devolucion is null;

