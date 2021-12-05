DROP VIEW IF EXISTS usuario_info;
DROP VIEW IF EXISTS calificaciones;
DROP VIEW IF EXISTS producto_info;
DROP VIEW IF EXISTS view_carrito;
DROP VIEW IF EXISTS top_vendedor;
DROP VIEW IF EXISTS top_mas_vendidos;
DROP TABLE IF EXISTS carrito;
DROP TABLE IF EXISTS boleta;
DROP TABLE IF EXISTS producto;
DROP TABLE IF EXISTS usuario;
DROP PROCEDURE IF EXISTS comprar_producto;
DROP PROCEDURE IF EXISTS agregar_producto_carrito;


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
	rol_comprador VARCHAR(11) NOT NULL,
	rol_vendedor VARCHAR(11) NOT NULL,
	id_producto INT NOT NULL,
	nombre_producto VARCHAR(30) NOT NULL,
	fecha DATE NOT NULL,
	cantidad INT NOT NULL,
	precio_unidad INT NOT NULL,
	calificacion INT,
	comentario VARCHAR(500)
);

CREATE TABLE carrito (
	rol_usuario VARCHAR(11) NOT NULL,
	id_producto INT NOT NULL,
	cantidad INT NOT NULL,
	PRIMARY KEY (rol_usuario, id_producto),
	CONSTRAINT fk_usuario FOREIGN KEY (rol_usuario) REFERENCES usuario(rol) ON DELETE CASCADE,
	CONSTRAINT fk_producto FOREIGN KEY (id_producto) REFERENCES producto(id) ON DELETE CASCADE
);


CREATE OR REPLACE FUNCTION check_new_product()
	RETURNS TRIGGER
	LANGUAGE plpgsql
	AS $$
BEGIN
	IF EXISTS (SELECT FROM producto WHERE stock > 0) THEN
		IF NOT EXISTS (SELECT FROM boleta WHERE rol_comprador = NEW.vendedor) THEN
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

	INSERT INTO boleta (rol_comprador,rol_vendedor, id_producto, nombre_producto, fecha, cantidad, precio_unidad) VALUES (rol_comprador,(SELECT vendedor FROM producto WHERE id = id_producto), id_producto, (SELECT nombre FROM producto WHERE id = id_producto), CURRENT_DATE, cantidad, (SELECT precio FROM producto WHERE id = id_producto));
END;
$$;

CREATE OR REPLACE FUNCTION post_new_boleta()
	RETURNS TRIGGER
	LANGUAGE plpgsql
	AS $$
BEGIN
	UPDATE producto SET stock = stock - NEW.cantidad WHERE id = NEW.id_producto;
	RETURN NEW;
END;
$$;

CREATE TRIGGER post_new_boleta_trigger
	AFTER INSERT ON boleta
	FOR EACH ROW
	EXECUTE PROCEDURE post_new_boleta();


CREATE OR REPLACE PROCEDURE agregar_producto_carrito(rol_comprador VARCHAR(11), producto_id INT, cantidad_ INT)
	LANGUAGE plpgsql
	AS $$
BEGIN
	IF cantidad_ <= 0 THEN
		RAISE EXCEPTION 'La cantidad debe ser mayor a 0';
	END IF;

	IF NOT EXISTS (SELECT FROM producto WHERE id = producto_id AND stock >= cantidad_) THEN
		RAISE EXCEPTION 'No hay suficiente stock';
	END IF;

	IF NOT EXISTS (SELECT FROM producto WHERE id = producto_id) THEN
		RAISE EXCEPTION 'El producto no existe';
	END IF;

	IF NOT EXISTS (SELECT FROM usuario WHERE rol = rol_comprador) THEN
		RAISE EXCEPTION 'El usuario no existe';
	END IF;

	IF EXISTS (SELECT FROM carrito WHERE rol_usuario = rol_comprador AND id_producto = producto_id) THEN
		UPDATE carrito SET cantidad = cantidad_ WHERE rol_usuario = rol_comprador AND id_producto = producto_id;
	ELSE
		INSERT INTO carrito (rol_usuario, id_producto, cantidad) VALUES (rol_comprador, producto_id, cantidad_);
	END IF;
	
END;
$$;

