import {hideElement, loadElements, makeAFetch, showElement, updateNameNavBar} from "./dom.js";
import {loadForm} from "./forms.js";
import { path } from "./config.js";

document.addEventListener("DOMContentLoaded", ()=>{
	const [ botonEditarNombre, botonEditarCorreo, botonCambioPass,
		botonEliminarCuenta, formCambioNombre, formCambioCorreo,
		pNombre, pCorreo,contenedorOpciones, formCambioPass,pErrorCambioPass, pNacimiento, formCambioNacimiento, botonEditarNacimiento] = loadElements(
		[ 'boton-editar-nombre','boton-editar-correo','boton-cambio-password','boton-eliminar-cuenta','form-cambio-nombre','form-cambio-correo','p-nombre','p-correo','perfil-opciones', 'form-cambio-password','error-cambio-clave','p-nacimiento','form-cambio-nacimiento','boton-editar-nacimiento' ]
	);	

	const [ dataNombre, postNombre ] = loadForm('form-cambio-nombre',()=>{});

	formCambioNombre.addEventListener('submit',( e )=>{e.preventDefault()});
	formCambioCorreo.addEventListener('submit',( e )=>{e.preventDefault()});

	
	botonEditarNombre.addEventListener('click', async ()=> {
		if( botonEditarNombre.innerHTML === '<img src="../IMG/editar.png" class="boton-perfil" alt="">'){
			showElement(formCambioNombre);
			hideElement(pNombre);
			botonEditarNombre.innerHTML = '<img class="boton-perfil" src="../IMG/confirmacion.png" alt="">';
		}else{
			hideElement(formCambioNombre);
			showElement(pNombre);
			botonEditarNombre.innerHTML = '<img class="boton-perfil" src="../IMG/editar.png" alt="">';
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
		if( botonEditarCorreo.innerHTML === '<img src="../IMG/editar.png" class="boton-perfil" alt="">' ){
			showElement(formCambioCorreo);
			hideElement(pCorreo);
			botonEditarCorreo.innerHTML = '<img class="boton-perfil" src="../IMG/confirmacion.png" alt="">';
		}else{
			hideElement(formCambioCorreo);
			showElement(pCorreo);
			botonEditarCorreo.innerHTML = '<img src="../IMG/editar.png" class="boton-perfil" alt="">';
			const resp = await postCorreo(path.perfil);
			if( resp['ANSWER'] === 'OK' ) pCorreo.innerHTML= dataCorreo.valores['correo'];
			else console.log(resp['ANSWER']);
		}
	});

	botonCambioPass.addEventListener("click", () => {
		hideElement(contenedorOpciones);
		showElement(formCambioPass);
	});

	const [dataPassword, postPassword] = loadForm('form-cambio-password',()=>{});

	formCambioPass.addEventListener("submit", async ( e ) => {
		e.preventDefault();
		const resp = await postPassword(path.perfil);
		if( resp['ANSWER'] !== "OK" ){
			pErrorCambioPass.innerHTML="La contrase??a no coincide";
			showElement(pErrorCambioPass);
			setTimeout(() => {
				hideElement(pErrorCambioPass);
			},2000);
		}else{
			showElement(contenedorOpciones);
			hideElement(formCambioPass);
		}
	});

	botonEliminarCuenta.addEventListener('click', async ()=>{
		const resp = await makeAFetch( path.perfil, [
			['request','eliminar-perfil']
		]);	
		if( resp['ANSWER'] === 'OK' ) window.location.replace('./index.php');
	});

	const [ dataNacimiento, postNacimiento ] = loadForm('form-cambio-nacimiento',()=>{});
	botonEditarNacimiento.addEventListener('click', async () => {
		if( botonEditarNacimiento.innerHTML === '<img src="../IMG/editar.png" class="boton-perfil" alt="">' ){
			showElement(formCambioNacimiento);
			hideElement(pNacimiento);
			botonEditarNacimiento.innerHTML = '<img class="boton-perfil" src="../IMG/confirmacion.png" alt="">';
		}else{
			hideElement(formCambioNacimiento);
			showElement(pNacimiento);
			botonEditarNacimiento.innerHTML = '<img src="../IMG/editar.png" class="boton-perfil" alt="">';
			const resp = await postNacimiento(path.perfil);
			if( resp['ANSWER'] === 'OK' ) pNacimiento.innerHTML= dataNacimiento.valores['nacimiento'];
			else console.log(resp['ANSWER']);
			
		}
	});
	
});
