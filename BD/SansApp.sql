DROP VIEW IF EXISTS top_vendedor;
DROP VIEW IF EXISTS top_mas_vendidos;
DROP TABLE IF EXISTS carrito;
DROP TABLE IF EXISTS boleta;
DROP TABLE IF EXISTS producto;
DROP TABLE IF EXISTS usuario;


CREATE TABLE usuario (
	rol VARCHAR(11) NOT NULL PRIMARY KEY,
	usuario VARCHAR(20) NOT NULL,
	contrasena VARCHAR(60) NOT NULL,
	correo VARCHAR(30) NOT NULL UNIQUE,
	nacimiento DATE NOT NULL
);

CREATE TABLE producto (
	id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	nombre VARCHAR(30) NOT NULL,
	descripcion VARCHAR(500) NOT NULL,
	precio INT NOT NULL,
	stock INT NOT NULL,
	categoria VARCHAR(30) NOT NULL,
	vendedor VARCHAR(11) NOT NULL,
	CONSTRAINT fk_usuario FOREIGN KEY (vendedor) REFERENCES usuario(rol) ON DELETE CASCADE
);

CREATE TABLE boleta (
	id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	comprador VARCHAR(11) NOT NULL,
	producto INT NOT NULL,
	nombre_producto VARCHAR(30) NOT NULL,
	fecha DATE NOT NULL,
	cantidad INT NOT NULL,
	precio_unidad INT NOT NULL,
	calificacion INT,
	comentario VARCHAR(500)
);

CREATE TABLE carrito (
	usuario VARCHAR(11) NOT NULL,
	producto INT NOT NULL,
	cantidad INT NOT NULL,
	PRIMARY KEY (usuario, producto),
	CONSTRAINT fk_usuario FOREIGN KEY (usuario) REFERENCES usuario(rol) ON DELETE CASCADE,
	CONSTRAINT fk_producto FOREIGN KEY (producto) REFERENCES producto(id) ON DELETE CASCADE
);


CREATE OR REPLACE FUNCTION check_new_product()
	RETURNS TRIGGER
	LANGUAGE plpgsql
	AS $$
BEGIN
	IF EXISTS (SELECT FROM producto WHERE stock > 0) THEN
		IF NOT EXISTS (SELECT FROM boleta WHERE comprador = NEW.vendedor) THEN
			RAISE EXCEPTION 'No se puede vender productos sin haber comprado antes';
		END IF;
	END IF;

	RETURN NEW;
END;
$$;

CREATE TRIGGER check_new_product_trigger
	BEFORE INSERT ON producto
	FOR EACH ROW
	EXECUTE PROCEDURE check_new_product();


CREATE OR REPLACE PROCEDURE comprar_producto(rol_comprador VARCHAR(11), id_producto INT, cantidad INT)
	LANGUAGE plpgsql
	AS $$
DECLARE
	cantidad_disponible INT;
BEGIN
	IF cantidad <= 0 THEN
		RAISE EXCEPTION 'La cantidad debe ser mayor a 0';
	END IF;

	IF NOT EXISTS (SELECT FROM producto WHERE id = id_producto) THEN
		RAISE EXCEPTION 'El producto no existe';
	END IF;

	IF NOT EXISTS (SELECT FROM usuario WHERE rol = rol_comprador) THEN
		RAISE EXCEPTION 'El usuario no existe';
	END IF;
	
	SELECT stock INTO cantidad_disponible FROM producto WHERE id = id_producto;

	IF cantidad_disponible < cantidad THEN
		RAISE EXCEPTION 'No hay suficiente stock';
	END IF;

	INSERT INTO boleta (comprador, producto, nombre_producto, fecha, cantidad, precio_unidad) VALUES (rol_comprador, id_producto, (SELECT nombre FROM producto WHERE id = id_producto), CURRENT_DATE, cantidad, (SELECT precio FROM producto WHERE id = id_producto));
END;
$$;

CREATE OR REPLACE FUNCTION post_new_boleta()
	RETURNS TRIGGER
	LANGUAGE plpgsql
	AS $$
BEGIN
	UPDATE producto SET stock = stock - NEW.cantidad WHERE id = NEW.producto;
	RETURN NEW;
END;
$$;

CREATE TRIGGER post_new_boleta_trigger
	AFTER INSERT ON boleta
	FOR EACH ROW
	EXECUTE PROCEDURE post_new_boleta();


CREATE OR REPLACE PROCEDURE agregar_producto_carrito(rol_comprador VARCHAR(11), id_producto INT, cantidad_ INT)
	LANGUAGE plpgsql
	AS $$
