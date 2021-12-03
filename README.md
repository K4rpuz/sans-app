# SansApp
### Integrantes
- Esteban Naranjo Rayo	202030533-1
- Fernando Amthauer		202030538-2
---

## Instrucciones

```sql
create user sansadmin with encrypted password 'admin' createdb login superuser;
create database sansapp;
```
---

## Aclaraciones
- Para poner un producto a la venta el usuario debe tener alguna compra registrada con la excepcion de cuando no existan productos disponibles para comprar
- Los usuarios califican y comentan cada venta, es decir no se puede calificar/comentar un producto que no han comprado y dicha calificacon/comentario puede ser editado posteriormente. Esta decision apunta al hecho de que una persona puede comprar el mismo producto en mas de una ocasion, siendo ambas experiencias distintas.
---

## To-do
- Sistema
	- navegacion sin recargar o retroceder
	- mostrar usuario activo siempre
- BD
	- ~~tablas~~
	- ~~2 triggers~~
	- ~~2 vistas~~
- Pagina Principal
	- barra de busqueda (categorias, productos, usuarios)
	- top 5 productos mejor promedio calificaciones
	- ~~top 5 productos mas vendidos~~
	- ~~top 5 usuarios con mas ventas~~
- Barra de busqueda
	- busqueda de productos y usuarios con nombre similar al buscado
	- busqueda por categoria
	- busqueda exitosa muestra info basica del producto/usuario y link
- perfil
	- edicion de datos y eliminacion de cuenta |**consultar que implica borrar todo registro asociado**
	- mostrar cantidad de productos vendidos, comprados, usuario y correo
	- historial de compras y ventas
- Carrito
	- edicion de productos en carrito
	- mostrar info de los productos; cantidad, nombre, precio...
	- Boton de compra
- Productos
	- CRUD para productos en venta
	- CRUD para comentarios 
	- debe mostrar toda la info relacionada, total vendido y unidades disponibles
	- mostrar cantidad de comentarios, promedio de calificacion y nombre del vendedor con enlace al perfil
- Historial
	- mostrar producto, fecha, precio, cantidad, nombre comprador o vendedor segun quien vea| **consultar caso de borrado**

- sistema de registro
- solo usuario registrado y logueado puede comprar
