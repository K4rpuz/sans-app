SELECT * FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema';

INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030533-1', 'Esteban Naranjo', '12345678', 'esteban.naranjo@usm.cl', TO_DATE('03-03-2002', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030538-2', 'Fernando Amthauer', '12345678', 'fernando.amthauer@usm.cl', TO_DATE('04-05-2000', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030513-7', 'Nicolas Ramirez', '12345678', 'nicolas.ramirez@usm.cl', TO_DATE('05-06-1999', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030523-4', 'Nilsson Acevedo', '12345678', 'nilsson.acevedo@usm.cl', TO_DATE('06-07-1998', 'DD-MM-YYYY'));

INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Celular', 'Celular de google', 20000, 10, 'Electronico', '202030533-1');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Laptop', 'Microsoft Surface', 30000, 6, 'Electronico', '202030513-7');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Television', 'Samsung', 20000, 10, 'Electronico', '202030538-2');

SELECT * FROM producto;
UPDATE producto SET stock = 0 WHERE id = 1;

CALL comprar_producto('202030538-2', 4, 2);

SELECT * FROM boleta;

SELECT * FROM carrito;
CALL agregar_producto_carrito('202030538-2', 1, 2);

DELETE FROM usuario WHERE rol = '202030538-2';

CREATE OR REPLACE FUNCTION cantidad_en_carrito(rol_comprador VARCHAR(11), id_producto INT)
	RETURNS INT
	LANGUAGE plpgsql
	AS $$
DECLARE
	cantidad_disponible INT;
BEGIN
	SELECT cantidad INTO cantidad_disponible FROM carrito WHERE usuario = rol_comprador AND producto = id_producto;
	RETURN cantidad_disponible;
END;
$$;