-- inserts
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030533-1', 'Esteban Naranjo', '$2y$10$kq2ODfn/iZ2OPDlBGLeTCOebS.nLQ3Qgvn1Y7bUF0dxKkbg4RkFq2', 'esteban.naranjo@usm.cl', TO_DATE('2002-03-03', 'YYYY-MM-DD')); -- 12345678
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030538-2', 'Fernando Amthauer', '$2y$10$zQXQO1yfSn4qgpOMRAnm2.FAbRwyo3z4mNDViaoCX2CENXNzLqFky', 'fernando.amthauer@usm.cl', TO_DATE('2000-05-04', 'YYYY-MM-DD')); -- 12345678
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030513-7', 'Nicolas Ramirez', '$2y$10$NtIn3X5v4kUblmgJzFgJAuosUJ16G99zuFXFtYR0BG8o3gxifErCC', 'nicolas.ramirez@usm.cl', TO_DATE('1999-06-05', 'YYYY-MM-DD')); -- 12345678
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030523-8', 'Nilsson Acevedo', '$2y$10$5/jOedNq7zqNc0UOUJSJsu1qS9e4iQ3etHmWeQjgv/8IGewZfPv7G', 'nilsson.acevedo@usm.cl', TO_DATE('1998-07-06', 'YYYY-MM-DD')); -- 12345678
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030536-5', 'Sebastian Gonzalez', '$2y$10$Z34G3dmCddFr/waJIepEE.mZuXqzk3VeBdF9uEoM4BfXMM6aSx8rm', 'sebastian.gonzales@usm.cl', TO_DATE('1997-08-07', 'YYYY-MM-DD')); -- asd55a96
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030549-4', 'Sebastian Naranjo', '$2y$10$LthakVrf/i34okq9s7qk7e7o70MKjLVM5mLmq/HbYKDnd9bkmCusW', 'sebastian.naranjo@usm.cl', TO_DATE('1996-09-08', 'YYYY-MM-DD')); -- 12345678
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030510-1', 'Sergio Gonzalez', '$2y$10$Nwg/HsKqyQa7lkU2mexgyOjQpKzzTyCha0VuZHPY4r1yuVJeq463S', 'sergio.gonzales@usm.cl', TO_DATE('1995-10-09', 'YYYY-MM-DD')); -- 12345678
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030515-0', 'Manuel Fernandez', '$2y$10$98R61qo0WBmFOr.p/RnITeePEoYLvxxQZqqNNKFTRJDvb9xLm2lB.', 'manuel.fernandez@usm.cl', TO_DATE('1994-11-10', 'YYYY-MM-DD')); -- 12345678
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030520-3', 'Juan Perez', '$2y$10$E/WEJpx2l97TxQMCGYKDdeduISnkO8u07d3F3r5rHICXsQ.hXUoD.', 'juan.perez@usm.cl', TO_DATE('1993-12-11', 'YYYY-MM-DD')); -- 12345678
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030525-8', 'Juan Amthauer', '$2y$10$iDWcw2.cTWfxNoptacIOwepX5EFKyVtTGWG4HrwYfeD13gOXxvnK.', 'juan.amthauer@usm.cl', TO_DATE('1992-01-12', 'YYYY-MM-DD')); -- 12345678

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

CALL comprar_producto('202030538-2', 1, 2);
CALL comprar_producto('202030538-2', 11, 5);
CALL comprar_producto('202030533-1', 3, 5);
CALL comprar_producto('202030536-5', 4, 4);
CALL comprar_producto('202030549-4', 5, 3);
CALL comprar_producto('202030510-1', 6, 2);
CALL comprar_producto('202030515-0', 7, 1);
CALL comprar_producto('202030513-7', 8, 2);

-- top 5 productos mas vendidos
CREATE OR REPLACE VIEW top_mas_vendidos AS SELECT p.id, p.nombre, p.precio, p.vendedor, SUM(b.cantidad) AS cantidad_vendida FROM producto as p INNER JOIN boleta as b ON p.id = b.id_producto GROUP BY p.id ORDER BY cantidad_vendida DESC LIMIT 5;

-- top 5 vendedores con mas ventas
CREATE OR REPLACE VIEW top_vendedor AS SELECT u.rol, u.usuario ,SUM(b.cantidad) AS cantidad_vendida FROM usuario as u INNER JOIN boleta as b ON u.rol = b.rol_vendedor GROUP BY u.rol ORDER BY cantidad_vendida DESC LIMIT 5;

-- vista para carrito
CREATE OR REPLACE VIEW view_carrito AS SELECT rol_usuario,id, nombre, precio, stock, cantidad, (cantidad*precio) AS subtotal FROM producto INNER JOIN carrito ON id=id_producto;

-- vista para datos de producto
CREATE OR REPLACE VIEW producto_info AS SELECT p.id, p.nombre, p.precio,p.stock,p.descripcion,p.categoria, p.vendedor,(SELECT usuario FROM usuario WHERE rol=p.vendedor) AS nombre_vendedor, SUM(b.cantidad) AS cantidad_vendida, AVG(b.calificacion) AS calificacion_promedio FROM producto as p FULL JOIN boleta as b ON p.id = b.id_producto GROUP BY p.id;

-- vista calificaciones
CREATE OR REPLACE VIEW calificaciones AS SELECT id,nombre_producto, rol_comprador,(SELECT usuario FROM usuario WHERE rol=rol_comprador) AS nombre_comprador, calificacion, comentario,fecha FROM boleta WHERE calificacion IS NOT NULL;

-- vista para datos de usuario
CREATE OR REPLACE VIEW usuario_info AS SELECT u.rol, u.usuario,u.correo,u.nacimiento, SUM(b.cantidad) AS cantidad_vendida, AVG(b.calificacion) AS calificacion_promedio, SUM(b.cantidad*b.precio_unidad) AS ganancias_totales FROM usuario as u FULL JOIN boleta as b ON u.rol = b.rol_vendedor GROUP BY u.rol;

-- vista top 5 calificaciones
CREATE OR REPLACE VIEW top_calificaciones AS SELECT p.id, p.nombre, p.precio, p.vendedor, ROUND(AVG(b.calificacion),2) AS calificacion_promedio FROM producto as p INNER JOIN boleta as b ON p.id = b.id_producto GROUP BY p.id HAVING AVG(b.calificacion) IS NOT NULL;
