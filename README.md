# SansApp
### Integrantes
- Esteban Naranjo Rayo	202030533-1
- Fernando Amthauer	202030538-2
---

## Instrucciones

Descomprimir el fichero zip y colocar la carpeta del proyecto en el directorio xampp/htdocs.

Antes de probar el proyecto se debe ir a la consola de postgres (psql) y utilizar el comando: 
```sql
CREATE DATABASE sansapp;
```
Luego crear el super usuario sansadmin, con el comando:
```sql
create user sansadmin with encrypted password 'admin' createdb login superuser;
```

Ejecutar el script que se encuentra en la carpeta 'BD' llamado 'SansApp.sql'.

Visitar la dirección localhost/sans-app/PHP en el navegador.

---

## Aclaraciones
- Para poner un producto a la venta el usuario debe tener alguna compra registrada con la excepcion de cuando no existan productos disponibles para comprar
- Los usuarios califican y comentan cada venta, es decir no se puede calificar/comentar un producto que no han comprado y dicha calificacon/comentario puede ser editado posteriormente. Esta decision apunta al hecho de que una persona puede comprar el mismo producto en mas de una ocasion, siendo ambas experiencias distintas.
- Cuando un usuario es borrado desaparece toda la información relacionada al mismo, con excepción de la información de las boletas (donde aparece su nombre y rol). Esto con el propósito de que la otras partes involucradas en la transacción mantengan registro de las mismas.
