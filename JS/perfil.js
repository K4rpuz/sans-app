import {hideElement, loadElements, showElement, updateNameNavBar} from "./dom.js";
import {loadForm} from "./forms.js";
import { path } from "./config.js";


document.addEventListener("DOMContentLoaded", ()=>{
	const [ botonEditarNombre, botonEditarCorreo, botonHistorialCompra, botonHistorialVenta, botonCambioPass, botonEliminarCuenta, formCambioNombre, formCambioCorreo, pNombre, pCorreo] = loadElements(
		[ 'boton-editar-nombre','boton-editar-correo','boton-historial-compra','boton-historial-venta','boton-cambio-password','boton-eliminar-cuenta','form-cambio-nombre','form-cambio-correo','p-nombre','p-correo' ]
	);	

	const [ dataNombre, postNombre ] = loadForm('form-cambio-nombre',()=>{});
	
	botonEditarNombre.addEventListener('click', async ()=> {
		if( botonEditarNombre.innerHTML === '<img src="../IMG/editar.png" alt="">' ){
			showElement(formCambioNombre);
			hideElement(pNombre);
			botonEditarNombre.innerHTML = '<img src="../IMG/confirmacion.png" alt="">';
		}else{
			hideElement(formCambioNombre);
			showElement(pNombre);
			botonEditarNombre.innerHTML = '<img src="../IMG/editar.png" alt="">';
			const resp = await postNombre(path.perfil);
			if( resp['ANSWER'] === 'OK' ) {
				const name = dataNombre.valores['user'];
				pNombre.innerHTML= name;
				updateNameNavBar(name);
			}						
		}
		
	});
	const [ dataCorreo, postCorreo ] = loadForm('form-cambio-correo',()=>{});
	botonEditarCorreo.addEventListener('click', async () => {
		if( botonEditarCorreo.innerHTML === '<img src="../IMG/editar.png" alt="">' ){
			showElement(formCambioCorreo);
			hideElement(pCorreo);
			botonEditarCorreo.innerHTML = '<img src="../IMG/confirmacion.png" alt="">';
		}else{
			hideElement(formCambioCorreo);
			showElement(pCorreo);
			botonEditarCorreo.innerHTML = '<img src="../IMG/editar.png" alt="">';
			const resp = await postCorreo(path.perfil);
			if( resp['ANSWER'] === 'OK' ) pCorreo.innerHTML= dataCorreo.valores['correo'];
			else console.log(resp['ANSWER']);
		}
	});
});
