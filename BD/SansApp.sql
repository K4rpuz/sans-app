SELECT * FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema';

CREATE TABLE usuario (
	rol VARCHAR(11) NOT NULL PRIMARY KEY,
	usuario VARCHAR(20) NOT NULL,
	contrasena VARCHAR(60) NOT NULL,
	correo VARCHAR(30) NOT NULL,
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
	id INT GENERATED ALWAYS AS IDENTITY,
	comprador VARCHAR(11) NOT NULL,
	producto INT NOT NULL,
	nombre_producto VARCHAR(30) NOT NULL,
	fecha DATE NOT NULL,
	cantidad INT NOT NULL,
	precio_unidad INT NOT NULL,
	calificacion INT,
	comentario VARCHAR(500),
	PRIMARY KEY (comprador, producto, id)
);

CREATE TABLE carrito (
	usuario VARCHAR(11) NOT NULL,
	producto INT NOT NULL,
	cantidad INT NOT NULL,
	PRIMARY KEY (usuario, producto),
	CONSTRAINT fk_usuario FOREIGN KEY (usuario) REFERENCES usuario(rol) ON DELETE CASCADE,
	CONSTRAINT fk_producto FOREIGN KEY (producto) REFERENCES producto(id) ON DELETE CASCADE
);

INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030533-1', 'Esteban Naranjo', '12345678', 'esteban.naranjo@usm.cl', TO_DATE('03-03-2002', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030538-2', 'Fernando Amthauer', '12345678', 'fernando.amthauer@usm.cl', TO_DATE('04-05-2000', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030513-7', 'Nicolas Ramirez', '12345678', 'nicolas.ramirez@usm.cl', TO_DATE('05-06-1999', 'DD-MM-YYYY'));
INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('202030523-4', 'Nilsson Acevedo', '12345678', 'nilsson.acevedo@usm.cl', TO_DATE('06-07-1998', 'DD-MM-YYYY'));

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

INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Celular', 'Celular de google', 20000, 10, 'Electronico', '202030533-1');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Laptop', 'Microsoft Surface', 30000, 6, 'Electronico', '202030513-7');
INSERT INTO producto(nombre, descripcion, precio, stock, categoria, vendedor) VALUES ('Television', 'Samsung', 20000, 10, 'Electronico', '202030538-2');

SELECT * FROM producto;
UPDATE producto SET stock = 0 WHERE id = 1;

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

CALL comprar_producto('202030538-2', 4, 2);

SELECT * FROM boleta;

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

SELECT * FROM carrito;
CALL agregar_producto_carrito('202030538-2', 1, 2);

DELETE FROM usuario WHERE rol = '202030538-2';