BEGIN
	IF cantidad_ <= 0 THEN
		RAISE EXCEPTION 'La cantidad debe ser mayor a 0';
	END IF;

	IF NOT EXISTS (SELECT FROM producto WHERE id = id_producto) THEN
		RAISE EXCEPTION 'El producto no existe';
	END IF;

	IF NOT EXISTS (SELECT FROM usuario WHERE rol = rol_comprador) THEN
		RAISE EXCEPTION 'El usuario no existe';
	END IF;

	IF EXISTS (SELECT FROM carrito WHERE usuario = rol_comprador AND producto = id_producto) THEN
		UPDATE carrito SET cantidad = cantidad_ WHERE usuario = rol_comprador AND producto = id_producto;
	ELSE
		INSERT INTO carrito (usuario, producto, cantidad) VALUES (rol_comprador, id_producto, cantidad_);
	END IF;
	
END;
$$;

-- inserts
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030533-1', 'Esteban Naranjo', '12345678', 'esteban.naranjo@usm.cl', TO_DATE('03-03-2002', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030538-2', 'Fernando Amthauer', '12345678', 'fernando.amthauer@usm.cl', TO_DATE('04-05-2000', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030513-7', 'Nicolas Ramirez', '12345678', 'nicolas.ramirez@usm.cl', TO_DATE('05-06-1999', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030523-8', 'Nilsson Acevedo', '12345678', 'nilsson.acevedo@usm.cl', TO_DATE('06-07-1998', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030536-5', 'Sebastian Gonzalez', 'asd55a96', 'sebastian.gonzales@usm.cl', TO_DATE('07-08-1997', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030549-4', 'Sebastian Naranjo', '12345678', 'sebastian.naranjo@usm.cl', TO_DATE('08-09-1996', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030510-1', 'Sergio Gonzalez', '12345678', 'sergio.gonzales@usm.cl', TO_DATE('09-10-1995', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030515-0', 'Manuel Fernandez', '12345678', 'manuel.fernandez@usm.cl', TO_DATE('10-11-1994', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030520-3', 'Juan Perez', '12345678', 'juan.perez@usm.cl', TO_DATE('11-12-1993', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030525-8', 'Juan Amthauer', '12345678', 'juan.amthauer@usm.cl', TO_DATE('12-01-1992', 'DD-MM-YYYY'));

INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('llavero SansAPP', 'Llavero con el logo de SansApp', '100', '100', 'Accesorios', '202030533-1');

CALL comprar_producto('202030538-2', 1, 1);
CALL comprar_producto('202030513-7', 1, 1);
CALL comprar_producto('202030523-8', 1, 1);
CALL comprar_producto('202030536-5', 1, 1);
CALL comprar_producto('202030549-4', 1, 1);
CALL comprar_producto('202030510-1', 1, 1);
CALL comprar_producto('202030515-0', 1, 1);

INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Celular', 'Celular de google', 20000, 10, 'Electronico', '202030510-1');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Laptop', 'Microsoft Surface', 30000, 6, 'Electronico', '202030513-7');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Television', 'Samsung', 20000, 10, 'Electronico', '202030538-2');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Vestido', 'Vestido de verano', 20000, 10, 'Ropa', '202030549-4');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Zapatos', 'Zapatos de verano', 20000, 10, 'Ropa', '202030549-4');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Pantalon', 'Pantalon de verano', 20000, 10, 'Ropa', '202030549-4');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Aspiradora', 'Aspiradora Philips', 20000, 10, 'Electronico', '202030536-5');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Lavadora', 'Lavadora LG', 20000, 10, 'Electronico', '202030536-5');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Martillo', 'Martillo de pared', 20000, 10, 'Herramientas', '202030513-7');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Cuchillo', 'Cuchillo de cocina', 20000, 10, 'Herramientas', '202030513-7');

CALL comprar_producto('202030533-1', 3, 5);
CALL comprar_producto('202030536-5', 4, 4);
CALL comprar_producto('202030549-4', 5, 3);
CALL comprar_producto('202030510-1', 6, 2);
CALL comprar_producto('202030515-0', 7, 1);
CALL comprar_producto('202030513-7', 8, 2);

-- top 5 productos mas vendidos
CREATE OR REPLACE VIEW top_mas_vendidos AS SELECT p.id, p.nombre, p.precio, p.vendedor, SUM(b.cantidad) AS cantidad_vendida FROM producto as p INNER JOIN boleta as b ON p.id = b.producto GROUP BY p.id ORDER BY cantidad_vendida DESC LIMIT 5;

-- top 5 vendedores con mas ventas
CREATE OR REPLACE VIEW top_vendedor AS SELECT p.vendedor,(SELECT usuario FROM usuario WHERE rol=p.vendedor) ,SUM(b.cantidad) AS cantidad_vendida FROM producto as p INNER JOIN boleta as b ON p.id = b.producto GROUP BY p.vendedor ORDER BY cantidad_vendida DESC LIMIT 5;

