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
